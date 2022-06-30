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
 * @version     $Id: jsecure.php  $
 */
// no direct access
ini_set('display_errors', '0');
defined('_JEXEC') or die('Restricted Access');

if (!file_exists(JPATH_ROOT.'/'.'administrator'.'/'.'components'.'/'.'com_jsecure'.'/'.'images'.'/'.'secureimage'.'/')) {
    mkdir(JPATH_ROOT.'/'.'administrator'.'/'.'components'.'/'.'com_jsecure'.'/'.'images'.'/'.'secureimage', 0777, true);
}

if (!file_exists(JPATH_ROOT.'/'.'administrator'.'/'.'components'.'/'.'com_jsecure'.'/'.'images'.'/'.'tempimage'.'/')) {
    mkdir(JPATH_ROOT.'/'.'administrator'.'/'.'components'.'/'.'com_jsecure'.'/'.'images'.'/'.'tempimage', 0777, true);
}


// Require the base controller
require_once (JPATH_COMPONENT_ADMINISTRATOR.'/controller.php');

if (!JFactory::getUser()->authorise('core.manage', 'com_jsecure')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base()."components/com_jsecure/css/jsecure.css");
$task	= JRequest::getCmd('task');
$con = JRequest::getCmd('controller');
$input=JFactory::getApplication()->input;

// Create the controller
$controller    = new jsecureControllerjsecure();

if($con=='userkey') {
	// Sub-controller condition for UserKey
   
	require_once (JPATH_COMPONENT_ADMINISTRATOR.'/controller.php');
	$task = JRequest::getCmd('task');
	$path = JPATH_COMPONENT.'/'.'controllers'.'/'.$con.'.php';
	
	if (file_exists($path)) {
		require_once $path;
		
	} else {
		$con = '';
	}

	$classname	= 'jsecureController'.$con;
	$subcontroller	= new $classname( );
	$subcontroller->execute($input->get('action','display'));
	$subcontroller->redirect();
	
  }

// Perform the Request task
if (!$controller->isMasterLogged())
{	
	if (JRequest::getCmd('task') == 'login')
		$controller->execute('login');
	else 
		$controller->execute($task);
}
else
	$controller->execute($task);

if($con=='autoban') 
{
   
        // special condition for Autoban
   $controller->autoban_tasks($task);
   
   
  } 
// Redirect if set by the controller
$controller->redirect();

?>