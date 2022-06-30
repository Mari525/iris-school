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
 
class JFormFieldTextredirects extends JFormField {
 
	protected $type = 'textredirects';
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
		
		if($first = in_array(++self::$_cnt, array(3, 4)) ? ' first-rule' : '')
		{
			$text .= '<b class="marked-info"><sup>?</sup></b>';
			$title = ' data-content="' . $desc . '"';
		}
		
		$number = is_int($number = (self::$_cnt - 1) / 2) ? "<span class='redirect-rule-num'>#<span class='redirect-rule-cnt'>$number</span></span>: " : '';
		
		$html = '<label class="hasPopover' . $first . '" data-placement="bottom"' . $title . '>' . $number . $text . '<input type="text" name="' . $this->name . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '" autocomplete="off" /></label>';
		
		return $html;
	}
}