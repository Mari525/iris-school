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
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
$document = JFactory::getDocument();
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/log.js"></script>');
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
	
	
	if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_log == '1' )
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));	
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
	
?>

<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" class="span10">
<h3><?php echo JText::_('LOG');?></h3>
<fieldset class="adminform">
	<table class="table table-striped">
	<tr>
		<td class="paramlist_key">
			<span class="editlinktip">
				<label id="paramsshowAllChildren-lbl" for="paramsshowAllChildren" class="hasTip" title="<?php echo JText::_('KEEP_LOG_FOR_DESCRIPTION'); ?>">
					<?php echo JText::_('KEEP_LOG_FOR'); ?>
				</label>
			</span>		
		</td>
		<td class="paramlist_value">
			<select name="delete_log" id="delete_log" style="width:100px">
				<option value="0" <?php echo ($JSecureConfig->delete_log==0)?"selected":''; ?>>Forever</option>
				<option value="1" <?php echo ($JSecureConfig->delete_log==1)?"selected":''; ?>>1 Months</option>
				<option value="2" <?php echo ($JSecureConfig->delete_log==2)?"selected":''; ?>>2 Months</option>
				<option value="3" <?php echo ($JSecureConfig->delete_log==3)?"selected":''; ?>>3 Months</option>
				<option value="4" <?php echo ($JSecureConfig->delete_log==4)?"selected":''; ?>>4 Months</option>
				<option value="5" <?php echo ($JSecureConfig->delete_log==5)?"selected":''; ?>>5 Months</option>
			</select>
		</td>		
	</tr>	
	</table>
</fieldset>
<input type="hidden" name="task" value="" />
<?php
}
?>

<input type="hidden" name="option" value="com_jsecure"/>
</form>