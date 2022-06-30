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
$document = JFactory::getDocument();
$document->addScript(JURI::base()."components/com_jsecure/js/auth.js");
$name = $this->name;
$data = $this->display;
$compname = $name[0]->name;
$compname = str_replace('com_','',$compname);
$compname = ucfirst($compname);
?>

<style>
div#toolbar-box div.m, div#toolbar-box div.t, div#toolbar-box div.b
{
display:none;
}

form[name="adminForm"] { 
font-size:12px;
}

h2.title {
    border-bottom: 1px solid #CCCCCC;
    display: block;
    font-family: arial;
    font-size: 15px;
    font-weight: bold;
    margin: 0 0 14px;
    padding: 0 0 10px;	
}

form[name="adminForm"] input[type="submit"] {
   background: none repeat scroll 0 0 #146295;
    border: medium none;
    border-radius: 4px 4px 4px 4px;
    color: #FFFFFF;
    cursor: pointer;
    padding: 6px 18px;
}

.radiusBox {
border:1px solid #ccc;
padding:8px;
border-radius:7px;	
}

.head-top { 
padding:10px;
border:1px solid #cdcdcd;
border-radius:8px;
}
</style>

<form method="post" name="adminForm" autocomplete="off">
	<table class="" cellspacing="0" width="100%">
     <tr>
	<td class="radiusBox" style="display:block">
    	<table border="0">
<tr>
    <td><img src="<?php echo JURI::base();?>components/com_jsecure/images/jSecure_icon_48x48.png" style="margin:0 5px 0 0" /></td>
    <td><h1 class="title"><?php echo JText::_($compname.' is protected'); ?></h1></td>
  </tr>
</table>

    </td>
  </tr>
	<tr>
    	<td>
        <table border="0" style="margin:13px 0 0 0;">
 <tr>
			<td><big style="padding:0 15px 0 0"><?php echo JText::_('Please enter the key for '.$compname); ?></big></td>
			<td><input type="password" name="component_password" class="textarea" value="" size="50" /></td>
		</tr>
        <tr><td colspan="2" height="4px"></td></tr>
        <tr>
        	<td></td>
            <td ><input type="submit" name="" value="<?php echo JText::_('JSECURE_LOGIN'); ?>" /></td>
        </tr>
</table>

        </td>
    </tr>
			

	</table>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	    <input type="hidden" name="option" value="com_jsecure" />
		 <input type="hidden" name="view" value="componentform" />
	    <input type="hidden" name="task" value="checkCompassword" />
        <input type="hidden" name="id" value="<?php echo $name[0]->extension_id;?>" />
        <input type="hidden" name="name" value="<?php echo $name[0]->element;?>" />
        <input type="hidden" name="" value="" />
</form>