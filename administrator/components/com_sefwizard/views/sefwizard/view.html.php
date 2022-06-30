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

class SefwizardViewSefwizard extends JViewLegacy
{
	public function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();
		
		$layout = JFactory::getApplication()->input->get('layout', 'default');
		
		$this->addToolbar($layout);
		$this->addSubmenu($layout);
		
		if($layout === 'default')
		{
			JFactory::getDocument()->addStyleDeclaration('#system-message-container > .alert {margin-bottom: 5px}');
		}
		else
		{
			$doc->addScriptDeclaration("
				(function(w,d) {
					if (location.hash) {
						d.addEventListener('DOMContentLoaded', function() {
							var active = d.querySelector('a[href$=\"' + location.hash + '\"]');
							active && setTimeout(function() {active.click()}, 100);
						});
					}
				})(window, document);
			");
			
			$this->form = $this->get('Form');
		}
		
		parent::display($tpl);
		
	}
	
	protected function addToolbar($layout)
	{
		if($layout === 'settings')
		{
			$const = 'SEF_SETTINGS';
			JToolBarHelper::apply('saveParams');
		}
		else
		{
			$const = 'COMPONENT';
		}
		
		if(JFactory::getUser()->authorise('core.admin', 'com_sefwizard'))
		{
			JToolbarHelper::preferences('com_sefwizard');
		}
		
		SefwizardHelper::addChangeLogButton();
		SefwizardHelper::addHelpButton($layout === 'default' ? '' : $layout);
		
		JToolBarHelper::title(JText::_('COM_SEFWIZARD_' . $const), 'sefwizard-' . $layout . ' icon-sefwizard');
	}
	
	protected function addSubmenu($layout)
	{
		$this->sidebar = SefwizardHelper::addSubmenu($layout, 'layout=');
	}
	
	protected function renderFields()
	{
		$tabs = array(
			'remove_id' => 'COM_SEFWIZARD_ID_TAB',
			'duplicate_handling' => 'COM_SEFWIZARD_DUPLICATE_TAB',
			'com_content_sef' => 'COM_SEFWIZARD_CONTENT_TAB',
			'common_sef' => 'COM_SEFWIZARD_COMMON_SEF_TAB',
			'engine_settings' => 'COM_SEFWIZARD_ENGINE_TAB'
		);
		
		$doc = JFactory::getDocument();
		
		JHtml::_('behavior.formvalidation');
		JHtml::_('formbehavior.chosen', 'select');
		
		$doc->addScriptVersion(JUri::root(true) . '/media/system/js/polyfill.xpath.js');
		$doc->addScriptVersion(JUri::root(true) . '/media/system/js/tabs-state.js');
		
		$html = JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'duplicate_handling'));
		
		foreach($tabs as $key => $tab)
		{
			$html .= JHtml::_('bootstrap.addTab', 'myTab', $key, JText::_($tab, true));
			$html .= $this->form->renderFieldset($key);
			$html .= JHtml::_('bootstrap.endTab');
		}
		
		return $html . JHtml::_('bootstrap.endTabSet');
	}
}
