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
$key =$license_key;
$JSecureConfig = $this->JSecureConfig;
$captcha = $this->captcha;
$captchadatasitekey = $this->captchadatasitekey;


JHtml::_('behavior.framework', true);
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
//include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
$app        = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/validate_license.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/basic.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>');
$document->addCustomTag('<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />');
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
		window.addEvent('domready', function() {

			SqueezeBox.initialize({});
			SqueezeBox.assign($$('a.modal'), {
				parse: 'rel'
			});
		});
");
$controller = new jsecureControllerjsecure();

if (!$controller->isMasterLogged() and $JSecureConfig->enableMasterPassword == '1'and $JSecureConfig->include_basic_confg == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
?>
<body onload = "hidefield(); showlicense('<?php echo $key;?>','<?php echo $sub_cat_id;?>');">
<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />
<script src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>
<script src="components/com_jsecure/js/dashboard_menu.js"></script>
<h3><?php echo JText::_('BASIC_CONFIGURATION');?></h3>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" id="adminForm" autocomplete="off">
<fieldset class="adminform">
      <table style="table-layout:fixed;" class="table table-striped">
        <tr>
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('ENABLE')."::".JText::_('PUBLISHED_DESCRIPTION');?>"> <?php echo JText::_('ENABLE'); ?> </span> </td>
          <td class="paramlist_value">
		<fieldset id="jform_home" class="radio btn-group">
  			<input  type="radio" name="publish" value="1" <?php echo ($JSecureConfig->publish == 1)? 'checked="checked"':''; ?> id="publish1" />
  			<label class="btn" for="publish1">Yes</label>
  			<input type="radio" name="publish" value="0" <?php echo ($JSecureConfig->publish == 0)?  'checked="checked"':''; ?> id="publish0" />
  			<label class="btn" for="publish0">No</label>
		</fieldset>
          </td>
        </tr>
		
        <tr>
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('PASS_KEY').'::'.JText::_('PASS_KEY_OPTION_DESCRIPTION'); ?>"> <?php echo JText::_('PASS_KEY'); ?> </span> </td>
          <td class="paramlist_value">
			<fieldset id="passkeytype" class="radio btn-group">
  			<input type="radio" name="passkeytype" value="url" <?php echo ($JSecureConfig->passkeytype == "url")? 'checked="checked"':''; ?> id="url" />
  			<label class="btn" for="url"><?php echo JText::_('URL'); ?></label>
  			<input type="radio" name="passkeytype" value="form" <?php echo ($JSecureConfig->passkeytype == "form")? 'checked="checked"':''; ?> id="form" />
  			<label class="btn" for="form"><?php echo JText::_('FORM'); ?></label>
			</fieldset>
          </td>
        </tr>
		
        <tr>
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('KEY').'::'.JText::_('KEY_DESCRIPTION'); ?>"> <?php echo JText::_('KEY'); ?> </span> </td>
          <td class="paramlist_value"><input type="password" name="key" id="key" value="" size="50"/>
          </td>
        </tr>
		
		<tr>
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('REDIRECT_OPTIONS').'::'.JText::_('REDIRECT_OPTIONS_DESCRIPTION'); ?>"> <?php echo JText::_('REDIRECT_OPTIONS'); ?> </span> </td>
          <td class="paramlist_value">
				 <!--[if lt IE 9]>
				 <fieldset id="options" class="radio btn-group-22">
				 <input type="radio" name="options" value="0" <?php echo ($JSecureConfig->options == 0)? 'checked="checked"':''; ?> id="options0" />
				 <label class="btn" for="options0"><?php echo JText::_('REDIRECT_INDEX'); ?></label>
				 <input type="radio" name="options" value="1" <?php echo ($JSecureConfig->options == 1)? 'checked="checked"':''; ?> id="options1" />
				 <label class="btn" for="options1"><?php echo JText::_('CUSTOM_PATH'); ?></label>
			   </fieldset>
			   <![endif]-->
			   
			   <!--[if IE 9]>
				 <fieldset id="options" class="radio btn-group">
				 <input type="radio" name="options" value="0" <?php echo ($JSecureConfig->options == 0)? 'checked="checked"':''; ?> id="options0" />
				 <label class="btn" for="options0"><?php echo JText::_('REDIRECT_INDEX'); ?></label>
				 <input type="radio" name="options" value="1" <?php echo ($JSecureConfig->options == 1)? 'checked="checked"':''; ?> id="options1" />
				 <label class="btn" for="options1"><?php echo JText::_('CUSTOM_PATH'); ?></label>
			   </fieldset>
			   <![endif]-->
			   
			   <![if !IE]>
				 <fieldset id="options" class="radio btn-group">
				 <input type="radio" name="options" value="0" <?php echo ($JSecureConfig->options == 0)? 'checked="checked"':''; ?> id="options0" />
				 <label class="btn" for="options0"><?php echo JText::_('REDIRECT_INDEX'); ?></label>
				 <input type="radio" name="options" value="1" <?php echo ($JSecureConfig->options == 1)? 'checked="checked"':''; ?> id="options1" />
				 <label class="btn" for="options1"><?php echo JText::_('CUSTOM_PATH'); ?></label>
			   </fieldset>
			   <![endif]>
          </td>
        </tr>
		
        <tr id="custom_path">
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('CUSTOM_PATH').'::'.JText::_('CUSTOM_PATH_DESCRIPTION'); ?>"> <?php echo JText::_('CUSTOM_PATH'); ?> </span> </td>
          <td class="paramlist_value"><input name="custom_path" type="text" value="<?php echo $JSecureConfig->custom_path; ?>" size="50" />
          </td>
        </tr>
		
		<tr>
		<td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('CAPTCHA_STATUS').'::'.JText::_('CAPTCHA_OPTION_DESCRIPTION'); ?>"> <?php echo JText::_('CAPTCHA_STATUS'); ?> </span> </td>
		<td class="paramlist_value">
		<fieldset id="jform_home" class="radio btn-group">
  			<input onclick="hidefield()" type="radio" name="captchapublish" value="1" <?php echo ($JSecureConfig->captchapublish == 1)? 'checked="checked"':''; ?> id="captchapublish1" />
  			<label class="btn" for="captchapublish1">Yes</label>
  			<input onclick="hidefield()" type="radio" name="captchapublish" value="0" <?php echo ($JSecureConfig->captchapublish == 0)?  'checked="checked"':''; ?> id="captchapublish0" />
  			<label class="btn" for="captchapublish0">No</label>
		</fieldset>
          </td>
		
		</tr>
		
		 <tr id="captchakey1">
          <td class="paramlist_key"><span class="bold hasTip" id="captchakey1" title="<?php echo JText::_('CAPTCHA_KEY').'::'.JText::_('CAPTCHA_KEY_DESCRIPTION'); ?>"> <?php echo JText::_('CAPTCHA_KEY'); ?> </span> </td>
          <td class="paramlist_value"><input type="text" name="captchakey" id="captchakey" value="<?php echo (isset($captcha[0]->Value) && $captcha[0]->Value != '')?  $captcha[0]->Value : ''; ?>" size="50"/>
          </td>
        </tr>
		
		<tr id="captchadatasitekey1">
          <td class="paramlist_key"><span id="captchadatasitekey1" class="bold hasTip" title="<?php echo JText::_('CAPTCHA_DATA_SITE_KEY').'::'.JText::_('CAPTCHA_DATA_SITE_KEY_DESCRIPTION'); ?>"> <?php echo JText::_('CAPTCHA_DATA_SITE_KEY'); ?> </span> </td>
          <td class="paramlist_value"><input type="text" name="captchadatasitekey" id="captchadatasitekey" value="<?php echo (isset($captchadatasitekey[0]->Value) && $captchadatasitekey[0]->Value != '')?  $captchadatasitekey[0]->Value : ''; ?>" size="50"/>
          </td>
        </tr>
		
        <tr id="USEFULLINKS">
		<td width="100" align="left" class="key">
			<span class="bold hasTip" title="<?php echo JText::_('USEFUL_CAPTCHA_LINKS_DESCRIPTION'); ?>"> <?php echo JText::_('USEFUL_LINKS'); ?> </span>
		</td>
		<td>		
		<a title="Get the Google Re-Captcha keys" href="https://www.google.com/recaptcha/intro/index.html" target="_blank">Obtain Google Re-Captcha Keys</a>
		</td>
		
	</tr>
		
      </table>
      </fieldset>
  <input type="hidden" name="option" value="com_jsecure"/>
  <input type="hidden" name="task" value="saveBasic" />
  <input name="sendemail" type="hidden" value="<?php echo $JSecureConfig->sendemail; ?>" size="50" />
</form>
<div id="license"></div>
</body>
<?php
}
?>