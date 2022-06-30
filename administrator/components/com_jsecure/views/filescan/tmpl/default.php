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
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/filescan.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>');
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

$results = $this->results;



if (!$controller->isMasterLogged() and $JSecureConfig->enableMasterPassword == '1'and $JSecureConfig->include_email_scan == '1')
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
?>
<body>


<!--<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>-->
<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />
<script src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>
<script src="components/com_jsecure/js/dashboard_menu.js"></script>
<h3><?php echo JText::_('File Scan');?></h3>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" onsubmit="return submitbutton();" id="adminForm" autocomplete="off" class="span10">
<h3 style="margin-left:5px"><?php echo JText::_('COM_FILESCAN');?></h3>
  <table style="table-layout:fixed;" class="table table-striped">
  
	<tr>
	<td class="paramlist_key"><div class="scan_content"><p><img src='components/com_jsecure/images/fire.png'/>Protect your Website Data.</p><p><img src='components/com_jsecure/images/spider.png'/>Scan your website to know files modified by Hackers.</p><p><img src='components/com_jsecure/images/shield.png'/>Get to know List of files Added or Deleted in your website by Hackers.</p><div id="loader" class="loader"></div><input type="button" name="button" class="btn btn-info scanbtn1" value="Start File Scan" id="1" onclick="return startscanner()"/></div>
	</td>
	</tr>
	
	
	<tr>
	<td>
	<!-- <div id="myProgress">
		<div id="myBar">
			<div id="label">0%</div>
		</div>
	</div>  -->
		
	</td>
	
	</tr>
	
	
	<tr>
	<td>
	
	<?php
	
	if ($results) {
            $report = '<div class="filescanhead">Scan Results - For '.$results['current_scan']."</div>\r\n\r\n";
            unset ($results['current_scan']);
						$count = count($results['Changed']) + count($results['Added']) + count($results['Deleted']);
            
			$report .='<div class="parent_div">';
			
			foreach ($results as $key => $entries) {
			
				if($key == 'Changed'){$class = 'changeclass';}
				if($key == 'Added'){$class = 'addedclass';}
				if($key == 'Deleted'){$class = 'deletedclass';}
			
			
                $report .=  '<div class="filescancontent '.$class.'">'.$key.'</div>'."\r\n\r\n";
				
				if($key == 'Changed'){$filesclass = 'changefilesclass'; $image = JURI::base().'/components/com_jsecure/images/changed.png';}
				if($key == 'Added'){$filesclass = 'addedfilesclass'; $image = JURI::base().'/components/com_jsecure/images/added.png';}
				if($key == 'Deleted'){$filesclass = 'deletedfilesclass'; $image = JURI::base().'/components/com_jsecure/images/deleted.png';}
				
				$report .= '<div class="'.$filesclass.'">';
                foreach ($entries as $entry) $report .= '<img src="'.$image.'"/> '.$entry."</br>";
                $report .= "\r\n";
				$report .= '</div>';
            }
			 
			$report .= '</div>';
			
            echo '<DIV>'.$report.'</DIV>';	
			
			}
	
	?>
	
	
	
	
	</td>
	</tr>
	  
  </table>
   	 
  <input type="hidden" name="option" value="com_jsecure"/>
  <input type="hidden" id="task" name="task" value="" />
   
</form>

</body>
<?php
}
?>