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
$JSecureConfig = $this->JSecureConfig;


JHtml::_('behavior.framework', true);
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
JHtml::_('behavior.multiselect');


$app      = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/modern_jquery.mCustomScrollbar.css");
$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/styles.css");
/*$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/bootstrap.min.css");*/
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/jquery.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/countryblock.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>');
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
		window.addEvent('domready', function() {

			SqueezeBox.initialize({});
			SqueezeBox.assign($$('a.modal'), {
				parse: 'rel'
			});
		});
");
$controller = new jsecureControllerjsecure();

if (!$controller->isMasterLogged() and $JSecureConfig->enableMasterPassword == '1'and $JSecureConfig->include_email_scan == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
?>
<body>

<h3 style="margin-left:5px"><?php echo JText::_('COUNTRY_BLOCK');?></h3>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" id="adminForm" autocomplete="off">
   <!--  <fieldset class="adminform mCustomScrollbar" style="height:500px; overflow:hidden;">-->
      <div class="row-fluid" style="padding:5px 0 10px;">
       <div class="span6"> 
          <span class="bold hasTip" title="<?php echo JText::_('PUBLISHED_COUNTRY_BLOCK');?>"> <?php echo JText::_('ENABLE'); ?> </span> 
        </div>
        <div class="span6"> 		
		<fieldset id="jform_home" class="radio btn-group">
  			<input onchange="countryblock(this);" type="radio" name="publishcountryblock" value="1" <?php echo ($JSecureConfig->countryblock == 1)? 'checked="checked"':''; ?> id="publishcountryblock1" />
  			<label class="btn" for="publishcountryblock1">Yes</label>
  			<input onchange="countryblock(this);" type="radio" name="publishcountryblock" value="0" <?php echo ($JSecureConfig->countryblock == 0)?  'checked="checked"':''; ?> id="publishcountryblock0" />
  			<label class="btn" for="publishcountryblock0">No</label>
		</fieldset>
       </div> 
     </div>
	 
	 
	 
	 <div class="row-fluid" style="background-color:#f9f9f9">
     <div class="span12">
	 
	 <span class="bold hasTip" style="padding:5px 0;display: inline-block; padding-top: 5px;margin-right: 40px;" title="<?php echo JText::_('COUNTRY_LIST');?>"> <?php echo JText::_('COUNTRIES'); ?></span>
	 
	
	 <span class="editlinktip">
				<a class="btn btn-micro active" href="javascript:void(0);" onclick="">
					<i class="icon-publish"></i>
				</a>
	</span> - Unblock Country
	
	<span class="editlinktip" style="margin-left: 30px;">
				<a class="btn btn-micro active" href="javascript:void(0);" onclick="">
					<i class="icon-unpublish"></i>
				</a>
	</span> - Block Country
	 
	 
	 </div>
	  </div>
	 <div class="row-fluid">
	<div class="span12 countryblock">
	 <?php echo JHtml::_('grid.checkall'); ?>
	 </div>
	</div>
	 
	<div id="countrylist" class="row mCustomScrollbar" style="height:450px" >
	 <?php 
	 if($this->data != null){
	 
	$i=0;$k = 1;
	foreach($this->data as $row){
	
	if($i%2==0){
	
	$class="style='background-color:#f9f9f9'";
	
	
	}
	else{
	$class='';
	
	}
	
	
	
	if($row->published == 1){
			
			$publish_info = JText::_( 'UNPUBLISH_ITEM' );
			$iconclass = "icon-publish";
			$aclass = "btn btn-micro active";
		} else if($row->published == 0){
			
			$publish_info = JText::_( 'PUBLISH_ITEM' );
			$iconclass = "icon-unpublish";
			$aclass = "btn btn-micro ";
		}

	?>
	<div class="row-fluid countryname"  <?php echo $class;?>>
	<div class="span4 countryblock">
	<?php echo JHtml::_('grid.id', $i, $row->id); ?>
		
	</div>
	<div class="span4 countryblock">
	
			<?php echo $row->name; ?>		
		
    </div>
	<div class="span4 countryblock">
			<span class="editlinktip hasTip" title="<?php echo $publish_info; ?>">
				<a  class="<?php echo $aclass; ?>" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->published ? 'unpublish' : 'publish' ?>')">
					<i class="<?php echo $iconclass;  ?>"></i>
				</a>
			</span>				
    </div>

	</div>
	<?php
		$k = 1 - $k;	$i++;
	?>

	
		
	<?php	
	}
	
	}
	
else { echo '<tr><td colspan="10"><center><h5>'. JText::_('NO_RESULT_IN_LIST') .'</h5></center></td></tr>'; }
	?>
    
</div>
	<div class="row-fluid">
	<div class="span12">
	</div>
	</div>
	<div class="row-fluid">
     <div class="span6">
     <span class="bold hasTip" title="<?php echo JText::_('BLOCKED_IP');?>"> <?php echo JText::_(' View Blocked IP'); ?></span>
     </div>
	 <div class="span6">
     <a href="<?php echo JURI::base(); ?>index.php?option=com_jsecure&task=countrylog"/>
    View country log</a>
   </div>
     </div>
	 <!--</fieldset>-->
  <input type="hidden" name="option" value="com_jsecure"/>
  <input type="hidden" name="task" value="" />
  <input name="sendemail" type="hidden" value="<?php echo $JSecureConfig->sendemail; ?>" size="50" />
  <input type="hidden" name="boxchecked" value="0"/>
  
  
</form>
<script>

		
		$(document).ready(function(){
			$("#countrylist").mCustomScrollbar({
				autoHideScrollbar:true
			});
		});
</script>
</body>
<?php
}
?>