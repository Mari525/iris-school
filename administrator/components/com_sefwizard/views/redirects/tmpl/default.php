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

<form class="sefwizard-redirects" action="index.php?option=com_sefwizard&view=redirects" method="post" id="adminForm" name="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<h1><?php echo JText::_('COM_SEFWIZARD_REDIRECT_MANAGER'); ?></h1>
		<div id="redirect-rules">
			<?php foreach ($this->data as $key => $rule) : ?>
				<?php if($rule === null) echo '<script type="text/template">'; ?>
				<div class="redirect-rule clearfix">
					<div class="redirect-cell redirect-source">
						<?php echo $this->getPair('source', $key); ?>
						<div class="redirect-inline">
							<?php echo $this->getPair('regex', $key); ?>
							<?php echo $this->getPair('cs', $key); ?>
						</div>
					</div>
					<div class="redirect-cell redirect-destination">
						<?php echo $this->getPair('destination', $key); ?>
						<div class="redirect-inline">
							<?php echo $this->getPair('get', $key); ?>
							<?php echo $this->getPair('internal', $key); ?>
						</div>
					</div>
					<div class="redirect-cell redirect-code">
						<?php echo $this->getPair('code', $key); ?>
					</div>
					<div class="redirect-cell redirect-remove noselect" title="<?php echo JText::_('COM_SEFWIZARD_REDIRECT_MANAGER_REMOVE_RULE'); ?>"><span>&times;</span></div>
				</div>
				<?php if($rule === null) echo '</script>'; ?>
			<?php endforeach; ?>
		</div>
		<div class="redirect-add-rule noselect">
			<button class="redirect-add" type="button"><?php echo JText::_('COM_SEFWIZARD_REDIRECT_MANAGER_ADD_RULE'); ?></button>
		</div>
		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
