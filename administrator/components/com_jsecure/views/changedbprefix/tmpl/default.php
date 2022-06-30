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
JHtml::_('behavior.multiselect');

$app      = JFactory::getApplication();
$JSecureConfig = $this->JSecureConfig;
$document = JFactory::getDocument();
$document->addCustomTag('<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/internalpages.css" />');
$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/modern_jquery.mCustomScrollbar.css");
//$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/styles.css");
/*$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/bootstrap.min.css");*/
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/validate_license.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/jquery.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/scrollspy.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/changedbprefix.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>');
//$document->addCustomTag('<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />');

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
//$controller = new jsecureControllerjsecure();

$current_db_prefix = $this->table_prefix;

$message = $_GET['msg'];

$message = $this->encryptor('decrypt',$message);

$controller = new jsecureControllerjsecure();
//print_r($JSecureConfig);die;
if (!$controller->isMasterLogged() and $JSecureConfig->enableMasterPassword == '1'and $JSecureConfig->include_change_db_prefix == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
?>


<script type="text/javascript"> 

function stopRKey(evt) { 
  var evt = (evt) ? evt : ((event) ? event : null); 
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
} 

document.onkeypress = stopRKey; 



</script>

<body>
 <link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />
<script src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>
<script src="components/com_jsecure/js/dashboard_menu.js"></script>
<h3><?php echo JText::_('COM_CHANGE_DB_PREFIX');?></h3>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="button2.disabled=true;button1.disabled=true;return submitbutton();" id="adminForm" autocomplete="off">
<!--<h3 style="margin-left:5px"><?php echo JText::_('COM_CHANGE_DB_PREFIX');?></h3>-->
  <table style="table-layout:fixed;" class="table table-striped">
	<tr>
	<td><?php echo JText::_('CURRENT_DB_PREFIX'); ?></td>
	<td><?php echo $current_db_prefix;?></td>
	
	
	</tr>
	
	<tr>
	<td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('DB_PREFIX_DESCRIPTION'); ?>"><?php echo JText::_('DB_PREFIX'); ?></span> </td>
	<td>
		<input id="db_prefix" name="db_prefix" type="text" value="" size="50" />	
	</td>
	
	</tr>
	
	<tr>
    <td><?php echo JText::_('GENERATE_RANDOM_PREFIX');;?></td>
    <td>
    <input type="checkbox" name="generateKey" id="generateKey" value="1" onchange="return CheckCheckboxes(this)" />
    </td>
    </tr>
	
	<tr>
	<td>
	
	</td>
	<td></td>
	</tr>
	
	<tr>
	<td class="paramlist_key"><input type="button" name="button1" class="btn btn-info scanbtn" value="Database Backup" id="button1" onclick="return dbbackup();"/></td>	
	<td class="paramlist_key"></td>	
	</tr>
	
	<tr>
	<td class="paramlist_key"><input type="submit" name="button2" class="btn btn-info scanbtn" value="Change Database Prefix" id="button2" onclick="return getdbprefix(this.value);" /></td>
	<td class="paramlist_key"></td>
	</tr>
	  
  </table>
   	 
  <input type="hidden" name="option" value="com_jsecure"/>
  <input type="hidden" id="task" name="task" value="" />
   <?php echo $message; ?>
</form>
<div id="license"></div>
</body>
<?php

//echo $message;

function encryptor($action,$string) {
	
	
    $output = false;

    $encrypt_method = "AES-256-CBC";
    //pls set your unique hashing key
    $secret_key = 'muni';
    $secret_iv = 'muni123';

    // hash
    $key = hash('sha256', $secret_key);
	
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
			
    //do the encyption given text/string/number
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    		
	}
    else if( $action == 'decrypt' ){
    	//decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
	}

}
?>