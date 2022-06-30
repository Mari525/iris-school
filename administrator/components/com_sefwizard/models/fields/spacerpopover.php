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
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('spacer');
 
class JFormFieldSpacerPopover extends JFormFieldSpacer
{
	protected $type = 'Spacerpopover';
	
	protected function getLabel()
	{
		$html = array();
		$class = !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$html[] = '<span class="spacer">';
		$html[] = '<span class="before"></span>';
		$html[] = '<span' . $class . '>';

		if ((string) $this->element['hr'] == 'true')
		{
			$html[] = '<hr' . $class . ' />';
		}
		else
		{
			$title = '';
			$label = '';

			$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
			$text = $this->translateLabel ? JText::_($text) : $text;
			
			$class = $this->required == true ? 'required' : '';

			if (!empty($this->description) && $text !== $this->description)
			{
				JHtml::_('bootstrap.popover');
				$class .= ' hasPopover';
				$title  = ' title="' . htmlspecialchars(trim($text, ':')) . '"'
					. ' data-content="'. htmlspecialchars(JText::_($this->description)) . '"';
				
				$position = 'top';
				
				if (!empty($this->position))
				{
					$position = $this->position;
				}
				else if(JFactory::getLanguage()->isRtl())
				{
					$position = 'left';
				}
				
				$title .= ' data-placement="' . $position . '" ';
				
			}
			
			$label .= '<label id="' . $this->id . '-lbl" class="' . $class . '"' . $title;
			$label .= '>' . $text . '</label>';
			$html[] = $label;
		}

		$html[] = '</span>';
		$html[] = '<span class="after"></span>';
		$html[] = '</span>';

		return implode('', $html);
	}
}