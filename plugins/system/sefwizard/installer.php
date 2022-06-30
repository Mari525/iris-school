<?php

defined('JPATH_PLATFORM') or die('Restricted Access');

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

class PlgSystemSefwizardInstallerScript
{
	public function preflight()
	{
		if(!class_exists('Pkg_SefwizardInstallerScript', false))
		{
			$app->enqueueMessage(JText::_('PLG_SYSTEM_SEFWIZARD_INTEGRITY_FAILURE'), 'error');
			$parent->getParent()->message = '';
			return false;
		}
	}
}
