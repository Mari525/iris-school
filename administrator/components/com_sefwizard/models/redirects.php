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

class SefwizardModelRedirects extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_sefwizard.sefwizard', 'redirects');
		return $form;
	}
	
	public function getData()
	{
		$data = array(null);
		
		$dbo = JFactory::getDbo();
		$dbo->setQuery($dbo->getQuery(true)
			->select('*')
			->from($dbo->quoteName('#__sefwizard_redirects'))
			->order($dbo->quoteName('id'))
		);
		
		if($result = $dbo->loadObjectList())
		{
			$data = array_merge($data, $result);
		}
		else
		{
			array_push($data, false);
		}
		
		return $data;
		
	}
	
}
