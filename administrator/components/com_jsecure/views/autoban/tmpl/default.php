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
$document = JFactory::getDocument();
$document->addCustomTag('<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/internalpages.css" />');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/validate_license.js"></script>');
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
		var key = '$license_key';
		var subcatid = '$sub_cat_id';
		window.onload = function(){
		showlicense(key,subcatid);
		}
");


$JSecureConfig = $this->JSecureConfig;
$document = JFactory::getDocument();
$document->addScript(JURI::base()."components/com_jsecure/js/autoban.js");
$document->addScript(JURI::base()."components/com_jsecure/js/spamip.js");
$document->addScriptDeclaration("abipLising(document.getElementById('abip'));");
$document->addScriptDeclaration("spamipLising(document.getElementById('spamip'));");
$honeypotapikey = $this->honeypotapikey;

$spamip = $this->spamip;
	jimport('joomla.environment.browser');
    $doc = JFactory::getDocument();
    $app        = JFactory::getApplication();
    $browser = &JBrowser::getInstance();
    $browserType = $browser->getBrowser();
    $browserVersion = $browser->getMajor();
    if(($browserType == 'msie') && ($browserVersion = 7))
    {
    	$document->addScript(JURI::base()."components/com_jsecure/js/tabs.js");
    }
	
	
?>
<?php
if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_autobanip == '1' )
{
 JError::raiseWarning(404, JText::_('NOT_AUTHERIZED')); 
 $link = "index.php?option=com_jsecure";
 $app->redirect($link);
}
else
{
?>
<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />
<script src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>
<script src="components/com_jsecure/js/dashboard_menu.js"></script>
<h3><?php echo JText::_('COM_JSECURE_AUTOBAN');?></h3>
<form action="index.php?option=com_jsecure" onsubmit="return submitbutton();" method="post" name="adminForm" id="adminForm" >

<fieldset class="adminform">
	<table style="table-layout:fixed" class="table table-striped">

	<tr id="ABIP">                    <!-- added by me-->
		<td width="100" class="key"><span class="editlinktip hasTip" title=" <?php echo JText::_('Auto Ban IP'); ?>"><?php echo JText::_('Auto Ban IP'); ?></td>
		<td width="200">
			<select name="abip" id="abip" style="width:100px" onchange="javascript: abipLising(this);">
					<option value="0" <?php echo ($JSecureConfig->abip == 0)?"selected":''; ?>><?php echo JText::_('NO'); ?></option>
					<option value="1" <?php echo ($JSecureConfig->abip == 1)?"selected":''; ?>><?php echo JText::_('YES'); ?></option>
				</select>
		</td>
	</tr>
	<tr id="ABIPLISTING">
		<td width="100" align="left" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_JSEUCRE_AUTOBANIPLIST'); ?>">
			<?php echo JText::_('COM_JSEUCRE_AUTOBANIPLIST'); ?>
			</span>
		</td>
		<td>
			<textarea cols="80" rows="10" class="text_area" type="text" name="ablist" id="ablist"><?php echo $JSecureConfig->ablist; ?></textarea>
		</td>
	</tr>
	<tr id="ABIPDDL">
		<td width="100" align="left" class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('COM_JSEUCRE_TIME_INERVAL_FOR_AUTO_BAN'); ?>"><?php echo JText::_('COM_JSEUCRE_TIME_INERVAL_FOR_AUTO_BAN'); ?>
			</span>
		</td>
		<td width="100">
			<select name="abiplist" id="abiplist" style="width:100px">
				<option value="5" <?php echo ($JSecureConfig->abiplist == 5)?"selected":''; ?>><?php echo "5 Mins"; ?></option>
				<option value="10" <?php echo ($JSecureConfig->abiplist == 10)?"selected":''; ?>><?php echo "10 Mins"; ?></option>
				<option value="20" <?php echo ($JSecureConfig->abiplist == 20)?"selected":''; ?>><?php echo "20 Mins"; ?></option>
				<option value="30" <?php echo ($JSecureConfig->abiplist == 30)?"selected":''; ?>><?php echo "30 Mins"; ?></option>
				<option value="60" <?php echo ($JSecureConfig->abiplist == 60)?"selected":''; ?>><?php echo "1 Hrs"; ?></option>
				<option value="120" <?php echo ($JSecureConfig->abiplist == 120)?"selected":''; ?>><?php echo "2 Hrs"; ?></option>
			</select>
		</td>
	</tr>
	<tr id="ABIPTRY">
		<td width="100" align="left" class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('COM_JSEUCRE_NUMBER_OF_ATTEMPTS'); ?>"><?php echo JText::_('COM_JSEUCRE_NUMBER_OF_ATTEMPTS'); ?>
			</span>
		</td>
		<td width="100">
			<select name="abiptrylist" id="abiptrylist" style="width:100px">
				<option value="5" <?php echo ($JSecureConfig->abiptrylist == 5)?"selected":''; ?>><?php echo "5"; ?></option>
				<option value="10" <?php echo ($JSecureConfig->abiptrylist == 10)?"selected":''; ?>><?php echo "10"; ?></option>
				<option value="20" <?php echo ($JSecureConfig->abiptrylist == 20)?"selected":''; ?>><?php echo "20"; ?></option>
				<option value="30" <?php echo ($JSecureConfig->abiptrylist == 30)?"selected":''; ?>><?php echo "30"; ?></option>
				<option value="40" <?php echo ($JSecureConfig->abiptrylist == 40)?"selected":''; ?>><?php echo "40"; ?></option>
				<option value="50" <?php echo ($JSecureConfig->abiptrylist == 50)?"selected":''; ?>><?php echo "50"; ?></option>
			</select>
		</td>
	</tr>
	</table>
	
	<table style="table-layout:fixed;" class="table table-striped">

	<tr id="SPAMIP">                    <!-- added by me-->
		<td width="100" class="key"><span class="editlinktip hasTip" title=" <?php echo JText::_('Spam Ip'); ?>"><?php echo JText::_('Spam Ip'); ?></td>
		<td width="200">
			<select name="spamip" id="spamip" style="width:100px" onchange="javascript: spamipLising(this);">
					<option value="0" <?php echo ($JSecureConfig->spamip == 0)?"selected":''; ?>><?php echo JText::_('NO'); ?></option>
					<option value="1" <?php echo ($JSecureConfig->spamip == 1)?"selected":''; ?>><?php echo JText::_('YES'); ?></option>
				</select>
		</td>
	</tr>
	<tr id="SPAMIPHONEYPOTKEY">
		<td width="" align="left" class="key">
			<span class="bold hasTip" title="<?php echo JText::_('HONEY_POT_KEY_DESCRIPTION'); ?>"> <?php echo JText::_('HONEY_POT_API_KEY'); ?> </span>
		</td>
		<td>		
		<input type="text" name="honeypotapikey" id="honeypotapikey" value="<?php  echo (isset($honeypotapikey[0]->Value) && $honeypotapikey[0]->Value != '')?  $honeypotapikey[0]->Value : ''; ?>" size="50"/>
		</td>
		
	</tr>
	<tr id="ALLOWEDTHREATRATING">
		<td width="100" align="left" class="key">
			<span class="bold hasTip" title="<?php echo JText::_('ALLOWED_THREAT_RATING_DESCRIPTION'); ?>"> <?php echo JText::_('ALLOWED_THREAT_RATING'); ?> </span>
		</td>
		<td>		
		<input type="text" name="allowedthreatrating" id="allowedthreatrating" value="<?php echo $JSecureConfig->allowedthreatrating; ?>" size="50"/>
		</td>
		
	</tr>
	<tr id="SPAMIPLISTING">
		<td width="100" align="left" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_JSECURE_BLACKLISTEMAIL'); ?>">
			<?php echo JText::_('COM_JSECURE_SPAMIPLIST'); ?>
			</span>
		</td>
		<td>
			<textarea readonly cols="80" rows="10" class="text_area" type="text" name="spamlist" id="spamlist"><?php 

			// print_r($spamip);
			
			for($i=0;$i<count($spamip);$i++){
			
			echo $spamip[$i]->spamip."&#13;&#10;";
						
			}
			
			// foreach($spamip->spamip as $key){
			
			// echo $key;
			
			// }

			?></textarea>
		</td>
	</tr>
	<tr id="USEFULLINKS">
		<td width="100" align="left" class="key">
			<span class="bold hasTip" title="<?php echo JText::_('USEFUL_LINKS_DESCRIPTION'); ?>"> <?php echo JText::_('USEFUL_LINKS'); ?> </span>
		</td>
		<td>		
		<a title="Create a new account on ProjectHoneyPot" href="http://www.projecthoneypot.org/create_account.php" target="_blank">Create an account</a></br><a title="Get the API Access key from ProjectHoneyPot" href="http://www.projecthoneypot.org/httpbl_configure.php" target="_blank">Obtain API Access Key</a>
		</td>
		
	</tr>
	
	</table>
	
<script type="text/javascript">
abipElement = document.getElementById('abip');
abipLising(abipElement);

spamipElement = document.getElementById('spamip');
spamipLising(spamipElement);

</script>

<input type="hidden" name="option" value="com_jsecure"/>
<input type="hidden" name="controller" value="autoban"/>
<input type="hidden" name="task" value="" />
</fieldset>
</form>
<div id="license"></div>

<?php
}
?>