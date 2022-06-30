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

class jsecureViewMasterpwd extends JViewLegacy {

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
		
		
			JToolBarHelper::apply('applyMasterpwd');
			JToolBarHelper::save('saveMasterpwd');
			JToolBarHelper::cancel('cancel');
			JToolBarHelper::help('help');
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
 			$link = 'index.php?option=com_jsecure&task=masterpwd';
 			$msg  = 'Details Has Been Saved';
 			$app->redirect($link,$msg,'MESSAGE');
 	    }
 	}
 	
 	function saveDetails(){	
 		
		jimport('joomla.filesystem.file');	
		$app        = JFactory::getApplication();
		$option		= JRequest::getVar('option', '', '', 'cmd');
		$post       = JRequest::get( 'post' );
		
		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		
		$xml	    = $basepath.'/com_jsecure.xml';
		
		require_once($configFile);
		
		if(! is_writable($configFile))
		{
			$link = "index.php?option=com_jsecure&task=masterpwd";
			$msg = 'Configuration File is Not Writable /administrator/components/com_jsecure/params.php ';
			$app->redirect($link, $msg, 'notice'); 
			exit();
		}

		// Read the ini file
		if (JFile::exists($configFile)) {
			$content = JFile::read($configFile);
		} else {
			$content = null;
		}

		$config	  = new JRegistry('JSecureConfig');
		$oldValue = new JSecureConfig();
		$config_array = array();
		$config_array['publish']	                 =  $oldValue->publish;
		$config_array['publishemailcheck']			= $oldValue->publishemailcheck;
		$config_array['publishevent']			= $oldValue->publishevent;
		$config_array['blacklistemail']			= $oldValue->blacklistemail;
		$config_array['publishlogdb']			= $oldValue->publishlogdb;
		$config_array['publishforumcheck']			= $oldValue->publishforumcheck;
		$config_array['forumfrequency']			= $oldValue->forumfrequency;
		$config_array['key']                         =  $oldValue->key;
		$config_array['passkeytype']	             =  $oldValue->passkeytype;
		$config_array['countryblock']	             =  $oldValue->countryblock;
		$config_array['countryblock_frontend']	             =  $oldValue->countryblock_frontend;
		$config_array['country_front_custom_path']	  =  $oldValue->country_front_custom_path;
		$config_array['countryfrnt_options']	  =  $oldValue->countryfrnt_options;
		$config_array['options']                     =  $oldValue->options; 
		$config_array['custom_path']				 =  $oldValue->custom_path;
		
		$config_array['captchapublish']			= $oldValue->captchapublish;
		$config_array['imageSecure']    		= $oldValue->imageSecure;
		$config_array['enableMasterPassword']   = JRequest::getVar('MasterPasswordenable', 0, 'post', 'int');
		$config_array['master_password']        = JRequest::getVar('master_password', 0, 'post');
		$config_array['include_basic_confg']    = JRequest::getVar('include_basic_confg', 0, 'post', 'int');
		$config_array['include_email_scan']    = JRequest::getVar('include_email_scan', 0, 'post', 'int');
		$config_array['include_image_secure']   = JRequest::getVar('include_image_secure', 0, 'post', 'int');
		$config_array['include_user_key']       = JRequest::getVar('include_user_key', 0, 'post', 'int');
		$config_array['include_country_block']       = JRequest::getVar('include_country_block', 0, 'post', 'int');
		$config_array['include_change_db_prefix']       = JRequest::getVar('include_change_db_prefix', 0, 'post', 'int');
		$config_array['include_whois']       = JRequest::getVar('include_whois', 0, 'post', 'int');
		$config_array['include_adminpwdpro']    = JRequest::getVar('include_adminpwdpro', 0, 'post', 'int');
		$config_array['include_mail']           = JRequest::getVar('include_mail', 0, 'post', 'int');
		$config_array['include_ip']             = JRequest::getVar('include_ip', 0, 'post', 'int');
		$config_array['include_mastermail']     = JRequest::getVar('include_mastermail', 0, 'post', 'int');
		$config_array['include_adminid']        = JRequest::getVar('include_adminid', 0, 'post', 'int');
		$config_array['include_logincontrol']   = JRequest::getVar('include_logincontrol', 0, 'post', 'int');
		$config_array['include_metatags']       = JRequest::getVar('include_metatags', 0, 'post', 'int');
		$config_array['include_purgesessions']	= JRequest::getVar('include_purgesessions', 0, 'post', 'int');
		$config_array['include_log']            = JRequest::getVar('include_log', 0, 'post', 'int');
		$config_array['include_showlogs']       = JRequest::getVar('include_showlogs', 0, 'post', 'int');
		$config_array['include_directorylisting']= JRequest::getVar('include_directorylisting', 0, 'post', 'int');
		$config_array['include_component_protection']= JRequest::getVar('include_component_protection', 0, 'post', 'int');
		$config_array['include_autobanip']= JRequest::getVar('include_autobanip', 0, 'post', 'int');
		$config_array['include_graph']          = JRequest::getVar('include_graph', 0, 'post', 'int');
		$config_array['sendemail']		 = $oldValue->sendemail;
		$config_array['sendemaildetails']	 = $oldValue->sendemaildetails;
		$config_array['emailid']		 = $oldValue->emailid;
		$config_array['emailsubject']		 = $oldValue->emailsubject;
		$config_array['iptype']	                 = $oldValue->iptype;
		$config_array['iplistB']                 = $oldValue->iplistB;
		$config_array['iplistW']                 = $oldValue->iplistW;
		$config_array['abip']                    = $oldValue->abip;
		$config_array['ablist']                  = $oldValue->ablist;
		$config_array['abiplist']                = $oldValue->abiplist;
		$config_array['abiptrylist']             = $oldValue->abiptrylist;
		$config_array['spamip']                  = $oldValue->spamip;
		$config_array['spamlist']                = $oldValue->spamlist;
		$config_array['allowedthreatrating']     = $oldValue->allowedthreatrating;
		$config_array['mpsendemail']		 = $oldValue->mpsendemail;
		$config_array['mpemailsubject']		 = $oldValue->mpemailsubject;
		$config_array['mpemailid']		 = $oldValue->mpemailid;
		$config_array['login_control']		 = $oldValue->login_control;
		$config_array['adminpasswordpro']	 = $oldValue->adminpasswordpro;
		$config_array['metatagcontrol']		 = $oldValue->metatagcontrol;
		$config_array['metatag_generator']	 = $oldValue->metatag_generator;
		$config_array['metatag_keywords']	 = $oldValue->metatag_keywords;
		$config_array['metatag_description']	 = $oldValue->metatag_description;
		$config_array['metatag_rights']		 = $oldValue->metatag_rights;
      
		
		if($config_array['master_password'] == ""){
				$config_array['master_password'] = $oldValue->master_password;			
		} else {
				$masterkeyvalue = $config_array['master_password'];
				$config_array['master_password'] = md5(base64_encode($config_array['master_password']));
		}
	
		$config_array['adminType'] = $oldValue->adminType ;
		$config_array['delete_log']  = $oldValue->delete_log; //JRequest::getVar('delete_log', '0', 'post', 'int');

	//	$modifiedFieldName	=$this->checkModifiedFieldName($config_array, $oldValue, $JSecureCommon, $keyvalue, $masterkeyvalue);
		$modifiedFieldName	=$this->checkModifiedFieldName($config_array, $oldValue, null, null, null);
			
		$config->loadArray($config_array);
		
		$fname = JPATH_COMPONENT_ADMINISTRATOR.'/'.'params.php';
		 
		if (JFile::write($fname, $config->toString('PHP', array('class' => 'JSecureConfig','closingtag' => false)))) 
			$msg = JText::_('The Configuration Details have been updated');
		 else 
			$msg = JText::_('ERRORCONFIGFILE');
	
		if($modifiedFieldName != ''){
			$basepath   = JPATH_COMPONENT_ADMINISTRATOR .'/models/jsecurelog.php';
			require_once($basepath);
		
			$model 	= $this->getModel( 'jsecurelog' );
			$change_variable = str_replace('<br/>', '\n', $modifiedFieldName); 
		
			$insertLog = $model ->insertLog('JSECURE_EVENT_CONFIGURATION_FILE_CHANGED', $change_variable);
		}

		
		$JSecureConfig		  = new JSecureConfig();
		if($JSecureConfig->mpsendemail != '0')
			$result	= $this->sendmail($JSecureConfig, $modifiedFieldName);
		
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

						case 'enableMasterPassword':
						if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $enableMasterPassword[1] :  $enableMasterPassword[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
						break;
							
					case 'include_basic_confg':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							
							$val = ($newValue[$currentKeyName] !=0) ? $include_basic_confg[1] :  $include_basic_confg[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;
					case 'include_image_secure':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							
							$val = ($newValue[$currentKeyName] !=0) ? $include_image_secure[1] :  $include_image_secure[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;	
                                                        
                                        case 'include_country_block':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;
						
							
							$val = ($newValue[$currentKeyName] !=0) ? $include_country_block[1] :  $include_country_block[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;
                                        case 'include_change_db_prefix':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;
						       $val = ($newValue[$currentKeyName] !=0) ? $include_change_db_prefix[1] :  $include_change_db_prefix[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;
                                        case 'include_whois':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;
						       $val = ($newValue[$currentKeyName] !=0) ? $include_whois[1] :  $include_whois[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;
                                                        
                                        case 'include_user_key':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
								
								case 'include_user_key':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;
						
							
							$val = ($newValue[$currentKeyName] !=0) ? $include_user_key[1] :  $include_user_key[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;
						
                                        case 'include_adminpwdpro':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $include_adminpwdpro[1] :  $include_adminpwdpro[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
						break;	
							
                                        case 'include_mail':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $include_adminpwdpro[1] :  $include_adminpwdpro[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;

                                        case 'include_ip':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $include_ip[1] :  $include_ip[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;
                                        case 'include_mastermail':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $include_mastermail[1] :  $include_mastermail[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
						break;		
							
					case 'include_logincontrol':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $include_logincontrol[1] :  $include_logincontrol[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
						break;	
					case 'include_metatags':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $include_metatags[1] :  $include_metatags[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
						break;		
					case 'include_purgesessions':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
							
							$val = ($newValue[$currentKeyName] !=0) ? $include_purgesessions[1] :  $include_purgesessions[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;	
					case 'include_log':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $include_log[1] :  $include_log[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;	
					case 'include_showlogs':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $include_showlogs[1] :  $include_showlogs[0];
							$ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;								
					case 'include_directorylisting':
							if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $include_directorylisting[1] :  $include_directorylisting[0];
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
			
			//JUtility::sendMail($fromEmail, $fromName, $to, $subject, $body,1);
			$headers = 'From: '. $fromName . ' <' . $fromEmail . '>';
			//mail($to, $subject, $body, $headers);
			$return = JFactory::getMailer()->sendMail($fromEmail, $headers, $to, $subject, $body,1);
			if ($return !== true) {
			return new JException(JText::_('COM_JSECURE_MAIL_FAILED'), 500);
		 }	
		 }	
	}   
}

?>