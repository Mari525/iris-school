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

class SefwizardViewSefwizard extends JViewLegacy
{
	protected function _setPath($type, $path)
	{
		$app = JFactory::getApplication();
		$component = 'com_sefwizard';

		$this->_path[$type] = array();
		$this->_addPath($type, $path);

		switch (strtolower($type))
		{
			case 'template':
				if (isset($app))
				{
					$component = preg_replace('/[^A-Z0-9_\.-]/i', '', $component);
					$fallback = JPATH_THEMES . '/' . $app->getTemplate() . '/html/' . $component . '/' . $this->getName();
					$this->_addPath('template', $fallback);
				}
				break;
		}
	}
	
	public function display($tpl = null)
	{
		$this->doc = JFactory::getDocument();
		$this->error = SefwizardController::getException();
		
		$code = $this->error->getCode();
		$message = $this->error->getMessage();
		
		if ($code < 400 || $code > 599)
		{
			$code = 500;
		}
		
		http_response_code($code);
		$this->doc->setTitle($code . ' ' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
		
		parent::display($tpl);
	}
}
