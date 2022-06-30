<?php

defined('_JEXEC') or die;

/* SEF Wizard extension for Joomla 3.x
--------------------------------------------------------------
 Copyright (C) AddonDev. All rights reserved.
 Website: https://addondev.com
 GitHub: https://github.com/philip-sorokin
 Developer: Philip Sorokin
 Location: Russia, Moscow
 E-mail: philip.sorokin@gmail.com
 Created: January 2016
 License: GNU GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
--------------------------------------------------------------- */

require_once(__DIR__ . '/controller.php');

defined('JPATH_COMPONENT') or define('JPATH_COMPONENT', JPATH_BASE . '/components/com_sefwizard');
defined('JPATH_COMPONENT_SITE') or define('JPATH_COMPONENT_SITE', JPATH_SITE . '/components/com_sefwizard');
defined('JPATH_COMPONENT_ADMINISTRATOR') or define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/com_sefwizard');

if (!isset($error)) {
	$error = new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
}

$controller = SefwizardController::getController($error);
$controller->execute(JFactory::getApplication()->input->get('task', 'display'));
$controller->redirect();
