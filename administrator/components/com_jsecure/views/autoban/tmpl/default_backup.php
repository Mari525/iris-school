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
$document = JFactory::getDocument();
$document->addScript(JURI::base()."components/com_jsecure/js/autoban.js");
$document->addScriptDeclaration("abipLising(document.getElementById('abip'));");

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
<form action="index.php?option=com_jsecure" method="post" name="adminForm" id="adminForm" >

<fieldset class="adminform">
	<table class="table table-striped">

	<tr id="ABIP">                    <!-- added by me-->
		<td width="100" class="key"><span class="editlinktip hasTip" title=" <?php echo JText::_('Auto Ban IP'); ?>"><?php echo JText::_('Auto Ban IP'); ?></td>
		<td width="100">
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
	
<script type="text/javascript">
abipElement = document.getElementById('abip');
abipLising(abipElement);
</script>

<input type="hidden" name="option" value="com_jsecure"/>
<input type="hidden" name="controller" value="autoban"/>
<input type="hidden" name="task" value="" />
</fieldset>
</form>
<?php
}
?>