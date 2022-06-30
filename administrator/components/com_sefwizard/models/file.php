<?php

defined('_JEXEC') or die('Restricted Access');

/* SEF Wizard extension for Joomla 3.x - Version 3.7.3
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

class SefwizardModelFile extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{
		$jinput = JFactory::getApplication()->input;

		$form = $this->loadForm('com_sefwizard.sefwizard', 'file');		
		return $form;
	}
	
	protected function getFile($file)
	{	
		if(file_exists($path = JPATH_ROOT . DIRECTORY_SEPARATOR . $file))
		{
			return file_get_contents($path);
		}
		else
		{
			JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_SEFWIZARD_FILE_NOT_FOUND', JUri::root() . $file), 'notice');
			return '';
		}
	}
	
	public function getRobots()
	{
		return $this->getFile('robots.txt');
	}
	
	public function getSitemap()
	{
		return $this->getFile('sitemap.xml');
	}
	
}
