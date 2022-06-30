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

class SefwizardController extends JControllerLegacy
{
	protected static $error = null;
	protected static $instance = null;
	
	public static function getException()
	{
		return self::$error;
	}
	
	public static function getController($error = null)
	{
		if (!self::$instance)
		{
			parent::$instance = null;
			parent::$views = null;
			
			self::$error = $error;
			self::$instance = JControllerLegacy::getInstance('Sefwizard', ['base_path' => JPATH_SITE . '/components/com_sefwizard']);
		}
		
		return self::$instance;
	}
}
