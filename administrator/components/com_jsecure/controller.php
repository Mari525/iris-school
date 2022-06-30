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
 * @version     $Id: controller.php  $
 */
	
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
error_reporting(E_ALL & ~E_STRICT);
?>
<?php
$document = JFactory::getDocument();
//echo "<div class='sidepanel-overlay'></div>";
//echo "<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'></script>";
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/jquery.min.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/auth_dashboard.js"></script>');

jimport('joomla.application.component.controller');
jimport('joomla.application.component.helper');
require_once(JPATH_COMPONENT_ADMINISTRATOR .'/models/jsecurelog.php');
$task= JRequest::getVar( 'task', '', '', 'string', JREQUEST_ALLOWRAW );
if ($task == '' || $task == 'auth' || $task == 'cancel') {
	JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);

	
}
if ($task == 'license') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',true);
	
}
if ($task == 'basic') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);

	
}

if ($task == 'autoban' ) {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',true);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
}

if ($task == 'ip') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',true);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);

	
}

if ($task == 'masterpwd') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',true);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);

	
}
if ($task == 'logincontrol') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',true);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);

	
}
if ($task == 'pwdprotect') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
}

if ($task == 'countryblock'||$task == 'countrylog') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',true);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
}

if ($task == 'changedbprefix') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
	
} 


if ($task == 'imageSecure' || $task == 'applyimagesecure') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
	
}
if ($task == 'userkey' || $task == 'addkey' || $task == 'editkey') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
	
}

if ($task == 'comprotect') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
	
} 
if ($task == 'whois') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
} 

if ($task == 'emailcheck'||$task == 'emaillog'){
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
}

if ($task == 'directory') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',true);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
}

if ($task == 'mail') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',true);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
}
if ($task == 'mastermail') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',true);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
	
}
if ($task == 'keeplog') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
	
}

if ($task == 'log') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',true);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
}
if ($task == 'metatags') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);

}
if ($task == 'graph') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',true);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',false);
} 
if ($task == 'help') {
    JSubMenuHelper::addEntry(JText::_(''), '#',false);
	//JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LICENSE'), 'index.php?option=com_jsecure&task=license',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_BASIC'), 'index.php?option=com_jsecure&task=basic',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_AUTOBAN'), 'index.php?option=com_jsecure&task=autoban',false);
    JSubMenuHelper::addEntry(JText::_('IP_CONFIG'), 'index.php?option=com_jsecure&task=ip',false);
    JSubMenuHelper::addEntry(JText::_('MASTER_PASSWORD'), 'index.php?option=com_jsecure&task=masterpwd',false);
    JSubMenuHelper::addEntry(JText::_('LOGIN_CONTROL'), 'index.php?option=com_jsecure&task=logincontrol',false);
    JSubMenuHelper::addEntry(JText::_('ADMIN_PASSWORD_PROT'), 'index.php?option=com_jsecure&task=pwdprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_COUNTRY_BLOCK'), 'index.php?option=com_jsecure&task=countryblock',false);
    JSubMenuHelper::addEntry(JText::_('COM_CHANGE_DB_PREFIX'), 'index.php?option=com_jsecure&task=changedbprefix',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_IMAGE_SECURE'), 'index.php?option=com_jsecure&task=imageSecure',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_USERKEY'), 'index.php?option=com_jsecure&task=userkey',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_PROTECT'), 'index.php?option=com_jsecure&task=comprotect',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_WHOIS'), 'index.php?option=com_jsecure&task=whois',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_EMAIL_CHECK'), 'index.php?option=com_jsecure&task=emailcheck',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_DIRECTORIES'), 'index.php?option=com_jsecure&task=directory',false);
    JSubMenuHelper::addEntry(JText::_('MAIL_CONFIG'), 'index.php?option=com_jsecure&task=mail',false);
    JSubMenuHelper::addEntry(JText::_('EMAIL_MASTER'), 'index.php?option=com_jsecure&task=mastermail',false);
    JSubMenuHelper::addEntry(JText::_('LOG'), 'index.php?option=com_jsecure&task=keeplog',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_LOG'), 'index.php?option=com_jsecure&task=log',false);
    JSubMenuHelper::addEntry(JText::_('META_TAG_CONTROL'), 'index.php?option=com_jsecure&task=metatags',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_GRAPH'), 'index.php?option=com_jsecure&task=graph',false);
    JSubMenuHelper::addEntry(JText::_('COM_JSECURE_HELP'), 'index.php?option=com_jsecure&task=help',true);
	
}

class jsecureControllerjsecure extends JControllerLegacy
 {
	function display(){
		jimport('joomla.filesystem.file');	
		
	  	$view   = $this->getView('auth','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->display();
	}

	function basic(){
		
		jimport('joomla.filesystem.file');	
	
	  	$view   = $this->getView('basic','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->display();
	}
	
	function emailcheck(){
		
		jimport('joomla.filesystem.file');	
	
	  	$view   = $this->getView('emailcheck','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->display();
	}
	
	function emaillog(){
	
        jimport('joomla.filesystem.file');    
    
        $view   = $this->getView('emailcheck','html');
        $model     = $this->getModel( 'jsecurelog' );
        $view->setModel($model);
        $view->setLayout("logs");
        $view->emaillog();
        
    
    }


	function remove(){
				
			jimport('joomla.filesystem.file');    
		
			$view   = $this->getView('emailcheck','html');
			$model     = $this->getModel( 'jsecurelog' );
			$view->setModel($model);
			$view->setLayout("logs");
			$view->delete();
		}
		
	function cancelspamlog(){
    
		 jimport('joomla.filesystem.file');    
	   
		 $this->setRedirect('index.php?option=com_jsecure&task=emailcheck',$msg);
     
    }


	function directory(){
	  	$view   = $this->getView('directory','html');
	 	$view->display();
	}
	
	function advanced(){
		$view   = $this->getView('advanced','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->display();
	}
	
	function autoban(){                          //added by me
		$view   = $this->getView('autoban','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->display();
	}

	function Componentform(){
		$view   = $this->getView('componentform','html');
	 	$view->display();
	}

   function mail(){
		$view   = $this->getView('mail','html');
	 	$view->display();
	}
function ip(){
		$view   = $this->getView('ip','html');
	 	$view->display();
	}
 function adminid(){
		$view   = $this->getView('adminid','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->display();
	}
function masterpwd(){
		$view   = $this->getView('masterpwd','html');
	 	$view->display();
	}
function mastermail(){
		$view   = $this->getView('mastermail','html');
	 	$view->display();
	}
function keeplog(){
		$view   = $this->getView('keeplog','html');
	 	$view->display();
	}
function logincontrol(){
		$view   = $this->getView('logincontrol','html');
	 	$view->display();
	}
function pwdprotect(){
		$view   = $this->getView('pwdprotect','html');
	 	$view->display();
	}
	function help(){
	 	$view   = $this->getView('help','html');
	 	$view->display();
	}
 function metatags(){
	 	$view   = $this->getView('metatags','html');
	 	$view->display();
	}
		 function comprotect(){
	 	$view   = $this->getView('comprotect','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->display();
	}
	function graph(){
	 	$view   = $this->getView('graph','html');
	 	$view->display();
                 }

	function saveBasic(){
		$view   = $this->getView('basic','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save(); 	
	}
	function applyBasic(){
		$view   = $this->getView('basic','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
	}
	
	function saveEmailcheck(){
		$view   = $this->getView('emailcheck','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save(); 	
	}
	function applyEmailcheck(){
		$view   = $this->getView('emailcheck','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
	}
	
   function saveMail(){
		$view   = $this->getView('mail','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
 	
	}
	function applyMail(){
		$view   = $this->getView('mail','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
 	
	}
   function saveIp(){
		$view   = $this->getView('ip','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
 	
	}
	function applyIp(){
		$view   = $this->getView('ip','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
 	
	}
   function saveMasterpwd(){
		$view   = $this->getView('masterpwd','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
 	
	}
	function applyMasterpwd(){
		$view   = $this->getView('masterpwd','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
 	
	}
  function saveMpmail(){
		$view   = $this->getView('mastermail','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
 	
	}
	 function applyMpmail(){
		$view   = $this->getView('mastermail','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
 	
	}
function saveLog(){
		$view   = $this->getView('keeplog','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
 	
	}
	function applyLog(){
		$view   = $this->getView('keeplog','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
 	
	}
	
function saveLogincontrol(){
		$view   = $this->getView('logincontrol','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
 	
	}
	function applyLogincontrol(){
		$view   = $this->getView('logincontrol','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
 	
	}
function saveAdminpwdpro(){
		$view   = $this->getView('pwdprotect','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
 	
	}
	function applyAdminpwdpro(){
	
		$view   = $this->getView('pwdprotect','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
 	
	}
 function saveAdminid(){
		$view   = $this->getView('adminid','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save(); 	
}
 function saveMetatags(){
		$view   = $this->getView('metatags','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
 	
	}
	function applyMetatags(){
		$view   = $this->getView('metatags','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
 	
	}
	function applyUserkey(){
		$view   = $this->getView('userkey','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
 	
	}
	function saveUserkey(){
		$view   = $this->getView('userkey','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
 	
	}
	function saveComprotect(){
		$view   = $this->getView('comprotect','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
 	
	}
	function applyComprotect(){
		$view   = $this->getView('comprotect','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
 	
	}

	function isMasterLogged(){
		
		jimport('joomla.filesystem.file');	

		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	 = $basepath.'/params.php';
		
		require_once($configFile);
		
		$JSecureConfig = new JSecureConfig();
		
		if($JSecureConfig->enableMasterPassword == '0')
			return true;
		
		$session = JFactory::getSession();
		
		return $session->get('jsecure_master_logged', false);

	}

	function auth(){
		$view		   = $this->getView('auth','html');
	 	$view->display();
	}

	function login(){
		$view   = $this->getView('auth','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->login();
	}

	function setAdminType(){
		$view   = $this->getView('config','html');
		$view->setAdminType();
	}
		
	function log(){
		$view   = $this->getView('log','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->display();
	}
	
	function ipinfo(){
		$view   = $this->getView('log','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
		$view->setLayout('ipinfo');
	 	$view->ipInfo();
		exit;
	}
	/*@added*/
	function blockip(){
		$view   = $this->getView('log','html');
	 	$view->changeipstatus();
		exit;
	}

	function unblockip(){
		$view   = $this->getView('log','html');
	 	$view->changeipstatus();
		exit;
	}
/**/

	public function purgesessions()
	{   
		$model = $this->getModel( 'jsecurelog' );
		$sess_count = $model->purgeSessions();
		$msg = sprintf(JText::_("PURGE_SESSION_SUCCESS"),$sess_count);
		$this->setRedirect('index.php?option=com_jsecure&task=auth',$msg);
	}
	public function changeId()
	{
		$model = $this->getModel( 'jsecurelog' );
		$state = $model->changeSuperAdminId();
		if($state)
		{
		 $type = 'message';
		 $msg = JText::_("SUPER_ADMIN_ID_CHANGE_SUCCESS");
		 $this->setRedirect('index.php?option=com_jsecure&task=adminid',$msg,$type);
		}
		else
		{
		 $type = 'error';
		 $msg = JText::_("SUPER_ADMIN_ID_CHANGE_ERROR");
		 $this->setRedirect('index.php?option=com_jsecure&task=adminid',$msg,$type); 	
		}
		
	}

	function checkCompassword (){
		
		$id = $_POST['id'];
		$name = $_POST['name'];
		$compname = str_replace('com_','',$name);
        $compname = ucfirst($compname); 
		$session_variable = $name.$id;
		$password = $_POST['component_password'];
		$db = JFactory::getDBO();
		$query1 = "SELECT * FROM #__jsecurepassword where com_id=".$id;
		$db->setQuery($query1);
        $display = $db->loadObjectList();
	    $savepwd = $display[0]->password;
            if($savepwd == base64_encode($password))
				{

				$session    = JFactory::getSession();
				$session->set($session_variable, 1);
				$app    = &JFactory::getApplication();
				$link = 'index.php?option='.$name;
                $msg  = 'You have Been Logged into '.$compname; 
				$mainframe = JFactory::getApplication();    
                $mainframe->redirect($link,$msg,"MESSAGE");
 			   
				
				}
				else{
					JError::raiseWarning(404, JText::_('Password not correct'));
					$view   = $this->getView('componentform','html');
	 	            $view->display();
				    }
  	
 	}

	function getVersion(){
  	$params = JComponentHelper::getParams('com_jsecure');
	
	$params = json_decode($params);	
	$params = $params->version_info;	
	echo $params;
	// $versionPresent = $params->get('version');
	// echo $versionPresent;
  	exit;
 	}
	
	function autoban_tasks($task)
	{
		
	if($task=='save')          //if task=='save' for autoban ip
	 {
	    $view   = $this->getView('autoban','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save();
	 }
	else if($task=='apply')    //if task=='apply' for autoban ip
	 {
	    $view   = $this->getView('autoban','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
	 }
	} 
	
	function userkey()
	{	
		$view = $this->getView('userkey','html');
		$model 	= $this->getModel( 'userkey' );
		$view->setModel($model);
	 	$view->display();
	}
	
	function addkey()
	{   
		$view = $this->getView('userkey','html');
	 	$model 	= $this->getModel( 'userkey' );
		$view->setModel($model);
		$view->setLayout('form');
		$view->addkey();
	}
	
	function publishkey()
	{
		$view  = $this->getView('userkey','html');
		$model = $this->getModel( 'userkey' );
		$view->setModel( $model, true );
		$view->publishkey();
	}
	
	function unpublishkey()
	{
		$view  = $this->getView('userkey','html');
		$model = $this->getModel( 'userkey' );
		$view->setModel( $model, true );
		$view->unpublishkey();
	}
	
	function deletekey()
	{	
		$view  = $this->getView('userkey','html');
		$model = $this->getModel( 'userkey' );
		$view->setModel( $model, true );
		$view->deletekey();
	}
	
	function closeuserkey()
	{	
		$this->setRedirect( 'index.php?option=com_jsecure');
	}
	
	function editkey()
	{   
		$view = $this->getView('userkey','html');
	 	$model 	= $this->getModel( 'userkey' );
		$view->setModel($model);
		$view->setLayout('form');
		$view->editkey();
	}
	
	function imageSecure()
	{
		$view = $this->getView('imagesecure','html');		
	 	$view->display();
	
	}
	
	function applyimagesecure()
	{	
		$view = $this->getView('imagesecure','html');
	 	$view->applyImageSecureDetails();
	
	}
	
	function saveimagesecure()
	{	
		$view = $this->getView('imagesecure','html');
	 	$view->saveImageSecureDetails();
	
	}
	
	/*Country Block functionality*/
	function countryblock(){
	
		jimport('joomla.filesystem.file');	
	  	$view   = $this->getView('countryblock','html');
		$model 	= $this->getModel( 'jsecurelog');
		$view->setModel($model);
		$view->setLayout('default');
	 	$view->display();
	
	}
	function applyCountryblock(){
	
	    $view   = $this->getView('countryblock','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply();
	}
	
	function saveCountryblock(){
	
	    $view   = $this->getView('countryblock','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save(); 	
	}

	function countrylog(){
	
	    jimport('joomla.filesystem.file');    
        $view   = $this->getView('countryblock','html');
        $model     = $this->getModel( 'jsecurelog' );
        $view->setModel($model);
        $view->setLayout("countrylog");
        $view->countrylog();
	}
	
	function publish( $state = 1 ){

		$mainframe = Jfactory::GetApplication();
		
		// /*Initialize variables*/
		$db		=  JFactory::getDBO();
		$user	=  JFactory::getUser();

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );

		JArrayHelper::toInteger($cid);
		$option	= JRequest::getCmd( 'option' );
		
		
		$task	= JRequest::getCmd( 'task' );
		
		
		$rtask	= JRequest::getCmd( 'returntask', '', 'post' );
		if ($rtask) {
			$rtask = '&task='.$rtask;
		}

		if (count($cid) < 1) {
			$redirect	= JRequest::getVar( 'redirect', '', 'post', 'int' );
			$action		= ($state == 1) ? 'publish' : ($state == -1 ? 'archive' : 'unpublish');
			$msg		= JText::_('Select an item to') . ' ' . JText::_($action);
			$mainframe->redirect('index.php?option='.$option.$rtask.'&sectionid='.$redirect, $msg, 'error');
		}

		// Get some variables for the query
		// $uid	= $user->get('id');
		$total	= count($cid);
		$cids	= implode(',', $cid);

		$query = 'UPDATE #__jsecure_countries' .
				' SET published = '. (int) $state .
				' WHERE id IN ( '. $cids .' ) ';
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}

		switch ($state)
		{
			case -1 :
				$msg = JText::sprintf('Item(s) successfully Archived', $total);
				break;

			case 1 :
				$msg = JText::sprintf('Item(s) successfully Published', $total);
				break;

			case 0 :
			default :
				if ($task == 'unarchive') {
					$msg = JText::sprintf('Item(s) successfully Unarchived', $total);
				} else {
					$msg = JText::sprintf('Item(s) successfully Unpublished', $total);
				}
				break;
		}

		$cache =  JFactory::getCache('com_jsplocation');
		$cache->clean();

		$mainframe->redirect('index.php?option='.$option.$rtask.'&task=countryblock', $msg, 'MESSAGE');
}
	
	function unpublish($state = 0){
	
		$this->publish($state);
	}
	
	
	function removecountrylog(){

		jimport('joomla.filesystem.file');    	
		$view   = $this->getView('countryblock','html');
		$model     = $this->getModel( 'jsecurelog' );
		$view->setModel($model);
		$view->setLayout("countrylog");
		$view->delete();
	}

	
	function cancelcountrylog(){
	
    $mainframe = Jfactory::GetApplication();
	jimport('joomla.filesystem.file');    
	$mainframe->redirect('index.php?option=com_jsecure&task=countryblock');
     
    }
	
	function whois(){
						
		jimport('joomla.filesystem.file');	
	  	$view   = $this->getView('whois','html');
		$model 	= $this->getModel('jsecurelog');
		$view->setModel($model);
		$view->setLayout('default');
	 	$view->display();
	
	}
	
	function changedbprefix(){	

		jimport('joomla.filesystem.file');	
	  	$view   = $this->getView('changedbprefix','html');
		$model 	= $this->getModel('jsecurelog');
		$view->setModel($model);
		$view->setLayout('default');
	 	$view->display();
	}
	
	function submitdbprefix(){	
		jimport('joomla.filesystem.file');	
	  	$view   = $this->getView('changedbprefix','html');
		$model 	= $this->getModel('jsecurelog');
		$view->setModel($model);
		$view->setLayout('default');
	 	$view->submitdbprefix();
	}
	
	
	function generatedbprefix(){
	
        jimport('joomla.filesystem.file');    
        $view   = $this->getView('changedbprefix','html');
        $model     = $this->getModel('jsecurelog');
        $view->setModel($model);
        $view->setLayout('default');
         $view->randomPassword();
    }
	
	function filescan(){
		jimport('joomla.filesystem.file');	
	  	$view   = $this->getView('filescan','html');
		$model 	= $this->getModel('jsecurelog');
		$view->setModel($model);
		$view->setLayout('default');
	 	$view->display();
	
	}
	
	function startscanner(){
		jimport('joomla.filesystem.file');	
	  	$view   = $this->getView('filescan','html');
		$model 	= $this->getModel('jsecurelog');
		$view->setModel($model);
		$view->setLayout('default');
	 	$view->startscanner();
	
	}
	
	function dbbackup(){
		jimport('joomla.filesystem.file');	
	  	$view   = $this->getView('changedbprefix','html');
		$model 	= $this->getModel('jsecurelog');
		$view->setModel($model);
		$view->setLayout('default');
	 	$view->dbbackup();	
	}
	function license(){
    jimport('joomla.filesystem.file');
	$view = $this->getView('license','html');
	$model = $this->getModel('jsecurelog');
	$view->setModel($model);
	$view->setLayout('default');
	$view->display();
	}
	function savelicense(){
	    $view   = $this->getView('license','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->save(); 	
	}
	function applylicense(){
	    $view   = $this->getView('license','html');
		$model 	= $this->getModel( 'jsecurelog' );
		$view->setModel($model);
	 	$view->apply(); 	
	
	}
	function cancellicense(){
	
	$mainframe = Jfactory::GetApplication();
	jimport('joomla.filesystem.file');    
	$mainframe->redirect('index.php?option=com_jsecure&task=license');
	
	}		
			
}
?>
