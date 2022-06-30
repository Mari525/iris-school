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
// JHtml::_('behavior.multiselect');
$licensekey = $this->licensekey;

$app      = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/validate_license.js"></script>');
$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/modern_jquery.mCustomScrollbar.css");
$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/styles.css");
/*$document->addStyleSheet(JURI::base() . "components/com_jsecure/css/bootstrap.min.css");*/
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/jquery.js"></script>');
// $document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/scrollspy.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/license.js"></script>');
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
		var key = '$license_key';
		var subcatid = '$sub_cat_id';
		window.onload = function(){
		showlicense(key,subcatid);
		}
");

?>
<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />
<script src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>
<script src="components/com_jsecure/js/dashboard_menu.js"></script>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" id="adminForm" autocomplete="off">
<table class="table table-striped" cellspacing="1">
<thead>
<tr><th>
<?php echo JText::_( 'License' ); ?>
</th></tr>
</thead>
<tbody>
<tr>

<td><?php echo JText::_( 'License Key' );?>
</td>
<td>
<input type="text" id="license_key" name="license_key" value="<?php echo preg_replace('/[[:^print:]]/','',$licensekey); ?>" />
</td>
</tr>
</tbody>
</table>


<input type="hidden" name="option" value="com_jsecure"/>
<input type="hidden" id= "task" name="task" value="savelicense"/>
</form>
<div id="license"></div>
