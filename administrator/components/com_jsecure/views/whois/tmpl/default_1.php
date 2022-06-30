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
$whoisinfo = $this->whoisinfo;


JHtml::_('behavior.framework', true);
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
JHtml::_('behavior.multiselect');
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';

$app      = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/modern_jquery.mCustomScrollbar.css");
$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/styles.css");
/*$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/bootstrap.min.css");*/
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/jquery.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/scrollspy.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/whois.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>');
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
?>
<script type="text/javascript"> 

function stopRKey(evt) { 
  var evt = (evt) ? evt : ((event) ? event : null); 
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
} 

document.onkeypress = stopRKey; 

</script>
<?php
$controller = new jsecureControllerjsecure();

if (!$controller->isMasterLogged() and $JSecureConfig->enableMasterPassword == '1'and $JSecureConfig->include_email_scan == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
?>
<body>
<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" id="adminForm" autocomplete="off" class="span10">
	<h3 style="margin-left:5px"><?php echo JText::_('COM_JSECURE_WHOIS');?></h3>
  <table style="table-layout:fixed;" class="table table-striped">
  
	<tr>
	<td class="paramlist_key"><span class="bold hasTip" title="<?php echo JText::_('WHOIS_DOMAIN').'::'.JText::_('WHOIS_DOMAIN_DESCRIPTION'); ?>"> <?php echo JText::_('WHOIS_DOMAIN'); ?> </span> </td>
	<td>
		www.<input id="domain_lookup" name="domain_lookup" type="text" value="" size="50" />	
	</td>
	
	</tr>
	
	<tr>
	<td class="paramlist_key"><input type="button" name="button" class="btn btn-info scanbtn" value="Submit" id="1" onclick="return getwhoisinfo()"/>
	</td>
	<td></td>
	</tr>
	  
  </table>
   	 
  <input type="hidden" name="option" value="com_jsecure"/>
  <input type="hidden" id="task" name="task" value="" />
   
   <?php if($whoisinfo){?>

	<div>
	<?php 
	echo '<pre>';
	echo '<font size="3">'.$whoisinfo.'</font>';
	}
	?>
	
	</div>
</body>
<?php
}
?>
   
</form>

