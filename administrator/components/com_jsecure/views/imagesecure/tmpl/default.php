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
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'helper.php';
$license_key = licenseHelper::getLicenseKey();
$sub_cat_id = licenseHelper::getSubscriptionCatid();

JHtml::_('behavior.framework', true);
JHTML::_('behavior.modal');
JHtml::_('behavior.formvalidation');
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
$document = JFactory::getDocument();
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/validate_license.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/imagesecure.js"></script>');
$document->addCustomTag('<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/internalpages.css" />');
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
        var key = '$license_key';
		var subcatid = '$sub_cat_id';
		window.onload = function(){
		showlicense(key,subcatid);
		}
");
?>
<?php
$JSecureConfig = new JSecureConfig();
$controller = new jsecureControllerjsecure();
$app        = JFactory::getApplication();

if (!$controller->isMasterLogged() and $JSecureConfig->enableMasterPassword == '1'and $JSecureConfig->include_image_secure == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else {
?>
<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />
<script src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>
<script>
	jQuery(window).load(function(){
		$window_height = jQuery(window).innerHeight();
		$subhead_height = jQuery('.subhead').innerHeight();
		$sidebar_scroll_height = $window_height - $subhead_height - 30;
		jQuery('.task-imageSecure .container-fluid.container-main .span2').css('height',$sidebar_scroll_height);
		
		jQuery(".container-fluid.container-main .span2").mCustomScrollbar({
			autoHideScrollbar:true
		});
	});
	jQuery(window).scroll(function() {
		if(jQuery('.desktopview .subhead-collapse.collapse > .subhead').hasClass('subhead-fixed')){
			jQuery('.desktopview .container-fluid.container-main .span2').addClass('fixedsidebar');
			
		} else {
			jQuery('.container-fluid.container-main .span2').removeClass('fixedsidebar');
		}
	});
</script>

	<h3><?php echo JText::_('IMAGE_SECURE_HEADING');?></h3>
	
	
<form action="index.php?option=com_jsecure&task=userkey" method="post" name="adminForm" id= "adminForm" enctype="multipart/form-data">
	
	<fieldset class="adminform">
      <table class="table table-striped">
        <tr>
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('ENABLE')."::".JText::_('IMAGE_SECURE_STATUS_DESCRIPTION');?>"> <?php echo JText::_('ENABLE'); ?> </span> </td>
          <td class="paramlist_value">
		<fieldset id="jform_home" class="radio btn-group">
  			<input  type="radio" name="publish" value="1" <?php echo ($JSecureConfig->imageSecure == 1)? 'checked="checked"':''; ?> id="publish1" />
  			<label class="btn" for="publish1">Yes</label>
  			<input type="radio" name="publish" value="0" <?php echo ($JSecureConfig->imageSecure == 0)?  'checked="checked"':''; ?> id="publish0" />
  			<label class="btn" for="publish0">No</label>
		</fieldset>
          </td>
        </tr>
		
		
		
		<tr>
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('IMAGE_SECURE_HEADING')."::".JText::_('IMAGE_SECURE_UPLOAD_DESCRIPTION');?>"> <?php echo JText::_('IMAGE_SECURE_HEADING'); ?> </span> </td>
          <td class="paramlist_value">
		<fieldset id="jform_home">
  			<input type="file" name="Secureimage" id="Secureimage">
		</fieldset>
          </td>
        </tr>
		
		<tr>
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('IMAGE_SECURE_CURRENT')."::".JText::_('IMAGE_SECURE_CURRENT_DESCRIPTION');?>"> <?php echo JText::_('IMAGE_SECURE_CURRENT'); ?> </span> </td>
          <td class="paramlist_value">
		<fieldset id="jform_home">
		<?php 
			foreach(glob('../administrator/components/com_jsecure/images/secureimage/*.*') as $file)
			if(is_file($file))
			{
			   $secureImagePath = $file;
			}
			?>
			<?php if(isset($secureImagePath) && $secureImagePath !=''){ ?>
  			<a class="modal" title="Secure Image Preview"  href="<?php echo $secureImagePath; ?>" rel="{handler: 'iframe'}">							
			<?php }
				  else{
			?>
			<a class="modal" title="Secure Image Preview"  href="../administrator/components/com_jsecure/images/No_image_available.jpg" rel="{handler: 'iframe'}">
			<?php } ?>
									<?php if(isset($secureImagePath) && $secureImagePath !=''){ ?>
									
									<img style="height:150px;width:220px;border:1px solid #2F9FB3;" src="<?php echo $secureImagePath; ?>"></img> 
									<?php }
									else{
									?>
									<img style="height:150px;width:220px;border:1px solid #2F9FB3;" src="../administrator/components/com_jsecure/images/No_image_available.jpg"></img> 
									<?php } ?>
								</a>
		</fieldset>
          </td>
        </tr>
		
      </table>
      </fieldset>
	
<input type="hidden" name="option" value="com_jsecure" />
<input type="hidden" name="task" value=""/>
<input type="hidden" name="boxchecked" value="0" />
</form>
<div id="license"></div>
<?php } ?>