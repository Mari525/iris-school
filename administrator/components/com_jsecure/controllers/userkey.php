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
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class jsecureControlleruserkey extends JControllerForm
{

	function display(){
	
		$task = JRequest::getCmd('task');
	
		if($task == 'applykeyform'){
		$this->applykeyform();
		}
		
		if($task == 'savekeyform'){
		$this->savekeyform();
		}
		
		if($task == 'cancelkeyform'){
		$this->cancelkeyform();
		}
		
		if($task == 'applyEditkey'){
		$this->applyEditkey();
		}
		
		if($task == 'saveEditkey'){
		$this->saveEditkey();
		}
	
	}
	
	function applykeyform(){
	
	$userID = JRequest::getVar('userID');
	$user_key = JRequest::getVar('user_key');
	$start_date = JRequest::getVar('start_date');
	$end_date = JRequest::getVar('end_date');
	$status = JRequest::getVar('status');
	
	if(!empty($userID) && !empty($user_key) && !empty($start_date) && !empty($end_date) && $status!="") {
	
	$model=$this->getModel('userkey');
	$result = $model->saveUserkeys($userID, $user_key, $start_date, $end_date, $status);
	
	if($result == true) {
		$app = JFactory::getApplication();
		$link = 'index.php?option=com_jsecure&task=addkey';
		$msg  = 'Userkey(s) saved successfully!';
		$app->redirect($link,$msg,'MESSAGE');
		}
	}
	
	}
	
	function savekeyform(){
	
	$userID = JRequest::getVar('userID');
	$user_key = JRequest::getVar('user_key');
	$start_date = JRequest::getVar('start_date');
	$end_date = JRequest::getVar('end_date');
	$status = JRequest::getVar('status');
	
	if(!empty($userID) && !empty($user_key) && !empty($start_date) && !empty($end_date) && $status!="") {
	
	$model=$this->getModel('userkey');
	$result = $model->saveUserkeys($userID, $user_key, $start_date, $end_date, $status);
	
	if($result == true) {
		$app = JFactory::getApplication();
		$link = 'index.php?option=com_jsecure&task=userkey';
		$msg  = 'Userkey(s) saved successfully!';
		$app->redirect($link,$msg,'MESSAGE');
		}
	}
	
	}
	
	function applyEditkey(){
	
	$key_id = JRequest::getVar('key_id');
	$user_key = JRequest::getVar('user_key');
	$start_date = JRequest::getVar('start_date');
	$end_date = JRequest::getVar('end_date');
	$status = JRequest::getVar('status');
		
	$model=$this->getModel('userkey');
	$result = $model->updateUserkeys($key_id, $user_key, $start_date, $end_date, $status);
	
	if($result == true) {
		$app = JFactory::getApplication();
		$link = 'index.php?option=com_jsecure&task=editkey&id='.$key_id;
		$msg  = 'Userkey updated successfully!';
		$app->redirect($link,$msg,'MESSAGE');
		}
	
	}
	
	function saveEditkey(){
	
	$key_id = JRequest::getVar('key_id');
	$user_key = JRequest::getVar('user_key');
	$start_date = JRequest::getVar('start_date');
	$end_date = JRequest::getVar('end_date');
	$status = JRequest::getVar('status');
	
	$model=$this->getModel('userkey');
	$result = $model->updateUserkeys($key_id, $user_key, $start_date, $end_date, $status);
	
	if($result == true) {
		$app = JFactory::getApplication();
		$link = 'index.php?option=com_jsecure&task=userkey';
		$msg  = 'Userkey updated successfully!';
		$app->redirect($link,$msg,'MESSAGE');
		}
	
	}
	
	function cancelkeyform(){
	
	$this->setRedirect( 'index.php?option=com_jsecure&task=userkey');
	
	}
}	