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
JHTML::_('behavior.modal');
JHtml::_('behavior.formvalidation');
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
$document = JFactory::getDocument();
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/imagesecure.js"></script>');
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

if (!$controller->isMasterLogged() and $JSecureConfig->enableMasterPassword == '1'and $JSecureConfig->include_image_secure == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else {
?>
	
	<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
	
<form action="index.php?option=com_jsecure&task=userkey" method="post" name="adminForm" id= "adminForm" enctype="multipart/form-data" class="span10">
	<h3><?php echo JText::_('IMAGE_SECURE_HEADING');?></h3>
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
<?php } ?>