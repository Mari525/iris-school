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

class SefwizardModelSefwizard extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_sefwizard.sefwizard', 'params');
		
		if($loadData)
		{
			$params = SefwizardHelper::getParams(true);
			
			foreach($params as $key => $param)
			{
				$form->setValue($key, 'params', $param);
			}
		}
		
		return $form;
	}
	
}
