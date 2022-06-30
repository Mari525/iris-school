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

class SefwizardModelRobots extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{
		$jinput = JFactory::getApplication()->input;

		$form = $this->loadForm('com_sefwizard.sefwizard', 'robots');		
		return $form;
	}
	
	public function getContents()
	{
		if(file_exists($path = JPATH_ROOT . DIRECTORY_SEPARATOR . 'robots.txt'))
		{
			return file_get_contents($path);
		}
		else
		{
			JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_SEFWIZARD_FILE_NOT_FOUND', JUri::root() . 'robots.txt'), 'notice');
			return '';
		}
	}
}
