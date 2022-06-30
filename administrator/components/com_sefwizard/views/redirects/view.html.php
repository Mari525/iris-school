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

class SefwizardViewRedirects extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->form = $this->get('Form');
		$this->data = $this->get('Data');
		
		$this->addToolbar();
		$this->addSubmenu();
		
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		
		JHtml::_('bootstrap.framework');
		$doc->addScriptVersion(JURI::base(true) . '/components/com_sefwizard/assets/js/redirects.js');
		
		$doc->addScriptDeclaration('
			jQuery(document).ready(function($){
				$(".hasTooltip").tooltip({"html": true,"container": "body"});
				$(".hasPopover").popover({"html": true,"trigger": "hover focus","container": "body"});
			});
		');
		
		if(!SefwizardHelper::isRedirectManagerEnabled())
		{
			$app->enqueueMessage(JText::_('COM_SEFWIZARD_REDIRECT_MANAGER_NOT_ENABLED'), 'notice');
		}
		
		parent::display($tpl);
	}
	
	public function getPair($name, $key)
	{
		$fieldName = $name . '[]';
		
		if(isset($this->data[$key]))
		{
			if($this->data[$key] === false)
			{
				$value = $name === 'source' ? 'http://example.com/source' : ($name === 'destination' ? 'destination' : '');
			}
			else
			{
				$value = $this->data[$key]->$name;
			
				if($name === 'source' && empty($this->data[$key]->regex) && !empty($this->data[$key]->query))
				{
					$value .= '?' . $this->data[$key]->query;
				}
			}
			
			$this->form->setValue($fieldName, null, $value);
		}
		
		return $this->form->getInput($fieldName);
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::apply('saveRedirects');
		
		if(JFactory::getUser()->authorise('core.admin', 'com_sefwizard'))
		{
			JToolbarHelper::preferences('com_sefwizard');
		}
		
		SefwizardHelper::addChangeLogButton();
		SefwizardHelper::addHelpButton('redirects');
		
		JToolBarHelper::title(JText::_('COM_SEFWIZARD_REDIRECT_MANAGER'), 'sefwizard-redirects icon-sefwizard');
	}
	
	protected function addSubmenu()
	{
		$this->sidebar = SefwizardHelper::addSubmenu('redirects', 'view=');
	}
	
}
