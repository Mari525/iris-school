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

class jsecureViewImagesecure extends JViewLegacy {
	
	
	
	function display($tpl=null){
		
		
		$this->addToolbarlist();
		parent::display($tpl);
	}
	
    function addToolbarlist(){
		
		JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
		JToolBarHelper::apply('applyimagesecure');
		JToolBarHelper::save('saveimagesecure');
		JToolBarHelper::cancel('closeuserkey','Close');
		JToolbarHelper::preferences('com_jsecure');
		
		
	}
	
	
	/*refer the code*/
	function saveImageSecureDetails(){
		$app = &JFactory::getApplication();
		$result = $this->saveDetails();
		
		/*upload secure image - START*/
		if(empty($_FILES['Secureimage']['error'])){
		
		
		//--
			$fileType = $_FILES['Secureimage']['type'];
			$fileExplode = explode("/",$fileType);
			$fileExtension = $fileExplode[1];
		
			
			$image_extensions_allowed = array('jpg', 'jpeg', 'png', 'gif','bmp');
			
			
			
				if(!in_array($fileExtension, $image_extensions_allowed))
				{
					$msg  = "Image must be in one of the formats(jpg, jpeg, png, gif,bmp)";
	 				JError::raiseWarning(0,$msg, 'Warning');
					$valid = 0;
				}
				else{
				$valid = 1;
				}
		
		//--
		
		
		}
		if(empty($_FILES['Secureimage']['error']) && isset($valid) && $valid == 1){
		
		$fileType = $_FILES['Secureimage']['type'];
		$fileExplode = explode("/",$fileType);
		$fileExtension = $fileExplode[1];
		}
		
		if(empty($_FILES['Secureimage']['error']) && isset($valid)  && $valid == 1){
		
		foreach(glob('../administrator/components/com_jsecure/images/secureimage/*.*') as $file)
		if(is_file($file))
        @unlink($file);
		
		$moved = move_uploaded_file($_FILES['Secureimage']['tmp_name'], '../administrator/components/com_jsecure/images/secureimage/primary_image.'.$fileExtension);
		}
		
		
		/*upload secure image - END*/
		
 		if($result  && isset($valid)  && $valid == 1){
 			$link = 'index.php?option=com_jsecure';
 			$msg  = 'Secure Image details saved successfully!';
 			$app->redirect($link,$msg,'MESSAGE');
 	    }
		else if($result && !isset($valid)){
			$link = 'index.php?option=com_jsecure';
 			$msg  = 'Secure Image details saved successfully!';
 			$app->redirect($link,$msg,'MESSAGE');
		}
		else if($result && isset($valid)  && $valid == 0){
		$link = 'index.php?option=com_jsecure';
		$app->redirect($link);
		}
 	}
	
 	function applyImageSecureDetails(){
		$app = &JFactory::getApplication();
		$result = $this->saveDetails();
		
		/*upload secure image - START*/
		if(empty($_FILES['Secureimage']['error'])){
		
		
		//--
			$fileType = $_FILES['Secureimage']['type'];
			$fileExplode = explode("/",$fileType);
			$fileExtension = $fileExplode[1];
		
			
			$image_extensions_allowed = array('jpg', 'jpeg', 'png', 'gif','bmp');
			
			
			
				if(!in_array($fileExtension, $image_extensions_allowed))
				{
					$msg  = "Image must be in one of the formats(jpg, jpeg, png, gif,bmp)";
	 				JError::raiseWarning(0,$msg, 'Warning');
					$valid = 0;
				}
				else{
				$valid = 1;
				}
		
		//--
		
		
		}
		if(empty($_FILES['Secureimage']['error']) && isset($valid) && $valid == 1){
		
		$fileType = $_FILES['Secureimage']['type'];
		$fileExplode = explode("/",$fileType);
		$fileExtension = $fileExplode[1];
		}
		
		if(empty($_FILES['Secureimage']['error']) && isset($valid)  && $valid == 1){
		
		foreach(glob('../administrator/components/com_jsecure/images/secureimage/*.*') as $file)
		if(is_file($file))
        @unlink($file);
		
		$moved = move_uploaded_file($_FILES['Secureimage']['tmp_name'], '../administrator/components/com_jsecure/images/secureimage/primary_image.'.$fileExtension);
		}
		
		
		/*upload secure image - END*/
		
 		if($result  && isset($valid)  && $valid == 1){
 			$link = 'index.php?option=com_jsecure&task=imageSecure';
 			$msg  = 'Secure Image details saved successfully!';
 			$app->redirect($link,$msg,'MESSAGE');
 	    }
		else if($result && !isset($valid)){
			$link = 'index.php?option=com_jsecure&task=imageSecure';
 			$msg  = 'Secure Image details saved successfully!';
 			$app->redirect($link,$msg,'MESSAGE');
		}
		else if($result && isset($valid)  && $valid == 0){
		$link = 'index.php?option=com_jsecure&task=imageSecure';
		$app->redirect($link);
		}
 	}
	
 	function saveDetails(){	
 		
		jimport('joomla.filesystem.file');	
		$app           = JFactory::getApplication();
		$option		= JRequest::getVar('option', '', '', 'cmd');
		$post       = JRequest::get( 'post' );
		
		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		
		$xml	    = $basepath.'/com_jsecure.xml';
		
		require_once($configFile);
		
		if(! is_writable($configFile)){
			$link = "index.php?option=com_jsecure";
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

		$config = new JRegistry('JSecureConfig');
		$oldValue = new JSecureConfig();
		
		
		$config_array = array();
		
		
		$config_array['publish']	            = $oldValue->publish;
		$config_array['publishemailcheck']			= $oldValue->publishemailcheck;
		$config_array['publishevent']			= $oldValue->publishevent;
		$config_array['blacklistemail']			= $oldValue->blacklistemail;
		$config_array['publishlogdb']			= $oldValue->publishlogdb;
		$config_array['publishforumcheck']			= $oldValue->publishforumcheck;
		$config_array['forumfrequency']			= $oldValue->forumfrequency;
		$config_array['key']                    = $oldValue->key;
		$config_array['passkeytype']	        = $oldValue->passkeytype;
		$config_array['countryblock']	             =  $oldValue->countryblock;
		$config_array['countryblock_frontend']	             =  $oldValue->countryblock_frontend;
		$config_array['country_front_custom_path']	  =  $oldValue->country_front_custom_path;
		$config_array['countryfrnt_options']	  =  $oldValue->countryfrnt_options;
		$config_array['options']                = $oldValue->options; 
		$config_array['custom_path']			= $oldValue->custom_path;
		$config_array['captchapublish']			= $oldValue->captchapublish;
		$config_array['imageSecure']    		= JRequest::getVar('publish');			//new field imageSecure added to parameters
		
		$config_array['enableMasterPassword']   = $oldValue->enableMasterPassword;
		$config_array['master_password']        = $oldValue->master_password;
		$config_array['include_basic_confg']    = $oldValue->include_basic_confg;
		$config_array['include_email_scan']    = $oldValue->include_email_scan;
		$config_array['include_image_secure']   = $oldValue->include_image_secure;
		$config_array['include_user_key']       = $oldValue->include_user_key;
		$config_array['include_country_block']       = $oldValue->include_country_block;
		$config_array['include_change_db_prefix']       = $oldValue->include_change_db_prefix;
		$config_array['include_whois']       = $oldValue->include_whois;
		$config_array['include_adminpwdpro']    = $oldValue->include_adminpwdpro;
		$config_array['include_mail']           = $oldValue->include_mail;
		$config_array['include_ip']             = $oldValue->include_ip;
		$config_array['include_mastermail']     = $oldValue->include_mastermail;
		$config_array['include_adminid']        = $oldValue->include_adminid;
		$config_array['include_logincontrol']   = $oldValue->include_logincontrol;
		$config_array['include_metatags']       = $oldValue->include_metatags;
		$config_array['include_purgesessions']	= $oldValue->include_purgesessions;
		$config_array['include_log']            = $oldValue->include_log;
		$config_array['include_showlogs']       = $oldValue->include_showlogs;
		$config_array['include_directorylisting']= $oldValue->include_directorylisting;
		$config_array['include_component_protection']= $oldValue->include_component_protection;
		$config_array['include_autobanip']= $oldValue->include_autobanip;
		$config_array['include_graph']           = $oldValue->include_graph;
		$config_array['sendemail']				 = $oldValue->sendemail;
		$config_array['sendemaildetails']		 = $oldValue->sendemaildetails;
		$config_array['emailid']				 = $oldValue->emailid;
		$config_array['emailsubject']			 = $oldValue->emailsubject;
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
		$config_array['mpsendemail']			 = $oldValue->mpsendemail;
		$config_array['mpemailsubject']			 = $oldValue->mpemailsubject;
		$config_array['mpemailid']				 = $oldValue->mpemailid;
		$config_array['login_control']			 = $oldValue->login_control;
		$config_array['adminpasswordpro']		 = $oldValue->adminpasswordpro;
		$config_array['metatagcontrol']		     = $oldValue->metatagcontrol;
		$config_array['metatag_generator']		 = $oldValue->metatag_generator;
		$config_array['metatag_keywords']		 = $oldValue->metatag_keywords;
		$config_array['metatag_description']	 = $oldValue->metatag_description;
		$config_array['metatag_rights']		     = $oldValue->metatag_rights;
		$config_array['adminType']				 = $oldValue->adminType;
		$config_array['delete_log']				 = $oldValue->delete_log;
		
		

//		$modifiedFieldName	=$this->checkModifiedFieldName($config_array, $oldValue, $JSecureCommon, $keyvalue, $masterkeyvalue);
		$modifiedFieldName	=$this->checkModifiedFieldName($config_array, $oldValue, null , null , null );
		
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
					
						case 'imageSecure':
						if( !isset($newValue[$currentKeyName]) || !isset($JSecureCommon[$currentKeyName])  )
								break;	
						
							$val = ($newValue[$currentKeyName] !=0) ? $imageSecure[1] :  $imageSecure[0];
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
	
	
	
}

?>