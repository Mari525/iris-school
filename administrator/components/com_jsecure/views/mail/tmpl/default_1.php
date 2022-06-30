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
$JSecureConfig = $this->JSecureConfig;
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
	$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/mail.js"></script>');	
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
	
	
	if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_mail == '1' )
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));	
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
	
?>

<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" class="span10">
<h3><?php echo JText::_('MAIL_CONFIG');?></h3>
<fieldset class="adminform">
	 <fieldset class="adminform">
      <table class="table table-striped">
        <tr>
          <td class="paramlist_key" width="40%"><span class="editlinktip hasTip" title="Enable">
            <label id="paramsshowAllChildren-lbl" for="paramsshowAllChildren" class="hasTip" title="<?php echo JText::_('SEND_MAIL').'::'.JText::_('SEND_MAIL_DESCRIPTION'); ?>"> <?php echo JText::_('SEND_MAIL'); ?> </label>
            </span> </td>
          <td class="paramlist_value" width="60%">
						   <!--[if lt IE 9]>
					 <fieldset id="sendemail234" class="radio btn-group-id">
					<input type="radio" name="sendemail" value="0" <?php echo ($JSecureConfig->sendemail==0)? 'checked="checked"':''; ?> id="sendemail0" />
					<label class="btn" for="sendemail0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
					<input type="radio" name="sendemail" value="1" <?php echo ($JSecureConfig->sendemail==1)? 'checked="checked"':''; ?> id="sendemail1" />
					<label class="btn" for="sendemail1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
					</fieldset>
				   <![endif]-->
				   
				   <!--[if IE 9]>
					<fieldset id="sendemail234" class="radio btn-group">
					<input type="radio" name="sendemail" value="0" <?php echo ($JSecureConfig->sendemail==0)? 'checked="checked"':''; ?> id="sendemail0" />
					<label class="btn" for="sendemail0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
					<input type="radio" name="sendemail" value="1" <?php echo ($JSecureConfig->sendemail==1)? 'checked="checked"':''; ?> id="sendemail1" />
					<label class="btn" for="sendemail1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
					</fieldset>
				   <![endif]-->
				   
				   <![if !IE]>
					 <fieldset id="sendemail234" class="radio btn-group">
					<input type="radio" name="sendemail" value="0" <?php echo ($JSecureConfig->sendemail==0)? 'checked="checked"':''; ?> id="sendemail0" />
					<label class="btn" for="sendemail0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
					<input type="radio" name="sendemail" value="1" <?php echo ($JSecureConfig->sendemail==1)? 'checked="checked"':''; ?> id="sendemail1" />
					<label class="btn" for="sendemail1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
					</fieldset>
				   <![endif]>
			</td>
        </tr>
        <tr id="sendemaildetails">
          <td class="paramlist_key" width="40%"><span class="editlinktip">
            <label id="paramsshowAllChildren-lbl" for="paramsshowAllChildren" class="hasTip" title="<?php echo JText::_('SEND_MAIL_DETAILS').'::'.JText::_('SEND_MAIL_DETAILS_DESCRIPTION'); ?>"> <?php echo JText::_('SEND_MAIL_DETAILS'); ?> </label>
            </span> </td>
          <td class="paramlist_value" width="60%"><select name="sendemaildetails" class="input-medium chzn-done">
              <option value="1" <?php echo ($JSecureConfig->sendemaildetails == 1)?"selected":''; ?>><?php echo JText::_('SEND_CORRECT_KEY'); ?></option>
              <option value="2" <?php echo ($JSecureConfig->sendemaildetails == 2)?"selected":''; ?>><?php echo JText::_('SEND_WRONG_KEY'); ?></option>
              <option value="3" <?php echo ($JSecureConfig->sendemaildetails == 3)?"selected":''; ?>><?php echo JText::_('SEND_BOTH'); ?></option>
            </select>
          </td>
        </tr>
        <tr id="emailid">
          <td class="paramlist_key" width="40%"><span class="editlinktip">
            <label id="paramsshowAllChildren-lbl" for="paramsshowAllChildren" class="hasTip" title="<?php echo JText::_('EMAIL_ID').'::'.JText::_('EMAIL_ID_DESCRIPTION'); ?>"> <?php echo JText::_('EMAIL_ID'); ?> </label>
            </span> </td>
          <td class="paramlist_value" width="60%"><input name="emailid" type="text" value="<?php echo $JSecureConfig->emailid; ?>" size="50" />
          </td>
        </tr>
        <tr id="emailsubject">
          <td class="paramlist_key" width="40%"><span class="editlinktip">
            <label id="paramsshowAllChildren-lbl" for="paramsshowAllChildren" class="hasTip" title="<?php echo JText::_('EMAIL_SUBJECT').'::'.JText::_('EMAIL_SUBJECT_DESCRIPTION'); ?>"> <?php echo JText::_('EMAIL_SUBJECT'); ?> </label>
            </span> </td>
          <td class="paramlist_value" width="60%"><input name="emailsubject" type="text" value="<?php echo $JSecureConfig->emailsubject; ?>" size="50" />
          </td>
        </tr>
      </table>
      </fieldset>
</fieldset>
<input type="hidden" name="task" value="" />

<?php
}
?>
<input type="hidden" name="option" value="com_jsecure"/>
</form>