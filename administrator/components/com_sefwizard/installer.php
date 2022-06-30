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

class com_sefwizardInstallerScript
{
	public function preflight($type, &$parent)
	{
		if(!class_exists('Pkg_SefwizardInstallerScript', false))
		{
			$app->enqueueMessage(JText::_('COM_SEFWIZARD_INTEGRITY_FAILURE'), 'error');
			$parent->getParent()->message = '';
			return false;
		}
		
		$dbo = JFactory::getDbo();
		$dbo->getImporter()->from(file_get_contents(__DIR__ . '/table_structure.xml'))->asXml()->mergeStructure();
	}
	
	public function postflight()
	{
		$dbo = JFactory::getDbo();
		$dbo->setQuery($dbo->getQuery(true)
			->delete('#__menu')
			->where($dbo->qn('title') . ' = ' . $dbo->q('COM_SEFWIZARD_SITEMAP_EDITOR'))
		);
		$dbo->execute();
		$dbo->setQuery($dbo->getQuery(true)
			->update('#__menu')
			->set($dbo->qn('link') . ' = ' . $dbo->q('index.php?option=com_sefwizard&view=robots'))
			->where($dbo->qn('title') . ' = ' . $dbo->q('COM_SEFWIZARD_ROBOTS_EDITOR'))
		);
		$dbo->execute();
	}
	
	public function uninstall($parent) 
	{
		JFactory::getDbo()->dropTable('#__sefwizard_redirects');
	}
}
