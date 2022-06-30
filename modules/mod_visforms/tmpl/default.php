<?php
/**
 * Mod_Visforms Form
 *
 * @author       Aicha Vack
 * @package      Joomla.Site
 * @subpackage   mod_visforms
 * @link         http://www.vi-solutions.de 
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2012 vi-solutions
 * @since        Joomla 1.6 
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
	
if ($visforms->published != '1') {
    return;
    
}

//retrieve helper variables from params
$nbFields=$params->get('nbFields');
$required = $params->get('required');
$upload = $params->get('upload');
$textareaRequired = $params->get('textareaRequired');
$hasHTMLEditor = $params->get('hasHTMLEditor');
$return = JHtmlVisforms::base64_url_encode(JUri::getInstance()->toString());
$firstControl = $params->get('firstControl');
$setFocus = $params->get('setFocus');
$steps = $params->get('steps');
$context = $params->get('context');
$successMessage = $params->get('successMessage');
echo JLayoutHelper::render('visforms.custom.noscript', array(), null, array('component' => 'com_visforms'));
?>

<div class="visforms visforms-form"><?php
    if (isset($visforms->errors) && is_array($visforms->errors) && count($visforms->errors) > 0) {
	    echo JLayoutHelper::render('visforms.error.messageblock', array('errormessages' => $visforms->errors, 'context' => 'form'), null, array('component' => 'com_visforms'));
    }

    if ($menu_params->get('show_title') == 1) {?>
		<h1><?php echo $visforms->title; ?></h1><?php
	}

	echo JLayoutHelper::render('visforms.success.messageblock', array('message' => $successMessage, 'parentFormId' => $visforms->parentFormId), null, array('component' => 'com_visforms'));?>

    <div class="alert alert-danger error-note" style="display: none;"></div><?php
	echo JLayoutHelper::render('visforms.scripts.validation', array('visforms' => $visforms, 'textareaRequired' => $textareaRequired, 'hasHTMLEditor' => $hasHTMLEditor, 'parentFormId' => $visforms->parentFormId, 'steps' => $steps), null, array('component' => 'com_visforms'));
    if (strcmp ( $visforms->description , "" ) != 0) { ?>
        <div class="category-desc"><?php
            JPluginHelper::importPlugin('content');
            echo JHtml::_('content.prepare', $visforms->description); ?>
        </div><?php
    }

    //display form with appropriate layout
    switch($visforms->formlayout) {
        case 'btdefault' :
        case 'bthorizontal' :
        case 'bt3default' :
        case 'bt3horizontal' :
            require JModuleHelper::getLayoutPath('mod_visforms', $params->get('layout', 'default') . '_btdefault');
            break;
        case  'mcindividual' :
        case  'bt3mcindividual' :
            require JModuleHelper::getLayoutPath('mod_visforms', $params->get('layout', 'default') . '_mcindividual');
            break;
		case  'bt4mcindividual' :
            require JModuleHelper::getLayoutPath('mod_visforms', $params->get('layout', 'default') . '_bt4mcindividual');
            break;
        default :
            require JModuleHelper::getLayoutPath('mod_visforms', $params->get('layout', 'default') . '_visforms');
            break;
    }

    if ($visforms->poweredby == '1') {
        echo JHtml::_('visforms.creditsFrontend');
    }
    if (!empty($visforms->showmessageformprocessing)) { ?>
        <div id="<?php echo $visforms->parentFormId; ?>_processform" style="display:none"><div class="processformmessage"><?php
                echo $visforms->formprocessingmessage; ?>
            </div></div><?php
    }
    echo JLayoutHelper::render('visforms.scripts.map', array('form' => $visforms), null, array('component' => 'com_visforms')); ?>
</div>
