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
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
$app        = JFactory::getApplication();
$document = JFactory::getDocument();


$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/emailcheck.js"></script>');
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
if (!$controller->isMasterLogged() and $JSecureConfig->enableMasterPassword == '1'and $JSecureConfig->include_email_scan == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
?>
<body onload="init();">

<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" id="adminForm" autocomplete="off" class="span10">
<h3><?php echo JText::_('EMAIL_CHECK_CONFIGURATION');?></h3>
     <fieldset class="adminform">
      <table style="table-layout:fixed;" class="table table-striped">
        <tr>
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('ENABLE')."::".JText::_('PUBLISHED_EMAIL_DESCRIPTION');?>"> <?php echo JText::_('ENABLE'); ?> </span> </td>
          <td class="paramlist_value">
		<fieldset id="jform_home" class="radio btn-group">
  			<input onchange="javascript:spamemaillisting(this);" type="radio" name="publishemailcheck" value="1" <?php echo ($JSecureConfig->publishemailcheck == 1)? 'checked="checked"':''; ?> id="publishemailcheck1" />
  			<label class="btn" for="publishemailcheck1">Yes</label>
  			<input onchange="javascript:spamemaillisting(this);" type="radio" name="publishemailcheck" value="0" <?php echo ($JSecureConfig->publishemailcheck == 0)?  'checked="checked"':''; ?> id="publishemailcheck0" />
  			<label class="btn" for="publishemailcheck0">No</label>
		</fieldset>
          </td>
        </tr>
		
	<!--	
		<tr>
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('PUBLISHED_EMAIL_DESCRIPTION');?>"> <?php echo JText::_('Perform Email Check'); ?> </span> </td>
          <td class="paramlist_value">
		<fieldset id="jform_home" class="radio btn-group">
  			<input  type="radio" name="publishevent" value="1" <?php echo ($JSecureConfig->publishevent == 1)? 'checked="checked"':''; ?> id="publishevent1" />
  			<label class="btn" for="publishevent1">User Register</label>
  			<input type="radio" name="publishevent" value="0" <?php echo ($JSecureConfig->publishevent == 0)?  'checked="checked"':''; ?> id="publishevent0" />
  			<label class="btn" for="publishevent0">User Register + User Login</label>
		</fieldset>
          </td>
        </tr>
	-->	
		<tr id="BLACKLISTEMAILLISTING">
		<td width="100" align="left" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_JSECURE_BLACKLISTEMAIL'); ?>">
			<?php echo JText::_('COM_JSECURE_BLACKLISTEMAILLIST'); ?>
			</span>
		</td>
		<td>
			<textarea cols="80" rows="10" class="text_area" type="text" name="blacklistemail" id="blacklistemail"><?php echo $JSecureConfig->blacklistemail;?></textarea>
		</td>
		</tr>
		
		<tr id="stopforumspamdiv">
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('STOP_FORUM_SPAM_DESCRIPTION');?>"> <?php echo JText::_('STOP_FORUM_SPAM'); ?> </span> </td>
          <td class="paramlist_value">
		<fieldset id="jform_home" class="radio btn-group">
  			<input onchange="javascript:forumfrequencyreadonly();" type="radio" name="publishforumcheck" value="1" <?php echo ($JSecureConfig->publishforumcheck == 1)? 'checked="checked"':''; ?> id="publishforumcheck1" />
  			<label class="btn" for="publishforumcheck1">Yes</label>
  			<input onchange="javascript:forumfrequencyreadonly();" type="radio" name="publishforumcheck" value="0" <?php echo ($JSecureConfig->publishforumcheck == 0)?  'checked="checked"':''; ?> id="publishforumcheck0" />
  			<label class="btn" for="publishforumcheck0">No</label>
		</fieldset>
          </td>
        </tr>
		
		<tr id="stopforumspamfrequency">
		<td width="100" align="left" class="key">
			<span class="bold hasTip" title="<?php echo JText::_('STOPFORUMSPAM_FREQUENCY_DESCRIPTION'); ?>"> <?php echo JText::_('STOPFORUMSPAM_FREQUENCY'); ?> </span>
		</td>
		<td>		
		<input type="text" name="forumfrequency" maxlength="10" id="forumfrequency" value="<?php echo $JSecureConfig->forumfrequency;?>" size="50"/>
		</td>
		
	</tr>
	
	<tr id="saveviewlogoptiondiv">
          <td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('SAVE_SPAM_LOGS_IN_DB');?>"> <?php echo JText::_('LOG_BLOCKED_EMAIL_TO_DB'); ?> </span> </td>
          <td class="paramlist_value">
		<fieldset id="jform_home" class="radio btn-group">
  			<input  type="radio" name="publishlogdb" value="1" <?php echo ($JSecureConfig->publishlogdb == 1)? 'checked="checked"':''; ?> id="publishlogdb1" />
  			<label class="btn" for="publishlogdb1">Yes</label>
  			<input type="radio" name="publishlogdb" value="0" <?php echo ($JSecureConfig->publishlogdb == 0)?  'checked="checked"':''; ?> id="publishlogdb0" />
  			<label class="btn" for="publishlogdb0">No</label>
		</fieldset>
          </td>
       </tr>
	
	<tr id="viewlogdiv">
     <td class="paramlist_key">
     <span class="bold hasTip" title="<?php echo JText::_('Spam Logs');?>"> <?php echo JText::_('Spam Email Logs'); ?></span></td>
     <td class="paramlist_value">
     <a href="<?php echo JURI::base(); ?>index.php?option=com_jsecure&task=emaillog"/>
    View</a>
    
    
    </td>
    </tr>
			
		
		 
		
      </table>
      </fieldset>
  <input type="hidden" name="option" value="com_jsecure"/>
  <input type="hidden" name="task" value="saveEmailCheck" />
  <input name="sendemail" type="hidden" value="<?php echo $JSecureConfig->sendemail; ?>" size="50" />
</form>
</body>
<?php
}
?>