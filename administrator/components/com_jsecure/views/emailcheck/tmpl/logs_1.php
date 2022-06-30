<?php

/**
 * jSecure Authentication components for Joomla!
 * jSecure Authentication extention prevents access to administration (back end)
 * login page without appropriate access key.
 *
 * @author      $Author: Ajay Lulia $
 * @copyright   Joomla Service Provider - 2016
 * @package     jSecure3.5
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id: default.php  $
 */
	
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('behavior.framework', true);
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
// $app        = JFactory::getApplication();
$document = JFactory::getDocument();


$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/emailcheck.js"></script>');


?>

<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" id= "adminForm" class="span10">
<h3><?php echo JText::_('SPAM_EMAIL_LOG');?></h3>
		
	<table width="100%">
	
	    <tr>
		
		  <!-- Pagination Header --> 
		
		   <div id="filter-bar" class="btn-toolbar">
          <div class="btn-group pull-right hidden-phone">
          <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
          <?php echo $this->pagination->getLimitBox(); ?>
           </div>
           <!-- Pagination Header --> 
		
		
	        <!-- Search Box Code --> 
		   <div class="filter-search btn-group pull-left">
           <input id="search" type="text" placeholder="Search Emails:" title="Search" value="<?php echo $this->search?>" placeholder="Filter:" name="search" on change="document.adminForm.submit();">
           </div>
           <div class="btn-group pull-left">
           <!--<button class="btn tip" rel="tooltip" type="submit" data-original-title="Search" onclick="this.form.submit();">-->
           <button class="btn tip" rel="tooltip" type="submit" data-original-title="Search"  >
           <i class="icon-search"></i>
           </button>
           <button class="btn tip" rel="tooltip" onclick="document.id('search').value='';this.form.submit();" type="button" data-original-title="Clear">
           <i class="icon-remove"></i>
           </button>
           </div>
		    <!-- Search Box Code --> 
		</tr>   
		   
	</table>   
		   

<table class="table table-striped">
<thead>
	<tr>
	<th>
	<?php echo JHtml::_('grid.checkall'); ?>
	</th>
	
	
	
		<th width="2%">
			<?php echo JText::_( 'Num' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'IP' ); ?>
		</th>
		<th class="title" nowrap="nowrap">
			<?php echo JText::_( 'User Name' ); ?>
		</th>
		<th class="title" nowrap="nowrap">
			<?php echo JText::_( 'Email' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'Date' ); ?>
		</th>
	</tr>
</thead>
<tbody>
	<?php 
	$i=0;$k = 1;
	foreach($this->result as $row){?>
	</tr>
	<!-- <td align="left"> <input type="checkbox" onclick="Joomla.isChecked(this.checked);" name="spamlog[]" value="<?php echo $row->id;?>" id="<?php echo $row->id;?>"></td>  -->
	<td align="left"><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
	
	<td align="left">
			<?php echo $this->pagination->getRowOffset( $i ); ?>
		</td>
		<td align="left">
			<?php echo $row->ip; ?>
		</td>
			
		<td align="left">
			<?php echo $row->username; ?>
		</td>
		<td align="left">
			<?php echo $row->email; ?>
		</td>
		<td align="left">
			<?php echo str_replace("\n","<br/>",$row->datetime); ?>
		</td>
		

	</tr>
	<?php
		$k = 1 - $k;	$i++;
	}
	?>
</tbody>
<tfoot>
   <tr>
       <td colspan="15">
           <?php echo $this->pagination->getListFooter(); ?>
       </td>
   </tr>
</tfoot>
</table>
<input type="hidden" name="option" value="com_jsecure" />
<input type="hidden" name="controller" value="" />
<input type="hidden" name="task" value="emaillog"/>
<input type="hidden" name="boxchecked" value="0"/>
</form>
















