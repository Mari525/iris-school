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
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'helper.php';
$license_key = licenseHelper::getLicenseKey();
$sub_cat_id = licenseHelper::getSubscriptionCatid();

JHtml::_('behavior.framework', true);
$JSecureConfig = $this->JSecureConfig;
$document = JFactory::getDocument();
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/validate_license.js"></script>');
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
	$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/metatags.js"></script>');

		
	jimport('joomla.environment.browser');
    $doc = JFactory::getDocument();
    $browser = &JBrowser::getInstance();
    $browserType = $browser->getBrowser();
    $browserVersion = $browser->getMajor();
    if(($browserType == 'msie') && ($browserVersion = 7))
    {
    	$document->addScript(JURI::base()."components/com_jsecure/js/tabs.js");
    }
	$app        = JFactory::getApplication();
?>

<?php
	
	
	if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_metatags == '1' )
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));	
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{

?>
<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />
<script src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>
<script>
	jQuery(window).load(function(){
		$window_height = jQuery(window).innerHeight();
		$subhead_height = jQuery('.subhead').innerHeight();
		$sidebar_scroll_height = $window_height - $subhead_height - 30;
		jQuery('.container-fluid.container-main .span2').css('height',$sidebar_scroll_height);
		
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
<h3><?php echo JText::_('META_TAG_CONTROL');?></h3>
<form action="index.php?option=com_jsecure&task=metatags" method="post" name="adminForm" onsubmit="return submitbutton();">
<fieldset class="adminform">
	<table class="table table-striped">
	<tr>
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('MTEATAGCONTROL_DESCRIPTION'); ?>">
			<?php echo JText::_('META_TAG_CONTROL_LABEL'); ?>
		</span>
		</td>
		 <td class="paramlist_value" width="60%">
		  <!--[if lt IE 9]>
    <fieldset id="metatagcontrol" class="radio btn-group-id">
            <input type="radio" name="metatagcontrol" value="0" <?php echo ($JSecureConfig->metatagcontrol==0)? 'checked="checked"':''; ?> id="metatagcontrol0" />
            <label class="btn" for="metatagcontrol0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
            <input type="radio" name="metatagcontrol" value="1" <?php echo ($JSecureConfig->metatagcontrol==1)? 'checked="checked"':''; ?> id="metatagcontrol1" />
            <label class="btn" for="metatagcontrol1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
            </fieldset>
   <![endif]-->
   
   <!--[if IE 9]>
    <fieldset id="metatagcontrol" class="radio btn-group">
            <input type="radio" name="metatagcontrol" value="0" <?php echo ($JSecureConfig->metatagcontrol==0)? 'checked="checked"':''; ?> id="metatagcontrol0" />
            <label class="btn" for="metatagcontrol0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
            <input type="radio" name="metatagcontrol" value="1" <?php echo ($JSecureConfig->metatagcontrol==1)? 'checked="checked"':''; ?> id="metatagcontrol1" />
            <label class="btn" for="metatagcontrol1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
            </fieldset>
   <![endif]-->
   
   <![if !IE]>
     <fieldset id="metatagcontrol" class="radio btn-group">
            <input type="radio" name="metatagcontrol" value="0" <?php echo ($JSecureConfig->metatagcontrol==0)? 'checked="checked"':''; ?> id="metatagcontrol0" />
            <label class="btn" for="metatagcontrol0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
            <input type="radio" name="metatagcontrol" value="1" <?php echo ($JSecureConfig->metatagcontrol==1)? 'checked="checked"':''; ?> id="metatagcontrol1" />
            <label class="btn" for="metatagcontrol1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
            </fieldset>
   <![endif]>
			</td>
	</tr>
  <tr id="tag_generator">
		<td class="paramlist_key" width="40%">
			<span class="editlinktip hasTip" title="<?php echo JText::_('METATAGGENERATOR_DESCRIPTION'); ?>">
					<?php echo JText::_('METATAG_GENERATOR'); ?>
			</span>
		</td>
		<td class="paramlist_value" width="60%">
			<textarea name="metatag_generator" ><?php echo $JSecureConfig->metatag_generator;?></textarea>
		</td>	
	</tr>
	<tr id="tag_keywords">
		<td class="paramlist_key">
			<span class="editlinktip hasTip" title="<?php echo JText::_('METATAGKEYWORDS_DESCRIPTION'); ?>">
					<?php echo JText::_('METATAG_KEYWORD'); ?>
			</span>
		</td>
		<td class="paramlist_value">
			<textarea name="metatag_keywords" ><?php echo $JSecureConfig->metatag_keywords;?></textarea>
		</td>
	</tr>
	<tr id="tag_description">
		<td class="paramlist_key">
			<span class="editlinktip hasTip" title="<?php echo JText::_('METATAGDESCRIPTION__DESCRIPTION'); ?>">
					<?php echo JText::_('METATAG_DESCRIPTION'); ?>
			</span>
		</td>
		<td class="paramlist_value">
			<textarea name="metatag_description"><?php echo $JSecureConfig->metatag_description;?></textarea>
		</td>		
	</tr>
    <tr id="tag_rights">
			<td width="150">
			<span class="editlinktip hasTip" title="<?php echo JText::_('METATAGRIGHTS_DESCRIPTION'); ?>">
			<?php echo JText::_('METATAG_RIGHTS'); ?></span>
			</td>
			<td><textarea name="metatag_rights" class="textarea" ><?php echo $JSecureConfig->metatag_rights;?></textarea></td>
		</tr>
	</table>
	</fieldset>
<input type="hidden" name="task" value="" />
<?php
}

?>


<input type="hidden" name="option" value="com_jsecure"/>
</form>
<div id="license"></div>