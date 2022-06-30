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
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
$app        = JFactory::getApplication();
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

	$JSecureConfig = $this->JSecureConfig;
	$document = JFactory::getDocument();
 $document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/logincntrl.js"></script>');
		
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
	
	if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_logincontrol == '1' )
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
<h3><?php echo JText::_('LOGIN_CONTROL');?></h3>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();">
<fieldset class="adminform">
	<table class="table table-striped">
	<tr>
		<td class="paramlist_key">
			<span class="editlinktip">
				<label id="paramsshowAllChildren-lbl" for="paramsshowAllChildren" class="hasTip" title="<?php echo JText::_('CONFIGURATION_LOGIN_CONTROL_DESCRIPTION'); ?>">
					<?php echo JText::_('CONFIGURATION_LOGIN_CONTROL'); ?>
				</label>
			</span>		
		</td>
		<td class="paramlist_value" width="60%"><fieldset id="login_control" class="radio btn-group">
            <input type="radio" name="login_control" value="0" <?php echo ($JSecureConfig->login_control==0)? 'checked="checked"':''; ?> id="login_control0" />
            <label class="btn" for="login_control0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
            <input type="radio" name="login_control" value="1" <?php echo ($JSecureConfig->login_control==1)? 'checked="checked"':''; ?> id="login_control1" />
            <label class="btn" for="login_control1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
            </fieldset></td>			
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