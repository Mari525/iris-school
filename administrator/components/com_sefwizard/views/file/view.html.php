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

class SefwizardViewFile extends JViewLegacy
{
	public function display($tpl = null)
	{
		$file = JFactory::getApplication()->input->get('file') === 'sitemap' ? 'sitemap' : 'robots';
		
		$this->form = $this->get('Form');
		$this->fileContents = $this->get(ucfirst($file));
		$this->title = JText::_('COM_SEFWIZARD_' . strtoupper($file) . '_EDITOR');
		
		$this->addToolbar($file);
		$this->addSubmenu($file);
		$this->fileName = $file;
		
		if(SefwizardHelper::$legacyStyle)
		{
			JHtml::_('behavior.framework');
		}
		
		parent::display($tpl);
	}
	
	protected function addToolBar($file)
	{
		JToolBarHelper::apply('file.update' . ucfirst($file));
		
		if(JFactory::getUser()->authorise('core.admin', 'com_sefwizard'))
		{
			JToolbarHelper::preferences('com_sefwizard');
		}
		
		SefwizardHelper::addChangeLogButton();
		SefwizardHelper::addHelpButton($file . '-editor');
		
		$iconClass = SefwizardHelper::$legacyStyle ? '' : 'icon-sefwizard';
		JToolBarHelper::title($this->title, 'sefwizard-' . $file . ' ' . $iconClass);
	}
	
	protected function addSubmenu($file)
	{
		$this->sidebar = SefwizardHelper::addSubmenu($file, 'view=file&file=');
	}
	
}
