<?php
/**
 * Visforms html for admincontrollblock
 *
 * @author       Aicha Vack
 * @package      Joomla.Site
 * @subpackage   com_visforms
 * @link         http://www.vi-solutions.de
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2012 vi-solutions
 * @since        Joomla 1.6
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
if (!empty($displayData)) :
	if (isset($displayData['form']) && isset($displayData['nbFields']) && isset($displayData['hasRequired'])) :
		$form = $displayData['form'];
		$nbFields = $displayData['nbFields'];
		$hasRequired = $displayData['hasRequired'];
		$summarypageid = $displayData['summarypageid'];
		$backButtonText = (!empty($form->backbtntext)) ? $form->backbtntext : JText::_('COM_VISFORMS_STEP_BACK');
		$summaryButtonText = (!empty($form->summarybtntext)) ? $form->summarybtntext : JText::_('COM_VISFORMS_SUMMARY');
		$correctButtonText = (!empty($form->correctbtntext)) ? $form->correctbtntext : JText::_('COM_VISFORMS_CORRECT');
		if (!empty($form->mpdisplaytype) && !empty($form->accordioncounter)) {
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		if ($form->formlayout == "bt3horizontal") {
			echo '<div class="form-group"><div class="col-sm-offset-3 col-sm-9">';
		}
		else {
			echo ' <div class="form-actions">';
		}
		if (!empty($form->steps) && (int) $form->steps > 1) {
			echo ' <input type="button" class="btn back_btn" value="' . $backButtonText . '" /> ';
		}
		echo '<input type="button" class="btn summary_btn" value="' . $summaryButtonText . '" /> ';
		echo '</div>';
		echo '</fieldset>';
		echo '<fieldset class="fieldset-summarypage">';
		if ((!empty($form->summarydescription)) && (!empty($form->summarydescriptionposition)) && ($form->summarydescriptionposition == 'top')) {
			echo '<div class="summarydesc">' . $form->summarydescription . '</div>';
		}
		echo '<div id="' . $summarypageid . '_summarypage"></div>';
		//Explantion for * if at least one field is requiered above captcha
		if ($hasRequired == true && $form->required == 'captcha') {
			echo JLayoutHelper::render('visforms.requiredtext.btdefault', array('form' => $form), null, array('component' => 'com_visforms'));
		}
		if (isset($form->captcha) && ($form->captcha == 1 || $form->captcha == 2)) {
			switch ($form->formlayout) {
				case "bthorizontal" :
					echo JLayoutHelper::render('visforms.captcha.bthorizontal', array('form' => $form), null, array('component' => 'com_visforms'));
					break;
				case "bt3default" :
					echo JLayoutHelper::render('visforms.captcha.bt3default', array('form' => $form), null, array('component' => 'com_visforms'));
					break;
				case "bt3horizontal" :
					echo JLayoutHelper::render('visforms.captcha.bt3horizontal', array('form' => $form), null, array('component' => 'com_visforms'));
					break;
				default :
					echo JLayoutHelper::render('visforms.captcha.btdefault', array('form' => $form), null, array('component' => 'com_visforms'));
					break;
			}
		}
		//Explantion for * if at least one field is requiered above submit
		if ($hasRequired == true && $form->required == 'bottom') {
			echo JLayoutHelper::render('visforms.requiredtext.btdefault', array('form' => $form), null, array('component' => 'com_visforms'));
		}
		if ($form->formlayout == "bt3horizontal") {
			echo '<div class="form-group"><div class="col-sm-offset-3 col-sm-9">';
		}
		else {
			echo ' <div class="form-actions">';
		}
		echo '<input type="button" class="btn correct_btn" value="' . $correctButtonText . '" /> ';
		for ($i = 0; $i < $nbFields; $i++) {
			$field = $form->fields[$i];
			if (isset($field->isButton) && $field->isButton === true) {
				echo $field->controlHtml;
			}
		}
		echo '</div>';
		if ((!empty($form->displaysummarypage)) && ((!empty($form->summarydescription)) && (!empty($form->summarydescriptionposition)) && ($form->summarydescriptionposition == 'bottom'))) {
			echo '<div class="summarydesc">' . $form->summarydescription . '</div>';
		}
	endif;
endif; ?>