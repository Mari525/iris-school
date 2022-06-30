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
 
jimport('joomla.form.formfield');
 
class JFormFieldCheckboxredirects extends JFormField {
 
	protected $type = 'checkboxredirects';
	private static $_cnt = 0;
	
	public function getLabel()
	{
		return '';
	}
 
	public function getInput()
	{
		$title = '';
		$text = JText::_($this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name']);
		$desc = htmlspecialchars(JText::_($this->description), ENT_COMPAT, 'UTF-8');
		$checked = $this->value ? (int) $this->value : '';
		
		if($first = in_array(++self::$_cnt, array(5, 6, 7, 8)) ? ' first-rule' : '')
		{
			$text .= '<b class="marked-info"><sup>?</sup></b>';
			$title = ' data-content="' . $desc . '"';
		}
		
		$html = '<input name="' . $this->name . '" type="hidden" value="' . $checked . '" /><label class="hasPopover' . $first . '" data-placement="bottom"' . $title . '><input type="checkbox" ' . ($checked ? 'checked="checked"' : '') . ' />' . $text . '</label>';
		
		return $html;
	}
}