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
 * @version     $Id: view.html.php  $
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class jsecureViewAuth extends JViewLegacy {

	protected $form;
	protected $item;
	protected $state;

	function display($tpl=null){

		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		require_once($configFile);
		$JSecureConfig = new JSecureConfig();
		$this->addToolbar();
		$this->assignRef('JSecureConfig',$JSecureConfig);
		parent::display($tpl);
	}

	 protected function addToolbar()
	{
		
		JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
		JToolBarHelper::preferences('com_jsecure');
		
	}
	
	function login(){
		$app    = &JFactory::getApplication();
		jimport('joomla.filesystem.file');	

		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		
		require_once($configFile);
		
		$JSecureConfig = new JSecureConfig();

		$master_password = md5(base64_encode(JRequest::getCmd('master_password')));
		
		if($JSecureConfig->master_password == $master_password )
		{
			$oldTask = '';
			$session = JFactory::getSession();
			$session->set('jsecure_master_logged', true);
			$app->redirect('index.php?option=com_jsecure'.$oldTask.'', JText::_('LOGIN_OK'),'MESSAGE');
			
		} else {
			$model 	= $this->getModel( 'jsecurelog' );
			$change_variable = 'Wrong Master Key = '.JRequest::getCmd('master_password'); 
		
			$insertLog = $model ->insertLog('JSECURE_EVENT_MASTER_LOGIN_FAIL', $change_variable);
		
			JError::raiseWarning(500, JText::_('LOGIN_ERROR'));
			$app->redirect('index.php?option=com_jsecure&view=auth');
		}
	}
	
}

?>