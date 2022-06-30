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
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';

$app      = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/modern_jquery.mCustomScrollbar.css");
$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/styles.css");
/*$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/bootstrap.min.css");*/
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/jquery.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/scrollspy.js"></script>');
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

if (!$controller->isMasterLogged() and $JSecureConfig->enableMasterPassword == '1'and $JSecureConfig->include_country_block == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
?>
<body onload="init();">


<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" id="adminForm" autocomplete="off" class="span10">
<h3 style="margin-left:5px"><?php echo JText::_('COUNTRY_BLOCK');?></h3>
<ul class="nav nav-tabs">
  <li class="active"><a href="#countryblockstatus" data-toggle="tab" ><?php echo JText::_('COUNTRY_BLOCK'); ?></a></li>
  <li><a href="#countrylogs" data-toggle="tab" onclick="activate_tab()"><?php echo JText::_('LOGS'); ?></a></li>
 
</ul>
  
   <div class="tab-content">
   <div class="tab-pane active"  id="countryblockstatus">
   
      <div class="row-fluid" style="padding:5px 0 10px;">
       <div class="span6"> 
          <span class="bold hasTip" title="<?php echo JText::_('PUBLISHED_COUNTRY_BLOCK');?>"> <?php echo JText::_('ENABLE'); ?> </span> 
        </div>
        <div class="span6"> 		
		<fieldset id="jform_home" class="radio btn-group">
  			<input type="radio" onchange="countryblock(this);" name="publishcountryblock" value="1" <?php echo ($JSecureConfig->countryblock == 1)? 'checked="checked"':''; ?> id="publishcountryblock1" />
  			<label class="btn" for="publishcountryblock1">Yes</label>
  			<input type="radio" onchange="countryblock(this);" name="publishcountryblock" value="0" <?php echo ($JSecureConfig->countryblock == 0)?  'checked="checked"':''; ?> id="publishcountryblock0" />
  			<label class="btn" for="publishcountryblock0">No</label>
		</fieldset>
       </div> 
     </div>
	 
	 <div class="row-fluid" id="countryblockfrontend" style="padding:5px 0 10px;">
       <div class="span6"> 
          <span class="bold hasTip" title="<?php echo JText::_('PUBLISHED_COUNTRY_BLOCK_FRONT');?>"> <?php echo JText::_('ENABLE_COUNTRY_BLOCK_FRONTEND'); ?> </span> 
        </div>
        <div class="span6"> 		
		<fieldset id="jform_home" class="radio btn-group">
  			<input type="radio" name="publishcountryblockfront" value="1" <?php echo ($JSecureConfig->countryblock_frontend == 1)? 'checked="checked"':''; ?> id="publishcountryblockfront1" />
  			<label class="btn" for="publishcountryblockfront1">Yes</label>
  			<input type="radio" name="publishcountryblockfront" value="0" <?php echo ($JSecureConfig->countryblock_frontend == 0)?  'checked="checked"':''; ?> id="publishcountryblockfront0" />
  			<label class="btn" for="publishcountryblockfront0">No</label>
		</fieldset>
       </div> 
     </div>
	 
	 <div class="row-fluid" id="redirectOptions" style="padding:5px 0 10px;">
       <div class="span6"> 
          <span class="bold hasTip" title="<?php echo JText::_('FRONTEND_REDIRECT_OPTIONS').'::'.JText::_('REDIRECT_OPTIONS_DESCRIPTION'); ?>"> <?php echo JText::_('FRONTEND_REDIRECT_OPTIONS'); ?> </span> </div>
             <div class="span6">
				<fieldset id="countryfrnt_options" class="radio btn-group">
				 <input type="radio" onchange="pathfrontend(this);" name="countryfrnt_options" value="0" <?php echo ($JSecureConfig->countryfrnt_options == 0)? 'checked="checked"':''; ?> id="countryfrnt_options0" />
				 <label class="btn" for="countryfrnt_options0"><?php echo JText::_('FRONTEND_REDIRECT_INDEX'); ?></label>
				 <input type="radio" onchange="pathfrontend(this);" name="countryfrnt_options" value="1" <?php echo ($JSecureConfig->countryfrnt_options == 1)? 'checked="checked"':''; ?> id="countryfrnt_options1" />
				 <label class="btn" for="countryfrnt_options1"><?php echo JText::_('FRONTEND_CUSTOM_PATH'); ?></label>
			   </fieldset>
			</div>
        </div>
		
        <div class="row-fluid" id="countryblockfrntpath" style="padding:5px 0 10px;">
       <div class="span6"> 
	   <span class="bold hasTip" title="<?php echo JText::_('COUNTRY_FRONT_CUSTOM_PATH').'::'.JText::_('COUNTRY_FRONT_CUSTOM_PATH_DESCRIPTION'); ?>"> <?php echo JText::_('COUNTRY_FRONT_CUSTOM_PATH'); ?> </span>
	   </div>
	     <div class="span6">www.<input name="country_front_custom_path" type="text" value="<?php echo $JSecureConfig->country_front_custom_path; ?>" size="50" />
          </div>
        </div>
		
	 
	 
	 <div class="row-fluid" style="background-color:#f9f9f9">
     <div class="span12" id ="countries">
	 <span class="bold hasTip" style="padding:5px 0;display: inline-block; padding-top: 5px;" title="<?php echo JText::_('COUNTRY_LIST');?>"> <?php echo JText::_('COUNTRIES'); ?></span>
	 
	 <span class="editlinktip" style="margin-left: 10px;">
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
	 <div class="row-fluid" id="checkall">
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
			
			$publish_info = JText::_( 'UNPUBLISH_COUNTRY' );
			$iconclass = "icon-publish";
			$aclass = "btn btn-micro active";
		} else if($row->published == 0){
			
			$publish_info = JText::_( 'PUBLISH_COUNTRY' );
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
	
	 </div>

 
</div>	 
  <input type="hidden" name="option" value="com_jsecure"/>
  <input type="hidden" id="task" name="task" value="" />
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