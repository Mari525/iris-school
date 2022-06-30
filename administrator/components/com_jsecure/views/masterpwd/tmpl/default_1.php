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
JHtml::_('behavior.framework', true);
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
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
");

	$JSecureConfig = $this->JSecureConfig;
	$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/masterpwd.js"></script>');
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
	//echo $this->pane->startPane('config-pane');

	if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));	
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
	
?>

<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" autocomplete="off" id="adminForm" class="span10" >
<h3><?php echo JText::_('MASTER_PASSWORD');?></h3>
<fieldset class="adminform">
	<table class="table table-striped">
	<tr>
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('ENABLEMASTERPASSWORD_DESCRIPTION'); ?>">
			<?php echo JText::_('MASTER_PASSWORD_ENABLED'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<!--[if lt IE 9]>
            <fieldset id="MasterPasswordenable" class="radio btn-group-id">
  			<input type="radio" name="MasterPasswordenable" value="0" <?php echo ($JSecureConfig->enableMasterPassword == 0)? 'checked="checked"':''; ?> id="MasterPasswordenable0" />
  			<label class="btn" for="MasterPasswordenable0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
  			<input type="radio" name="MasterPasswordenable" value="1" <?php echo ($JSecureConfig->enableMasterPassword == 1)? 'checked="checked"':''; ?> id="MasterPasswordenable1" />
  			<label class="btn" for="MasterPasswordenable1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
			</fieldset>
   <![endif]-->
		  	 <!--[if IE 9]>
            <fieldset id="MasterPasswordenable" class="radio btn-group">
  			<input type="radio" name="MasterPasswordenable" value="0" <?php echo ($JSecureConfig->enableMasterPassword == 0)? 'checked="checked"':''; ?> id="MasterPasswordenable0" />
  			<label class="btn" for="MasterPasswordenable0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
  			<input type="radio" name="MasterPasswordenable" value="1" <?php echo ($JSecureConfig->enableMasterPassword == 1)? 'checked="checked"':''; ?> id="MasterPasswordenable1" />
  			<label class="btn" for="MasterPasswordenable1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
			</fieldset>
   <![endif]-->
   
   <![if !IE]>
            <fieldset id="MasterPasswordenable" class="radio btn-group">
  			<input type="radio" name="MasterPasswordenable" value="0" <?php echo ($JSecureConfig->enableMasterPassword == 0)? 'checked="checked"':''; ?> id="MasterPasswordenable0" />
  			<label class="btn" for="MasterPasswordenable0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
  			<input type="radio" name="MasterPasswordenable" value="1" <?php echo ($JSecureConfig->enableMasterPassword == 1)? 'checked="checked"':''; ?> id="MasterPasswordenable1" />
  			<label class="btn" for="MasterPasswordenable1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
			</fieldset>
   </fieldset>
   <![endif]>
          </td>
	</tr>	
	<tr id="master_password">
		<td class="paramlist_key" width="40%">
			<span class="editlinktip hasTip" title="<?php echo JText::_('MASTER_PASSWORD_DESCRIPTION'); ?>">
					<?php echo JText::_('MASTER_PASSWORD'); ?>
			</span>
		</td>
		<td class="paramlist_value" width="60%">
			<input type="password" name="master_password" value="" size="50" />
		</td>	
	</tr>
    <tr id="verify_master_password">
			<td class="paramlist_key" width="40%"><span class="editlinktip hasTip" title="<?php echo JText::_('RE_ENTER_MASTER_PASSWORD_DESCRIPTION'); ?>">
			<?php echo JText::_('REENTER_MASTER_PASSWORD_DESCRIPTION'); ?>
			</span></td>
			<td class="paramlist_key" width="60%" ><input type="password" name="ret_master_password" class="textarea" value="" size="50" /></td>	
		</tr>
		 <tr id="quick_title">
		 <td colspan="2">
		<strong><?php  echo JText::_('QUICK_SELECTION_TITLE');?></strong>
		</td>
		</tr>
		<tr id="quick_select">
		<td><span class="editlinktip hasTip" title="<?php echo JText::_('QUICK_SELECTION_DESCRIPTION'); ?>">
			<strong><?php echo JText::_('QUICK_SELECTION'); ?></strong>
			</td>
			<td>
		<!-- Select unselect buttons -->
		<!--[if lt IE 9]>
           <fieldset class="radio btn-group-id" id="switch_button">
			<input type="radio" id="switch_button1" value="1" name="switch_button">
			<label for="switch_button1" class="btn">All</label>
			<input type="radio" id="switch_button0"value="0" name="switch_button">
			<label for="switch_button0" class="btn">None</label>
		</fieldset>
   <![endif]-->
		  	 <!--[if IE 9]>
            <fieldset class="radio btn-group" id="switch_button">
			<input type="radio" id="switch_button1" value="1" name="switch_button">
			<label for="switch_button1" class="btn">All</label>
			<input type="radio" id="switch_button0"value="0" name="switch_button">
			<label for="switch_button0" class="btn">None</label>
		</fieldset>
   <![endif]-->
   
   <![if !IE]>
            <fieldset class="radio btn-group" id="switch_button">
			<input type="radio" id="switch_button1" value="1" name="switch_button">
			<label for="switch_button1" class="btn">All</label>
			<input type="radio" id="switch_button0"value="0" name="switch_button">
			<label for="switch_button0" class="btn">None</label>
		</fieldset>
   </fieldset>
   <![endif]>
		</td>
		</tr>
	<tr id="include_basic_confg">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDEPASSKEY_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_BASIC_CONF'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_basic_confg" class="radio btn-group">
  			<input  type="radio" name="include_basic_confg" value="1" <?php echo ($JSecureConfig->include_basic_confg == 1)? 'checked="checked"':''; ?> id="include_basic_confg1" />
  			<label class="btn" for="include_basic_confg1">Yes</label>
  			<input type="radio" name="include_basic_confg" value="0" <?php echo ($JSecureConfig->include_basic_confg == 0)?  'checked="checked"':''; ?> id="include_basic_confg0" />
  			<label class="btn" for="include_basic_confg0">No</label>
		</fieldset>
          </td>
	</tr>
		<tr id="include_email_scan">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDEEMAIL_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_EMAIL_SCAN'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_email_scan" class="radio btn-group">
  			<input  type="radio" name="include_email_scan" value="1" <?php echo ($JSecureConfig->include_email_scan == 1)? 'checked="checked"':''; ?> id="include_email_scan1" />
  			<label class="btn" for="include_email_scan1">Yes</label>
  			<input type="radio" name="include_email_scan" value="0" <?php echo ($JSecureConfig->include_email_scan == 0)?  'checked="checked"':''; ?> id="include_email_scan0" />
  			<label class="btn" for="include_email_scan0">No</label>
		</fieldset>
          </td>
	</tr>
	<tr id="include_user_key"><!-- User Key -->
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDEUSERKEY_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_USER_KEY'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_user_key" class="radio btn-group">
  			<input  type="radio" name="include_user_key" value="1" <?php echo ($JSecureConfig->include_user_key == 1)? 'checked="checked"':''; ?> id="include_user_key1" />
  			<label class="btn" for="include_user_key1">Yes</label>
  			<input type="radio" name="include_user_key" value="0" <?php echo ($JSecureConfig->include_user_key == 0)?  'checked="checked"':''; ?> id="include_user_key0" />
  			<label class="btn" for="include_user_key0">No</label>
		</fieldset>
          </td>
	</tr>
	
	
	<tr id="include_country_block"><!-- User Key -->
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDECOUNTRYBLOCK_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_COUNTRY_BLOCK'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_country_block" class="radio btn-group">
  			<input  type="radio" name="include_country_block" value="1" <?php echo ($JSecureConfig->include_country_block == 1)? 'checked="checked"':''; ?> id="include_country_block1" />
  			<label class="btn" for="include_country_block1">Yes</label>
  			<input type="radio" name="include_country_block" value="0" <?php echo ($JSecureConfig->include_country_block == 0)?  'checked="checked"':''; ?> id="include_country_block0" />
  			<label class="btn" for="include_country_block0">No</label>
		</fieldset>
          </td>
	</tr>
	
	
	<tr id="include_image_secure"><!-- User Key -->
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDEIMAGESECURE_DESCRIPTION'); ?>">
			<?php echo JText::_('COM_JSECURE_IMAGE_SECURE'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_image_secure" class="radio btn-group">
  			<input  type="radio" name="include_image_secure" value="1" <?php echo ($JSecureConfig->include_image_secure == 1)? 'checked="checked"':''; ?> id="include_image_secure1" />
  			<label class="btn" for="include_image_secure1">Yes</label>
  			<input type="radio" name="include_image_secure" value="0" <?php echo ($JSecureConfig->include_image_secure == 0)?  'checked="checked"':''; ?> id="include_image_secure0" />
  			<label class="btn" for="include_image_secure0">No</label>
		</fieldset>
          </td>
	</tr>
	<tr id="include_adminpwdpro">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDEREDIRECT_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_ADMINPWDPRO'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_adminpwdpro" class="radio btn-group">
  			<input  type="radio" name="include_adminpwdpro" value="1" <?php echo ($JSecureConfig->include_adminpwdpro == 1)? 'checked="checked"':''; ?> id="include_adminpwdpro1" />
  			<label class="btn" for="include_adminpwdpro1">Yes</label>
  			<input type="radio" name="include_adminpwdpro" value="0" <?php echo ($JSecureConfig->include_adminpwdpro == 0)?  'checked="checked"':''; ?> id="include_adminpwdpro0" />
  			<label class="btn" for="include_adminpwdpro0">No</label>
		</fieldset>
          </td>
	</tr>
	<tr id="include_mail">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDEMAIL_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_MAIL'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_mail" class="radio btn-group">
  			<input  type="radio" name="include_mail" value="1" <?php echo ($JSecureConfig->include_mail == 1)? 'checked="checked"':''; ?> id="include_mail1" />
  			<label class="btn" for="include_mail1">Yes</label>
  			<input type="radio" name="include_mail" value="0" <?php echo ($JSecureConfig->include_mail == 0)?  'checked="checked"':''; ?> id="include_mail0" />
  			<label class="btn" for="include_mail0">No</label>
		</fieldset>
          </td>
	</tr>
		<tr id="include_ip">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDEIP_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_IP'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_ip" class="radio btn-group">
  			<input  type="radio" name="include_ip" value="1" <?php echo ($JSecureConfig->include_ip == 1)? 'checked="checked"':''; ?> id="include_ip1" />
  			<label class="btn" for="include_ip1">Yes</label>
  			<input type="radio" name="include_ip" value="0" <?php echo ($JSecureConfig->include_ip == 0)?  'checked="checked"':''; ?> id="include_ip0" />
  			<label class="btn" for="include_ip0">No</label>
		</fieldset>
          </td>
	</tr>
	<tr id="include_mastermail">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDEMASTERMAIL_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_MASTERMAIL'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_mastermail" class="radio btn-group">
  			<input  type="radio" name="include_mastermail" value="1" <?php echo ($JSecureConfig->include_mastermail == 1)? 'checked="checked"':''; ?> id="include_mastermail1" />
  			<label class="btn" for="include_mastermail1">Yes</label>
  			<input type="radio" name="include_mastermail" value="0" <?php echo ($JSecureConfig->include_mastermail == 0)?  'checked="checked"':''; ?> id="include_mastermail0" />
  			<label class="btn" for="include_mastermail0">No</label>
		</fieldset>
          </td>
	</tr>
	<tr id="include_log">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDELOG_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_LOG'); ?>
		</span>
		</td>
		 <td class="paramlist_value" width="60%">
		<fieldset id="include_log" class="radio btn-group">
  			<input  type="radio" name="include_log" value="1" <?php echo ($JSecureConfig->include_log == 1)? 'checked="checked"':''; ?> id="include_log1" />
  			<label class="btn" for="include_log1">Yes</label>
  			<input type="radio" name="include_log" value="0" <?php echo ($JSecureConfig->include_log == 0)?  'checked="checked"':''; ?> id="include_log0" />
  			<label class="btn" for="include_log0">No</label>
		</fieldset>
          </td>
	</tr>
	<tr id="include_showlogs">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDESHOWLOG_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_SHOWLOG'); ?>
		</span>
		</td>
		 <td class="paramlist_value" width="60%">
		<fieldset id="include_showlogs" class="radio btn-group">
  			<input  type="radio" name="include_showlogs" value="1" <?php echo ($JSecureConfig->include_showlogs == 1)? 'checked="checked"':''; ?> id="include_showlogs1" />
  			<label class="btn" for="include_showlogs1">Yes</label>
  			<input type="radio" name="include_showlogs" value="0" <?php echo ($JSecureConfig->include_showlogs == 0)?  'checked="checked"':''; ?> id="include_showlogs0" />
  			<label class="btn" for="include_showlogs0">No</label>
		</fieldset>
          </td>
	</tr>
	<tr id="include_directorylisting">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDEDIRECTORYLISTING_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_DIRECTORYLISTING'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_directorylisting" class="radio btn-group">
  			<input  type="radio" name="include_directorylisting" value="1" <?php echo ($JSecureConfig->include_directorylisting == 1)? 'checked="checked"':''; ?> id="include_directorylisting1" />
  			<label class="btn" for="include_directorylisting1">Yes</label>
  			<input type="radio" name="include_directorylisting" value="0" <?php echo ($JSecureConfig->include_directorylisting == 0)?  'checked="checked"':''; ?> id="include_directorylisting0" />
  			<label class="btn" for="include_directorylisting0">No</label>
		</fieldset>
          </td>
	</tr>
	<tr id="include_component_protection">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDE_COMPONENT_PROTECTION_DESC'); ?>">
			<?php echo JText::_('INCLUDE_COMPONENT_PROTECTION'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_component_protection" class="radio btn-group">
  			<input  type="radio" name="include_component_protection" value="1" <?php echo ($JSecureConfig->include_component_protection == 1)? 'checked="checked"':''; ?> id="include_component_protection1" />
  			<label class="btn" for="include_component_protection1">Yes</label>
  			<input type="radio" name="include_component_protection" value="0" <?php echo ($JSecureConfig->include_component_protection == 0)?  'checked="checked"':''; ?> id="include_component_protection0" />
  			<label class="btn" for="include_component_protection0">No</label>
		</fieldset>
          </td>
	</tr>
	
	<tr id="include_autobanip">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDE_AUTOBANIP_DESC'); ?>">
			<?php echo JText::_('INCLUDE_AUTOBANIP'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_autobanip" class="radio btn-group">
  			<input  type="radio" name="include_autobanip" value="1" <?php echo ($JSecureConfig->include_autobanip == 1)? 'checked="checked"':''; ?> id="include_autobanip1" />
  			<label class="btn" for="include_autobanip1">Yes</label>
  			<input type="radio" name="include_autobanip" value="0" <?php echo ($JSecureConfig->include_autobanip == 0)?  'checked="checked"':''; ?> id="include_autobanip0" />
  			<label class="btn" for="include_autobanip0">No</label>
		</fieldset>
          </td>
	</tr>
	
	<tr id="include_graph">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDE_GRAPH_DESC'); ?>">
			<?php echo JText::_('INCLUDE_GRAPH'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_component_protection" class="radio btn-group">
  			<input  type="radio" name="include_graph" value="1" <?php echo ($JSecureConfig->include_graph == 1)? 'checked="checked"':''; ?> id="include_graph1" />
  			<label class="btn" for="include_graph1">Yes</label>
  			<input type="radio" name="include_graph" value="0" <?php echo ($JSecureConfig->include_graph == 0)?  'checked="checked"':''; ?> id="include_graph0" />
  			<label class="btn" for="include_graph0">No</label>
		</fieldset>
          </td>
	</tr>
	<tr id="include_adminid">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('ADMIN_ID_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_ADMIN_ID'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_adminid" class="radio btn-group">
  			<input  type="radio" name="include_adminid" value="1" <?php echo ($JSecureConfig->include_adminid == 1)? 'checked="checked"':''; ?> id="include_adminid1" />
  			<label class="btn" for="include_adminid1">Yes</label>
  			<input type="radio" name="include_adminid" value="0" <?php echo ($JSecureConfig->include_adminid == 0)?  'checked="checked"':''; ?> id="include_adminid0" />
  			<label class="btn" for="include_adminid0">No</label>
		</fieldset>
        </td>
	</tr>
	<tr id="include_logincontrol">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('LOGINCNTRL_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_LOGINCNTRL'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_logincontrol" class="radio btn-group">
  			<input  type="radio" name="include_logincontrol" value="1" <?php echo ($JSecureConfig->include_logincontrol == 1)? 'checked="checked"':''; ?> id="include_logincontrol1" />
  			<label class="btn" for="include_logincontrol1">Yes</label>
  			<input type="radio" name="include_logincontrol" value="0" <?php echo ($JSecureConfig->include_logincontrol == 0)?  'checked="checked"':''; ?> id="include_logincontrol0" />
  			<label class="btn" for="include_logincontrol0">No</label>
		</fieldset>
                </td>
	</tr>
	<tr id="include_metatags"> 
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDEMETATAGS_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_METATAGS'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_metatags" class="radio btn-group">
  			<input  type="radio" name="include_metatags" value="1" <?php echo ($JSecureConfig->include_metatags == 1)? 'checked="checked"':''; ?> id="include_metatags1" />
  			<label class="btn" for="include_metatags1">Yes</label>
  			<input type="radio" name="include_metatags" value="0" <?php echo ($JSecureConfig->include_metatags == 0)?  'checked="checked"':''; ?> id="include_metatags0" />
  			<label class="btn" for="include_metatags0">No</label>
		</fieldset>
                </td>
	</tr>
	<tr id="include_purgesessions">
		<td class="paramlist_key" width="40%">
		<span class="editlinktip hasTip" title="<?php echo JText::_('INCLUDE_PURGESESSION_DESCRIPTION'); ?>">
			<?php echo JText::_('INCLUDE_PURGE_SESSION'); ?>
		</span>
		</td>
		<td class="paramlist_value" width="60%">
		<fieldset id="include_purgesessions" class="radio btn-group">
  			<input  type="radio" name="include_purgesessions" value="1" <?php echo ($JSecureConfig->include_purgesessions == 1)? 'checked="checked"':''; ?> id="include_purgesessions1" />
  			<label class="btn" for="include_purgesessions1">Yes</label>
  			<input type="radio" name="include_purgesessions" value="0" <?php echo ($JSecureConfig->include_purgesessions == 0)?  'checked="checked"':''; ?> id="include_purgesessions0" />
  			<label class="btn" for="include_purgesessions0">No</label>
		</fieldset>
                </td>
	</tr>
	</table>
</fieldset>
<input type="hidden" name="task" value="" />
<?php
}
	
?>
<script type="text/javascript">
function doMassSelect(value)
{
	$$('.btn-group>checked').setProperty('checked','');
	$$('.btn-group').setProperty('value',value);
	return false;
}
</script>

<input type="hidden" name="option" value="com_jsecure"/>
</form>