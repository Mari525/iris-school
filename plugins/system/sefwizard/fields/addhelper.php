<?php

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

defined('JPATH_PLATFORM') or die;

class JFormFieldAddhelper extends JFormField
{	
	protected $type = 'addhelper';
	
	protected function getInput()
	{
		$app = JFactory::getApplication();
		
		$legacy = version_compare(JVERSION, '3.0', '<');
		
		if($legacy || $app->getTemplate() === 'hathor')
		{
			JFactory::getDocument()->addStyleDeclaration('div.options-section {display: none} div.main-section {width: 100% !important}');
		}
		
		if((!$legacy && !JComponentHelper::isInstalled('com_sefwizard'))
			|| ($legacy && !file_exists(JPATH_ADMINISTRATOR . DS . "components" . DS . 'com_sefwizard' . DS . 'sefwizard.php')))
		{
			$app->enqueueMessage(JText::_('PLG_SYSTEM_SEFWIZARD_CONTROLS_NOT_INSTALLED'), 'warning');
		}
		else if(!JComponentHelper::isEnabled('com_sefwizard'))
		{
			$app->enqueueMessage(JText::_('PLG_SYSTEM_SEFWIZARD_CONTROLS_NOT_ENABLED'), 'warning');
		}
		
		return '';
		
	}
	
}
