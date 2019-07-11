<?php
defined('_JEXEC') or die;
$input = JFactory::getApplication()->input;
$id = $input->getInt('id', 0);
$descs = ModDescsHelper::getDescs(0, $id);
if (count($descs) === 1 && $descs[0]['tppd'] === true) {
    echo JText::sprintf('MOD_DESCS_HAVE_TPPD');
    return;
}
if (count($descs) === 1 && $descs[0]['no_desc'] === true) {
    echo JText::sprintf('MOD_DESCS_NO_DESC');
    return;
}
if (empty($descs)) {
    echo JText::sprintf('MOD_DESCS_TIME_NO_INFO');
    return;
}
?>
<table class="table table-sm">
    <thead>
        <tr>
            <th scope="col"><?php echo JText::sprintf('MOD_DESCS_HEAD_DAYS');?></th>
            <th scope="col"><?php echo JText::sprintf('MOD_DESCS_HEAD_HOURS');?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($descs as $desc): ?>
            <tr>
                <td<?php if ($desc['now']) echo " class='text-danger'";?>><?php echo $desc['time_mask'];?></td>
                <td<?php if ($desc['now']) echo " class='text-danger'";?>><?php echo $desc['time'];?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
