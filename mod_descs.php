<?php
use Joomla\CMS\Helper\ModuleHelper;

defined('_JEXEC') or die;
require_once 'helper.php';

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$input = JFactory::getApplication()->input;
$view = $input->getString('view', null);
$id = $input->getInt('id', 0);

require ModuleHelper::getLayoutPath('mod_descs', $view);
