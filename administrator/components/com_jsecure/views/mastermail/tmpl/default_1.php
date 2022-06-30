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
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/mpmail.js"></script>');
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
	

	if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_mastermail == '1' )
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));	
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
		
?>

<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" class="span10">
<h3><?php echo JText::_('EMAIL_MASTER');?></h3>
<fieldset class="adminform">
	<fieldset class="adminform">
      <table class="table table-striped">
        <tr>
          <td class="paramlist_key" width="40%"><span class="editlinktip">
            <label id="paramsshowAllChildren-lbl" for="paramsshowAllChildren" class="hasTip" title="<?php echo JText::_('CONFIGURATION_SEND_MAIL').'::'.JText::_('CONFIGURATION_SEND_MAIL_DESCRIPTION')	; ?>"> <?php echo JText::_('CONFIGURATION_SEND_MAIL'); ?> </label>
            </span> </td>
          <td class="paramlist_value" width="60%">
		  <!--[if lt IE 9]>
            <fieldset id="mpsendemail" class="radio btn-group-id">
            <input type="radio" name="mpsendemail" value="0" <?php echo ($JSecureConfig->mpsendemail == 0)? 'checked="checked"':''; ?> id="mpsendemail0" />
            <label class="btn" for="mpsendemail0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
            <input type="radio" name="mpsendemail" value="1" <?php echo ($JSecureConfig->mpsendemail == 1)? 'checked="checked"':''; ?> id="mpsendemail1" />
            <label class="btn" for="mpsendemail1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
            </fieldset>
   <![endif]-->
   
   <!--[if IE 9]>
            <fieldset id="mpsendemail" class="radio btn-group">
            <input type="radio" name="mpsendemail" value="0" <?php echo ($JSecureConfig->mpsendemail == 0)? 'checked="checked"':''; ?> id="mpsendemail0" />
            <label class="btn" for="mpsendemail0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
            <input type="radio" name="mpsendemail" value="1" <?php echo ($JSecureConfig->mpsendemail == 1)? 'checked="checked"':''; ?> id="mpsendemail1" />
            <label class="btn" for="mpsendemail1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
            </fieldset>
   <![endif]-->
   
   <![if !IE]>
            <fieldset id="mpsendemail" class="radio btn-group">
            <input type="radio" name="mpsendemail" value="0" <?php echo ($JSecureConfig->mpsendemail == 0)? 'checked="checked"':''; ?> id="mpsendemail0" />
            <label class="btn" for="mpsendemail0"><?php echo JText::_('COM_JSECURE_NO'); ?></label>
            <input type="radio" name="mpsendemail" value="1" <?php echo ($JSecureConfig->mpsendemail == 1)? 'checked="checked"':''; ?> id="mpsendemail1" />
            <label class="btn" for="mpsendemail1"><?php echo JText::_('COM_JSECURE_YES'); ?></label>
            </fieldset>
   <![endif]>
			</td>
        </tr>
        <tr id="mpemailsubject">
          <td class="paramlist_key" width="40%"><span class="editlinktip">
            <label id="paramsshowAllChildren-lbl" for="paramsshowAllChildren" class="hasTip" title="<?php echo JText::_('CONFIGURATION_EMAIL_SUBJECT').'::'.JText::_('CONFIGURATION_EMAIL_SUBJECT_DESCRIPTION'); ?>"> <?php echo JText::_('CONFIGURATION_EMAIL_SUBJECT'); ?> </label>
            </span> </td>
          <td class="paramlist_value" width="60%"><input name="mpemailsubject" type="text" value="<?php echo $JSecureConfig->mpemailsubject; ?>" size="50" />
          </td>
        </tr>
        <tr id="mpemailid">
          <td class="paramlist_key" width="40%"><span class="editlinktip">
            <label id="paramsshowAllChildren-lbl" for="paramsshowAllChildren" class="hasTip" title="<?php echo JText::_('CONFIGURATION_EMAIL_ID').'::'.JText::_('CONFIGURATION_EMAIL_ID_DESCRIPTION'); ?>"> <?php echo JText::_('CONFIGURATION_EMAIL_ID'); ?> </label>
            </span> </td>
          <td class="paramlist_value" width="60%"><input name="mpemailid" type="text" value="<?php echo $JSecureConfig->mpemailid; ?>" size="50" />
          </td>
        </tr>
      </table>
      </fieldset>
    </div>
</fieldset>
<input type="hidden" name="task" value="" />
<script type="text/javascript">
checkMPMailStatus(document.getElementById('mpsendemail'));
</script>
<?php
}
?>

<input type="hidden" name="option" value="com_jsecure"/>
</form>