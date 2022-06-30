<?php

defined('_JEXEC') or die('Restricted Access');

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

require_once JPATH_COMPONENT . '/helpers/sefwizard.php';

if(!JFactory::getUser()->authorise('core.manage', 'com_sefwizard'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

$controller = JControllerLegacy::getInstance('Sefwizard');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
