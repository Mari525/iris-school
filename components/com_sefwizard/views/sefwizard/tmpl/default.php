<?php

defined('_JEXEC') or die;

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

$root = JURI::root(true);

$this->doc->addStyleDeclaration('
	
	@font-face {
		font-family: "IcoMoon";
		src: url("'. $root. '/media/jui/fonts/IcoMoon.eot");
		src: url("'. $root. '/media/jui/fonts/IcoMoon.eot?#iefix") format("embedded-opentype"), 
			url("'. $root. '/media/jui/fonts/IcoMoon.woff") format("woff"), 
			url("'. $root. '/media/jui/fonts/IcoMoon.ttf") format("truetype"), 
			url("'. $root. '/media/jui/fonts/IcoMoon.svg#IcoMoon") format("svg");
		font-weight: normal;
		font-style: normal;
	}
	
');

$this->doc->addStyleSheetVersion('components/com_sefwizard/assets/css/error-page.css');

?>

<div class="error-page-container item-page">
	<div class="error-page-header">
		<h1><?php echo JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
	</div>
	<div class="error-page-body">
		<p><?php echo 
			JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST') . " " .
			JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
		<ul class="error-page-list">
			<li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
			<li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
			<li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
			<li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
		</ul>
		<?php if (JModuleHelper::getModule('search')) : ?>
			<p><b><?php echo JText::_('JERROR_LAYOUT_SEARCH'); ?></b></p>
			<p><?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?></p>
			<?php echo $this->doc->getBuffer('module', 'search'); ?>
		<?php endif; ?>
		<p><a href="<?php echo JUri::root(true) ?>/" class="error-page-btn"><i class="error-page-icon-home"></i> <?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></a></p>
		<p class="error-page-contact"><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
		<p class="error-page-message">
			<b><?php echo $this->error->getCode(); ?></b> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8');?>
		</p>
	</div>
</div>
