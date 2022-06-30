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
$document = JFactory::getDocument();
JHtml::_('behavior.framework', true);
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
$app        = JFactory::getApplication();
$document = JFactory::getDocument();
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
	$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/ip.js"></script>');
		
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
	
	
	if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_ip == '1' )
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));	
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
	
?>

<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" class="span10">
<h3><?php echo JText::_('IP_CONFIG');?></h3>
  <div class="tab-pane" id="ip_config_tab">
      <fieldset class="adminform">
      <table class="table table-striped">
        <tr>
          <td class="paramlist_key" width="40%">
	  <span class="editlinktip hasTip" title="<?php echo "Select".'::'.JText::_('IP_TYPE'); ?>"><?php echo JText::_('IP_TYPE'); ?></span></td>
          <td class="paramlist_value" width="60%"><select name="iptype" id="iptype" onchange="javascript: ipLising(this);">
              <option value="0" <?php echo ($JSecureConfig->iptype == 0)?"selected":''; ?>><?php echo JText::_('BLOCKED_IP'); ?></option>
              <option value="1" <?php echo ($JSecureConfig->iptype == 1)?"selected":''; ?>><?php echo JText::_('WHISH_IP'); ?></option>
            </select></td>
        </tr>
        <tr id="BipLisingAddbox">
          <td class="paramlist_key" width="40%"><span class="editlinktip hasTip" title="<?php echo JText::_('ADD_IP_DESC'); ?>"> <?php echo JText::_('ACCESS_IP'); ?> </span> </td>
          <td width="60%"><table border="0">
              <tr>
                <td align="left" valign="bottom" width="20%"><input type="text" name="blacklist_ip1" id="blacklist_ip1" value="" size="3" maxlength="3" onkeyup="isNumeric(this)" style="width:32%;"/>
                  <b>&nbsp;.</b></td>
                <td align="left" valign="bottom" width="20%"><input type="text" name="blacklist_ip2" id="blacklist_ip2" value="" size="3" maxlength="3" onkeyup="isNumeric(this)" style="width:32%;"/>
                  <b>&nbsp;.</b></td>
                <td align="left" valign="bottom" width="20%"><input type="text" name="blacklist_ip3" id="blacklist_ip3" value="" size="3" maxlength="3" onkeyup="isNumeric(this)" style="width:32%;"/>
                  <b>&nbsp;.</b></td>
                <td align="left" valign="bottom" width="20%"><input type="text" name="blacklist_ip4" id="blacklist_ip4" value="" size="3" maxlength="3" onkeyup="isNumeric(this)" style="width:32%;"/></td>
                <td align="left" width="20%"><input type="button" id="add_ipB" name="" value="<?php echo JText::_('ADD'); ?>" onclick="addIpB('blacklist_ip', 'blacklist_ips');" class="btn btn-small btn-success"/></td>
              </tr>
            </table></td>
        </tr>
        <tr id="BipLisingIpbox">
          <td align="right" class="key" width="40%"><span class="editlinktip hasTip" title="<?php echo JText::_('ACCESS_IP_BLACKLIST'); ?>"> <?php echo JText::_('ACCESS_IP_BLACKLIST'); ?> </span> </td>
          <td width="60%"><textarea cols="80" rows="10" class="text_area" type="text" name="iplistB" id="blacklist_ips"><?php echo $JSecureConfig->iplistB; ?></textarea>
          </td>
        </tr>
        <tr id="WipLisingAddbox">
          <td class="paramlist_key" width="40%"><span class="editlinktip hasTip" title="<?php echo JText::_('ADD_IP_DESC'); ?>"> <?php echo JText::_('ACCESS_IP'); ?> </span> </td>
          <td width="60%"><table border="0">
              <tr>
                <td align="left" width="20%" valign="bottom"><input type="text" name="whitelist_ip1" id="whitelist_ip1" value="" size="3" maxlength="3" onkeyup="isNumeric(this)" style="width:32%;"/>
                  <b>&nbsp;.</b></td>
                <td align="left" width="20%" valign="bottom"><input type="text" name="whitelist_ip2" id="whitelist_ip2" value="" size="3" maxlength="3" onkeyup="isNumeric(this)" style="width:32%;"/>
                  <b>&nbsp;.</b></td>
                <td align="left" width="20%" valign="bottom"><input type="text" name="whitelist_ip3" id="whitelist_ip3" value="" size="3" maxlength="3" onkeyup="isNumeric(this)" style="width:32%;"/>
                  <b>&nbsp;.</b></td>
                <td align="left" width="20%" valign="bottom"><input type="text" name="whitelist_ip4" id="whitelist_ip4" value="" size="3" maxlength="3" onkeyup="isNumeric(this)" style="width:32%;"/></td>
                <td align="left" width="20%"><input type="button" id="add_ipW" name="" value="<?php echo JText::_('ADD'); ?>" onclick="addIpW('whitelist_ip', 'whitelist_ips');" class="btn btn-small btn-success"/></td>
              </tr>
            </table></td>
        </tr>
        <tr id="WipLisingIpbox">
          <td align="right" class="key" width="40%"><span class="editlinktip hasTip" title="<?php echo JText::_('ACCESS_IP_WHITELIST'); ?>"> <?php echo JText::_('ACCESS_IP_WHITELIST'); ?> </span> </td>
          <td width="60%"><textarea cols="80" rows="10" class="text_area" type="text" name="iplistW" id="whitelist_ips"><?php echo $JSecureConfig->iplistW; ?></textarea>
          </td>
        </tr>
      </table>
      </fieldset>
    </div>
<input type="hidden" name="task" value="" />
<script type="text/javascript">
ipLising(document.getElementById('iptype'));
</script>
<?php
}
?>
<input type="hidden" name="option" value="com_jsecure"/>


</form>




