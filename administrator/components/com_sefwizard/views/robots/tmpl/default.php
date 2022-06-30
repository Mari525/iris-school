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
<form action="index.php?option=com_sefwizard&view=robots" method="post" id="adminForm" name="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<h1><?php echo $this->title ?></h1>
		<?php echo $this->form->getInput('robots', null, $this->contents); ?>
		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>