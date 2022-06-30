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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class Pkg_SefwizardInstallerScript
{
	public function preflight($type, &$parent)
	{
		$app = JFactory::getApplication();
		
		$minJVersion = '3.7.1';
		$minPHPVersion = '5.4';
		
		if(version_compare(JVERSION, $minJVersion, '<'))
		{
			$error = JText::sprintf('PKG_SEFWIZARD_JOOMLA_VERSION_CHECK_FAILURE', $minJVersion);
		}
		
		if(version_compare(PHP_VERSION, $minPHPVersion, '<'))
		{
			if (isset($error)) {
				$error .= '<br/>';
			}
			$error .= JText::sprintf('PKG_SEFWIZARD_PHP_VERSION_CHECK_FAILURE', $minPHPVersion);
		}
		
		if(isset($error))
		{
			$app->enqueueMessage($error, 'error');
			$parent->getParent()->message = '';
			return false;
		}
		
		$manifest = $parent->get('manifest');
		$update_server = (string) $manifest->updateservers->server;
		
		if($update_server && preg_match('#^((?:https?:)?//[^/]+/[^\?]+?\.\w+)(?:\?(.*))?#i', $update_server, $server))
		{
			$update_server_ref = $update_server;
			$ref = '_' . urlencode(JUri::getInstance()->getHost());
			
			if(!empty($server[2]) && strpos($update_server_ref, $ref) === false)
			{
				$update_server_ref .= $ref;
			}
			
			if(strcasecmp($update_server, $update_server_ref))
			{
				$manifest->updateservers->server = $update_server_ref;
				JFile::write(__DIR__ . '/pkg_sefwizard.xml', $manifest->asXML());
			}
		
			$dbo = JFactory::getDbo();
			
			$dbo->setQuery($dbo->getQuery(true)
				->SELECT('update_site_id')
				->FROM($dbo->quoteName('#__update_sites'))
				->WHERE($dbo->quoteName('location') . ' LIKE ' . $dbo->quote($server[1] . '%'))
				->WHERE($dbo->quoteName('location') . ' <> ' . $dbo->quote($update_server_ref))
			);
			
			if($update_ids = $dbo->loadObjectList())
			{
				foreach($update_ids as &$element)
				{
					$element = (int) $element->update_site_id;
				}
				
				$update_ids = implode(',', $update_ids);
				
				$dbo->setQuery($dbo->getQuery(true)
					->DELETE($dbo->quoteName('#__update_sites'))
					->WHERE($dbo->quoteName('update_site_id') . ' IN (' . $update_ids . ')')
				);
				$dbo->execute();
				
				$dbo->setQuery($dbo->getQuery(true)
					->DELETE($dbo->quoteName('#__update_sites_extensions'))
					->WHERE($dbo->quoteName('update_site_id') . ' IN (' . $update_ids . ')')
				);
				$dbo->execute();
				
				$dbo->setQuery($dbo->getQuery(true)
					->DELETE($dbo->quoteName('#__updates'))
					->WHERE($dbo->quoteName('update_site_id') . ' IN (' . $update_ids . ')')
				);
				$dbo->execute();
			}
		}
		
		if ($type === 'update')
		{
			$parent->getParent()->message = '';
		}
	}
	
	public function postflight($type, &$parent)
	{
		$dbo = JFactory::getDbo();
		
		if(in_array($type, ['install', 'discover_install']))
		{
			$dbo->setQuery($dbo->getQuery(true)
				->update($dbo->quoteName('#__extensions'))
				->set($dbo->quoteName('enabled') . ' = 1')
				->where($dbo->quoteName('name') . ' = ' . $dbo->quote('plg_system_sefwizard'))
			);
			$dbo->execute();
		}
	}
	
}
