<?php
class ModDescsHelper
{
    public static function getDescs($directionID = 0, $stationID = 0): array
    {
        if ($directionID === 0 && $stationID === 0) return array();
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`d`.*, i.station, i.tppd")
            ->from("`#__rw_desc` `d`")
            ->rightJoin("`#__rw_stations_info` `i` on `i`.`id` = `d`.`stationID`");
        if ($stationID > 0) {
            $query->where("`i`.`id` = {$stationID}");
        }
        if ($directionID > 0) {
            $query
                ->leftJoin("`#__station_directions` `sd` on `sd`.`stationID` = `d`.`stationID`")
                ->where("`sd`.`directionID` = {$directionID}");
        }
        $items = $db->setQuery($query)->loadAssocList();
        if (empty($items) || ($items[0]['time_mask'] === null)) return array();
        $result = array();
        foreach ($items as $item) {
            $arr = array();
            $arr['time_mask'] = JText::sprintf("MOD_DESCS_TIME_MASK_{$item['time_mask']}");
            $dat_1 = JDate::getInstance(date("Y-m-d ").$item['time_1']);
            $dat_2 = JDate::getInstance(date("Y-m-d ").$item['time_2']);
            $arr['time'] = sprintf("%s - %s", $dat_1->format("H:i"), $dat_2->format("H:i"));
            if ($item['time_1'] == '00:00:00' && $item['time_2'] == '23:59:59') $arr['time'] = JText::sprintf('MOD_DESCS_TIME_MASK_EVERYDAY');
            if ($directionID > 0) $arr['station'] = $item['station'];
            $arr['no_desc'] = ($item['time_1'] === null && $item['time_2'] === null && $item['tppd'] == '0') ? true : false;
            switch ($item['tppd']) {
                case '0': {
                    $arr['tppd'] = false;
                    break;
                }
                case '1': {
                    $arr['tppd'] = true;
                    break;
                }
                default: {
                    $arr['tppd'] = null;
                }
            }
            $arr['now'] = (($item['time_1'] !== null && $item['time_2'] !== null && $arr['tppd'] === false)) ? self::isWorkedNow($item['time_mask'], $dat_1, $dat_2) : false;
            $result[] = $arr;
        }
        return $result ?? array();
    }

    static function isWorkedNow(string $mask, JDate $time_1, JDate $time_2): bool
    {
        $result = false;
        $day = JDate::getInstance();
        $dayOfWeek = (int) $day->format("w");
        if (substr($mask, $dayOfWeek, 1) === '1') {
            $open = $time_1->getTimestamp();
            $close = $time_2->getTimestamp();
            $now = $day->getTimestamp();
            if ($open <= $now && $now <= $close) $result = true;
        }
        return $result;
    }
}