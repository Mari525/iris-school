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

class jsecureViewUserkey extends JViewLegacy {
	
	
	
	function display($tpl=null){
		
		$app = JFactory::getApplication();
		$context = JRequest::getVar('option');
		//$this->search = $app->getUserStateFromRequest($context.'search', 'search', '', 'post');
		$this->search = JRequest::getVar('search','');
		$model=$this->getModel('userkey');
		if(empty($this->search) || ctype_space($this->search)) {
		if(ctype_space($this->search)) {
		$this->search='';
		}		
		$this->viewList = $model->prepareViewlist();
		$this->userNames = $model->prepareViewnames();
		$this->groupNames = $model->prepareViewgroups();
		}
		else if(!empty($this->search) && !ctype_space($this->search)){
		$this->userNames = $model->searchViewnames($this->search);
		$this->groupNames = $model->searchViewgroups($this->search);
		$this->viewList = $model->searchViewlist($this->search);
		}
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		$limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );
		$total=$model->getTotalRecords();
		jimport('joomla.html.pagination');		
		$this->pagination = new JPagination($total, $limitstart, $limit);
		$this->addToolbarlist();
		parent::display($tpl);
	}
	
	
	function addkey($tpl=null){
		
		$this->Session_user_key = JRequest::getVar('user_key');
		$this->Session_start_date = JRequest::getVar('start_date');
		$this->Session_end_date = JRequest::getVar('end_date');
		$this->Session_status = JRequest::getVar('status');
		
		$this->typeFilter = JRequest::getVar('user_group_filter');
		$model=$this->getModel('userkey');
		$this->userType = $model->prepareUsertype();
		if(!empty($this->typeFilter)) {
		$this->userList = $model->prepareUserlist($this->typeFilter);
		}
		$this->addToolbarlist();
		parent::display();
	}
	
	
    function addToolbarlist(){
		
		$task	= JRequest::getCmd('task');
		if($task == 'userkey')
		{
		JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
		JToolbarHelper::addNew('addkey');
		JToolbarHelper::deleteList('Do you want to delete the userkey(s) ?','deletekey');
		JToolbarHelper::publish('publishkey','publish', true);
		JToolbarHelper::unpublish('unpublishkey', 'unpublish', true);
		JToolBarHelper::cancel('closeuserkey','Close');
		JToolbarHelper::preferences('com_jsecure');
		}
		else if($task == 'addkey')
		{
		JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
		JToolBarHelper::apply('applykeyform');
		JToolBarHelper::save('savekeyform');
		JToolBarHelper::cancel('cancelkeyform');
		}
		else if($task == 'editkey')
		{
		JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
		JToolBarHelper::apply('applyEditkey','Update');
		JToolBarHelper::save('saveEditkey','Update & Close');
		JToolBarHelper::cancel('cancelkeyform');
		}
	}
	
	
	 function publishkey(){
		
		$id_list = JRequest::getVar('cid');
		$model = $this->getModel('userkey');
		$result = $model->publishKey($id_list);
		if($result == true){
		$app  =  JFactory::getApplication();
		$link = 'index.php?option=com_jsecure&task=userkey';
		$msg  = 'Userkey(s) published successfully!';
		$app->redirect($link,$msg,'MESSAGE');
		}
		
	 }
	 
	 function unpublishkey(){
		
		$id_list = JRequest::getVar('cid');
		$model = $this->getModel('userkey');
		$result = $model->unpublishKey($id_list);
		if($result == true){
		$app  =  JFactory::getApplication();
		$link = 'index.php?option=com_jsecure&task=userkey';
		$msg  = 'Userkey(s) unpublished successfully!';
		$app->redirect($link,$msg,'MESSAGE');
		}
	 }
	
	 function deletekey(){
		
		$id_list = JRequest::getVar('cid');
		$model = $this->getModel('userkey');
		$result = $model->deletekey($id_list);
		if($result == true){
		$app  =  JFactory::getApplication();
		$link = 'index.php?option=com_jsecure&task=userkey';
		$msg  = 'Userkey(s) deleted successfully!';
		$app->redirect($link,$msg,'MESSAGE');
		}
	 }
	 
	 function editkey($tpl=null){
	 
	    $key_id = JRequest::getVar('id');
		$model=$this->getModel('userkey');
		$this->keyData = $model->prepareEditkey($key_id);
		$this->addToolbarlist();
		parent::display();
	}
}

?>