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

?>

<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>

<div id="j-main-container" class="span10">
	<h1><?php echo JText::_('COM_SEFWIZARD_INFO'); ?></h1>
	<?php echo JText::_('COM_SEFWIZARD_DESCRIPTION'); ?>
	<h2><?php echo JText::_('COM_SEFWIZARD_INTEGRITY'); ?></h2>
	<?php echo JText::_('COM_SEFWIZARD_INTEGRITY_TEXT'); ?>
	<h2><?php echo JText::_('COM_SEFWIZARD_HIRE_US'); ?></h2>
	<?php echo JText::_('COM_SEFWIZARD_HIRE_US_TEXT'); ?>
	<h2><?php echo JText::_('COM_SEFWIZARD_REVIEW'); ?></h2>
	<?php echo JText::_('COM_SEFWIZARD_REVIEW_TEXT'); ?>
</div>
