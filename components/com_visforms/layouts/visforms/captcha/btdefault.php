<?php
/**
 * Visforms captcha html for bootstrap default layout
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
require_once JPATH_ADMINISTRATOR.'/components/com_visforms/helpers/html/visforms.php';

if (!empty($displayData)) : 
    if (isset($displayData['form'])) :
        $form  = $displayData['form'];
        $clear = (!empty($displayData['clear'])) ? true : false;
	    $context = (isset($form->context)) ? $form->context : '';
	    $name = $context . 'viscaptcha_response';
	    $errorDivClass = (isset($form->captcha) && $form->captcha == 2) ? 'fc-tbxrecaptcha_response_field' : 'fc-tbx'.$name.'_field';
        $html = array();
        if (isset($form->captcha))
        {
            //Create a div with the right class where we can put the validation errors into
            $html[] = '<div class="'.$errorDivClass.'"></div>';
            //showcaptchalabe == 0: show label!
            $html[] = (!(isset($form->showcaptchalabel)) || ($form->showcaptchalabel == 0)) ? '<div class="required asterix-ancor">' : '<div class="required">';
            $html[] = (!(isset($form->showcaptchalabel)) || ($form->showcaptchalabel == 0)) ? '<label id="captcha-lbl" for="recaptcha_response_field">' . JHtmlVisforms::createCaptchaTip($form) . '</label>' : '<span class ="asterix-ancor"></span>';
            if ($clear)
            {
                $html[] = '<div class="clr"> </div>';
            }
            switch ($form->captcha)
            {
                case 1 :
                    $html[] = '<img id="captchacode' . $form->id . '" class="captchacode" src="index.php?option=com_visforms&task=visforms.captcha&sid=c4ce9d9bffcf8ba3357da92fd49c2457&id=' . $form->id . '" align="absmiddle"> &nbsp; ';

                    $html[] = '<img alt="' . JText::_('COM_VISFORMS_REFRESH_CAPTCHA') . '" class="captcharefresh' . $form->id . '" src="' . JURI::root(true) . '/components/com_visforms/captcha/images/refresh.gif' . '" align="absmiddle"> &nbsp;';
	                $html[] = '<input class="visCSStop10'.(!empty($form->preventsubmitonenter) ? " noEnterSubmit": "").'" type="text" id="'.$name.'" name="'.$name.'" data-error-container-id="'.$errorDivClass.'" required="required" />';
                    break;
                case 2:
                    $captcha = JCaptcha::getInstance('recaptcha');
                    $html[] = $captcha->display(null, 'dynamic_recaptcha_1', 'required');
                    break;
                default :
                    return '';
            }
            $html[] = '</div>';
        }
        echo implode('', $html);
    endif;  
endif; ?>

        