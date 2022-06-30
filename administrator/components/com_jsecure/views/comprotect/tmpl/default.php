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
//JHTML::_('behavior.mootools');
//JHTML::_('script','system/modal.js', false, true);
//JHTML::_('stylesheet','system/modal.css', array(), true);

$document = JFactory::getDocument();
$document->addCustomTag('<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/internalpages.css" />');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/validate_license.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/comprotect.js"></script>');
$document->addScriptDeclaration("window.addEvent
		var key = '$license_key';
		var subcatid = '$sub_cat_id';
		window.onload = function(){
		showlicense(key,subcatid);
		}
");
$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	 = $basepath.'/params.php';
		
		require_once($configFile);
		$app        = JFactory::getApplication();
		$JSecureConfig = new JSecureConfig();

if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_component_protection == '1' )
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);	

}
else{

?>
<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />
<script src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>
<script>
	jQuery(window).load(function(){
		$window_height = jQuery(window).innerHeight();
		$subhead_height = jQuery('.subhead').innerHeight();
		$sidebar_scroll_height = $window_height - $subhead_height - 30;
		jQuery('.container-fluid.container-main .span2').css('height',$sidebar_scroll_height);
		
		jQuery(".container-fluid.container-main .span2").mCustomScrollbar({
			autoHideScrollbar:true
		});
	});
	jQuery(window).scroll(function() {
		if(jQuery('.desktopview .subhead-collapse.collapse > .subhead').hasClass('subhead-fixed')){
			jQuery('.desktopview .container-fluid.container-main .span2').addClass('fixedsidebar');
			
		} else {
			jQuery('.container-fluid.container-main .span2').removeClass('fixedsidebar');
		}
	});
</script>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" autocomplete="off" id="adminForm">
<table class="table table-striped" cellspacing="1">
<thead>
	<tr><th colspan=6 style='text-align:left;'><h1><?php echo JText::_( 'Component Protection' ); ?></h1></th></tr>
</thead>
<thead>
	<tr>
		<th width="5">
			<?php echo JText::_( 'Num' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'Component Name' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'Enable Protection' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'Password' ); ?>
		</th>
	</tr>
</thead>
<tbody>
	<?php
	$password = $this->password;
	$i=0;$k = 0;
	foreach($this->components as $row){
		if($row->name != 'jsecure'){
		$enabled = 0;
		foreach($password as $pass){
         if($pass->com_id == $row->extension_id and $pass->status == 1)
           $enabled = 1;
		}

	?>
	<tr class="<?php echo "row$k"; ?>">
		<td>
			<?php echo   $i+1; ?>
		</td>
		<td align="left">
				<?php 
		
	$name = str_replace('com_','',$row->name);
	$name = ucfirst($name); 
	echo JText::_($name); ?>
			</a>
		</td>	
		<td align="left">

		<?php	echo $list['status']  = JHTML::_( 'select.genericlist', $this->status, "component[$row->extension_id][status]",'class="droplist" style="width:40.0552%;"','id', 'title', $enabled );
		//echo $enabled;
		?>
		</td>
		<td align="left">
			<?php  echo '<input type="password" name="component['.$row->extension_id.'][key]" value="" size="50" />';?>

		</td>
		
	</tr>
	<?php
		$k = 1 - $k;	$i++;
		}
	}
	?>
</tbody>
</table>

<input type="hidden" name="option" value="com_jsecure" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
</form>
<div id="license"></div>
<?php
}
?>

