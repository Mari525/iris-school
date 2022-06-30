<?php
/**
 * jSecure Authentication components for Joomla!
 * jSecure Authentication extention prevents access to administration (back end)
 * login page without appropriate access key. 
 * @author      $Author: Ajay Lulia $
 * @copyright   Joomla Service Provider - 2016
 * @package     jSecure3.5
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id: view.html.php  $
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport('joomla.html.pane');

class jsecureViewComprotect extends JViewLegacy {
	
	function display($tpl=null){
		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		require_once($configFile);
		$JSecureConfig = new JSecureConfig();
		$model = $this->getModel('jsecurelog');
        $components = $model->getComponentname();
		$password = $model->getPassword();
		$status[] = JHTML::_( 'select.option', '-1', "Select state",'id', 'title');
		$status[] = JHTML::_( 'select.option', '0', "Disabled",'id', 'title' );
		$status[] = JHTML::_( 'select.option', '1', "Enabled", 'id', 'title' );
        $this->addToolbar();
		$this->assignRef('status',$status);
		$this->assignRef('password',$password);
		$this->assignRef('JSecureConfig',$JSecureConfig);
		$this->assignRef('components',$components);
		parent::display($tpl);
	}

	 protected function addToolbar()
	{
		
		JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
		
			JToolBarHelper::apply('applyComprotect');
			JToolBarHelper::save('saveComprotect');
			JToolBarHelper::cancel('cancel');
	}
	
	function save(){
		$app    = &JFactory::getApplication();
     	$msg  = 'Details Has Been Saved';
		$result = $this->saveDetails();
 		if($result){
 			$link = 'index.php?option=com_jsecure';
 			$msg  = 'Details Has Been Saved';
 			$app->redirect($link,$msg,'MESSAGE');
 	    }
 	}
	function apply(){
		
		$app    = &JFactory::getApplication();
     	$msg  = 'Details Has Been Saved';
		$result = $this->saveDetails();

 		if($result){
 			$link = 'index.php?option=com_jsecure&task=comprotect';
 			$msg  = 'Details Has Been Saved';
 			$app->redirect($link,$msg,'MESSAGE');
 	    }
 	}

 	
 	function saveDetails(){	
		$app    = &JFactory::getApplication();
		$db = & JFactory::getDBO();
		$model = $this->getModel('jsecurelog');
        $component = $_POST['component'];
        $comp_details = $model->getPassword();
		//print_r($comp_details);
			foreach($comp_details as $com)
		{
				$ids[] = $com->com_id;
		}
		
        foreach($component as $k =>$com)
		{
		    $status = $com['status'];
			$password = $com['key'];
			if($status == 1 and $password != '')
			{
				 $password = base64_encode($password);
				if(in_array($k,$ids))
				{
				$query =	"UPDATE #__jsecurepassword SET status = '".$status."', password ='".$password."' where com_id = ".$k;
				$db->setQuery($query);
		        $db->query();
				}
				else{
		     $query = "INSERT INTO `#__jsecurepassword` (id,com_id,status,password) values ('','$k','$status','$password')";
			 $db->setQuery($query);
		     $db->query();
				}
			}
			if($status == 0 and in_array($k,$ids))
			{
				$query =	"UPDATE #__jsecurepassword SET status = '".$status."' where com_id = ".$k;
				$db->setQuery($query);
		        $db->query();
			}
           if($status == 1 and $password == '')
			{ 
		      $a = in_array($k,$ids);
			   if($a == false)
				{
			    $name = $model->getNamecomp($k);
				$component_name = $name[0]->name;
				$component_name = str_replace('com_','',$component_name);
	            $component_name = ucfirst($component_name); 
				JError::raiseWarning(404, JText::_('Please enter a password for '.$component_name));
				$link = 'index.php?option=com_jsecure&task=comprotect';
 			    $app->redirect($link);
				}
				else
				{
                $query = "UPDATE #__jsecurepassword SET status = '".$status."' where com_id = ".$k;
				$db->setQuery($query);
		        $db->query();
				}
			}
		}
		return true;
 	}	
	
 	function checkModifiedFieldName($newValue, $oldValue, $JSecureCommon, $keyvalue=null, $masterkeyvalue=null){

	$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
	$commonFile	= $basepath.'/common.php';
	require_once($commonFile);
	$ModifiedValue = '';
	
		foreach($newValue as $key){
			$currentKeyName =  key($newValue);
		
			if(isset($oldValue)){
			 
			 if(array_key_exists($currentKeyName, $oldValue)){
				$result=($newValue[$currentKeyName] == $oldValue->$currentKeyName) ? '1' : '0';
				
				if(!$result){

					if( !isset($JSecureCommon[$currentKeyName])  ||  !isset($newValue[$currentKeyName]) )
					{
						continue;
					}
				
					switch($currentKeyName){
					case 'adminpasswordpro':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $adminpasswordpro[1] :  $adminpasswordpro[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
						break;
						default:
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
							
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $newValue[$currentKeyName] . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $newValue[$currentKeyName] . '<br/>');
						break;
					}

				}	
				next($newValue);
			 }
		  }
		}
	  return $ModifiedValue;
   }
 	
   function sendmail($JSecureConfig, $fieldName){
   		
		$config   = new JConfig();

		 $to        = $JSecureConfig->mpemailid;	
		 $to        = ($to) ? $to :  $config->mailfrom;
		 
		 if($to){
			$fromEmail  = $config->mailfrom;
			$fromName  = $config->fromname;
			$subject      = $JSecureConfig->mpemailsubject;
			$body         = JText::_( 'BODY_MESSAGE_FOR_MODIFIED_FIELDNAME:' ) .$_SERVER['REMOTE_ADDR'];
			$body		.= " ";
			$body		.= $fieldName ;  
			
			JUtility::sendMail($fromEmail, $fromName, $to, $subject, $body,1);
			$headers .= 'From: '. $fromName . ' <' . $fromEmail . '>';
			//mail($to, $subject, $body, $headers);
		 }	
	}   
}

?>