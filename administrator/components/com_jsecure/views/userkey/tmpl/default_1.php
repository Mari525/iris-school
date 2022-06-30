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
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('behavior.framework', true);
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
$document = JFactory::getDocument();
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/userkey.js"></script>');
$document->addScriptDeclaration("window.addEvent('domready', function() {
		$$('.hasTip').each(function(el) {
			var title = el.get('title');
			if (title) {
				var parts = title.split('::', 2);
				el.store('tip:title', parts[0]);
				el.store('tip:text', parts[1]);
			}
		});
		var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
	});
");
?>

<?php
$JSecureConfig = new JSecureConfig();
$controller = new jsecureControllerjsecure();
$app        = JFactory::getApplication();
if (!$controller->isMasterLogged() and $JSecureConfig->enableMasterPassword == '1'and $JSecureConfig->include_user_key == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else {
?>

<?php if($this->viewList == '' && $this->search != ''){ ?>
	
	
		
		<p id="system-message1"class="alert alert-error"><?php echo "No records were found matching your search criteria!"; ?></p>
		
<?php	} ?>
	
	


<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure&task=userkey" method="post" name="adminForm" id= "adminForm" class="span10">
<h3><?php echo JText::_('USERKEY_HEADING');?></h3>
<table width="100%">
<tr>
	<td >
		<div id="filter-bar" class="btn-toolbar">
<div class="filter-search btn-group pull-left">
<input name="search" id="search" type="text" title="Search" value="<?php echo $this->search; ?>" placeholder="<?php echo JText::_('FILTER_USERKEY'); ?>" name="search" onchange="document.adminForm.submit();">
					</div>
					<div class="btn-group pull-left">
					<button class="btn tip" rel="tooltip" type="submit" data-original-title="Search" onclick="this.form.submit();">
					<i class="icon-search"></i>
					 </button>
					<button class="btn tip" rel="tooltip" onclick="document.id('search').value='';this.form.submit();" type="button" data-original-title="Clear">
					<i class="icon-remove"></i>
					</button>
					</div>
					<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
			<?php if(empty($this->search)) { echo $this->pagination->getLimitBox(); } 
				  if(!empty($this->search) && ctype_space($this->search)) { echo $this->pagination->getLimitBox(); }
			?>
		</div>
	</div>
	</td>
</tr>
</table>

<table class="table table-striped">
<thead>
	<tr>
		<th width="2%">
			<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
		</th>
		<th class="title">
			<?php echo JText::_( 'User Name' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'Key Status' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'Start Date' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'End Date' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'User Group' ); ?>
		</th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="13" align="center">
		<?php if(empty($this->search) ) { echo $this->pagination->getListFooter(); }
			  if(!empty($this->search) && ctype_space($this->search)) { echo $this->pagination->getListFooter(); }
		?>
		</td>
	</tr>
</tfoot>
<tbody>

	<?php for($i=0;$i<count($this->viewList);$i++){  ?>
		<tr>
		<td align="left">
		<?php echo JHTML::_('grid.id',   $i, $this->viewList[$i]->id ); ?>
		</td>
		<td align="left">
		<span class="bold hasTip" title="<?php echo JText::_('Edit User')."::".JText::_('Click here to edit the userkey details for this user');?>">
			<a class="modal" href="index.php?option=com_jsecure&task=editkey&id=<?php echo $this->viewList[$i]->id; ?>">  
    			<?php echo $this->userNames[$i]->username; ?>
   			</a>
		</span>	
		</td>
		<td align="left">
		<?php 
				if($this->viewList[$i]->status == 1){
					$title = JText::_( 'UNPUBLISH_ITEM' );
					$iconclass = '<span class="icon-publish"></span>';
					$linkclass = "btn btn-micro active";
				} else if($this->viewList[$i]->status == 0){
					
					$title = JText::_( 'PUBLISH_ITEM' );
					$iconclass = '<span class="icon-unpublish"></span>';
					$linkclass = "btn btn-micro ";
				}
		?>			
				<span class="editlinktip hasTip" title="<?php echo $title; ?>">
					<a class="<?php echo $linkclass; ?>" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $this->viewList[$i]->status ? 'unpublishkey' : 'publishkey' ?>')">
						<?php echo $iconclass;?>
					</a>
				</span>
		</td>
		<td align="left">
			<?php
			if($this->viewList[$i]->end_date >= strtotime("today")) {
			echo "<font color=green>".date("d-M-Y",$this->viewList[$i]->start_date) ."</font>"; 
			}
			else {
			echo "<font color=red>".date("d-M-Y",$this->viewList[$i]->start_date) ."</font>";
			}
			?>
		</td>
		<td align="left">
			<?php 
			if($this->viewList[$i]->end_date >= strtotime("today")) {
			echo "<font color=green>".date("d-M-Y",$this->viewList[$i]->end_date) ."</font>"; 
			}
			else {
			echo "<font color=red>".date("d-M-Y",$this->viewList[$i]->end_date) ."</font>";
			}
			?>
		</td>
		<td align="left">
			<?php echo $this->groupNames[$i]; ?>
		</td>
		</tr>
<?php } ?>
</tbody>
</table>

<input type="hidden" name="option" value="com_jsecure" />
<input type="hidden" name="task" value="userkey"/>
<input type="hidden" name="boxchecked" value="0" />
</form>
<?php
}
?>
	