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

class SefwizardViewRobots extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->form = $this->get('Form');
		$this->contents = $this->get('Contents');
		$this->title = JText::_('COM_SEFWIZARD_ROBOTS_EDITOR');
		
		$this->addToolbar('robots');
		$this->addSubmenu('robots');
		
		parent::display($tpl);
	}
	
	protected function addToolBar()
	{
		JToolBarHelper::apply('saveRobots');
		
		if(JFactory::getUser()->authorise('core.admin', 'com_sefwizard'))
		{
			JToolbarHelper::preferences('com_sefwizard');
		}
		
		SefwizardHelper::addChangeLogButton();
		SefwizardHelper::addHelpButton('robots-editor');
		
		JToolBarHelper::title($this->title, 'sefwizard-robots icon-sefwizard');
	}
	
	protected function addSubmenu($file)
	{
		$this->sidebar = SefwizardHelper::addSubmenu('robots', 'view=robots');
	}
	
}
