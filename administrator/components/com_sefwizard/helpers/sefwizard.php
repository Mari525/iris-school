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

class SefwizardHelper
{
	protected static $engine = null;
	
	protected static function getPrefix()
	{
		return JFactory::getLanguage()->getTag() === 'ru-RU' ? '' : '/en';
	}
	
	public static function addHelpButton($view = '')
	{
		$prefix = self::getPrefix();
		JToolbarHelper::help('help', false, "https://addondev.com$prefix/extensions/sefwizard/documentation" . ($view ? "#$view" : ''));
	}
	
	public static function addChangelogButton()
	{
		$prefix = self::getPrefix();
		
		$bar = JToolbar::getInstance();
		$bar->appendButton('Popup', 'link', JText::_('COM_SEFWIZARD_CHANGELOG'), "https://addondev.com$prefix/uncategorised/changelog?tmpl=component&notitle=1&extension=sefwizard");
	}
	
	public static function addSubmenu($vName, $query)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_SEFWIZARD_INFO'),
			'index.php?option=com_sefwizard&layout=default',
			$vName == 'default'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_SEFWIZARD_SEF_SETTINGS'),
			'index.php?option=com_sefwizard&layout=settings',
			$vName == 'settings'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_SEFWIZARD_ROBOTS_EDITOR'),
			'index.php?option=com_sefwizard&view=robots',
			$vName == 'robots'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_SEFWIZARD_REDIRECT_MANAGER'),
			'index.php?option=com_sefwizard&view=redirects',
			$vName == 'redirects'
		);
		
		JHtmlSidebar::setAction('index.php?option=com_sefwizard&' . $query . $vName);
		return JHtmlSidebar::render();
	}
	
	public static function isEngineInstalled()
	{
		return self::getEngine() !== false;
	}
	
	public static function isEngineEnabled()
	{
		if($engine = self::getEngine())
		{
			return $engine->enabled;
		}
	}
	
	public static function getParams($array = false)
	{
		if($engine = self::getEngine())
		{
			return $array ? json_decode($engine->params, true) : new JRegistry($engine->params);
		}
	}
	
	public static function isRedirectManagerEnabled()
	{
		if($params = self::getParams())
		{
			return $params->get('redirect_manager_enabled');
		}
	}
	
	public static function setParams(array $params)
	{
		if(self::isEngineInstalled())
		{
			$dbo = JFactory::getDbo();
			
			$dbo->setQuery($dbo->getQuery(true)
				->update($dbo->quoteName('#__extensions'))
				->set($dbo->quoteName('params') . ' = ' . $dbo->quote(json_encode($params)))
				->where($dbo->quoteName('name') . ' = ' . $dbo->quote('plg_system_sefwizard'))
			);
			
			return $dbo->execute();
		}
	}
	
	public static function enableEngine()
	{
		if(self::isEngineInstalled() && !self::isEngineEnabled())
		{
			$dbo = JFactory::getDbo();
			
			$dbo->setQuery($dbo->getQuery(true)
				->update($dbo->quoteName('#__extensions'))
				->set($dbo->quoteName('enabled') . ' = 1')
				->where($dbo->quoteName('name') . ' = ' . $dbo->quote('plg_system_sefwizard'))
			);
			
			return $dbo->execute();
		}
	}
	
	protected static function getEngine()
	{
		if(!isset(self::$engine))
		{
			$dbo = JFactory::getDbo();
		
			$dbo->setQuery($dbo->getQuery(true)
				->select('*')
				->from($dbo->quoteName('#__extensions'))
				->where($dbo->quoteName('name') . ' = ' . $dbo->quote('plg_system_sefwizard'))
			);
			
			$result = $dbo->loadObject();
			self::$engine = $result ? $result : false;
		}
		
		return self::$engine;
		
	}
	
}
