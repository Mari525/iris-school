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
/* No direct access*/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport('joomla.html.pane');

class jsecureViewAutoban extends JViewLegacy {
    function display($tpl=null){

		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		require_once($configFile);
		$JSecureConfig = new JSecureConfig();
		
		
		$this->assignRef('JSecureConfig',$JSecureConfig);
		
		$model = $this->getModel('jsecurelog');
		$honeypotapikey = $model->getHoneyPotApiKey();
		
		$spamIpList = $model->getSpamIpList();
				
		
		$this->assignRef('honeypotapikey',$honeypotapikey);
		$this->assignRef('spamip',$spamIpList);
		
		/*$pane = JPane::getInstance('Tabs', array(), true);
		$this->assignRef('pane',$pane);*/
		
		$this->addToolbar();

		
		parent::display($tpl);
	}
	
	 protected function addToolbar()
	{
		
		JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
		
		
			JToolBarHelper::apply('apply');
			JToolBarHelper::save('save');
			JToolBarHelper::cancel('cancel');
			JToolBarHelper::preferences('com_jsecure');
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
 			$link = 'index.php?option=com_jsecure&task=autoban';
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
			$link = "index.php?option=com_jsecure&task=ip";
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
		$config_array['publish']	                  = $oldValue->publish;
		$config_array['key']                          =  $oldValue->key;
		$config_array['passkeytype']	             =  $oldValue->passkeytype;
		$config_array['countryblock']	             =  $oldValue->countryblock;
		$config_array['countryblock_frontend']	  =  $oldValue->countryblock_frontend;
		$config_array['country_front_custom_path']	  =  $oldValue->country_front_custom_path;
		$config_array['countryfrnt_options']	  =  $oldValue->countryfrnt_options;
		$config_array['options']                     =  $oldValue->options; 
		$config_array['custom_path']				 =  $oldValue->custom_path;
		$config_array['captchapublish']			= $oldValue->captchapublish;
		$config_array['publishemailcheck']			= $oldValue->publishemailcheck;
		$config_array['publishevent']			= $oldValue->publishevent;
		$config_array['blacklistemail']			= $oldValue->blacklistemail;
		$config_array['publishlogdb']			= $oldValue->publishlogdb;
		$config_array['publishforumcheck']			= $oldValue->publishforumcheck;
		$config_array['forumfrequency']			= $oldValue->forumfrequency;
		$config_array['imageSecure']    		= $oldValue->imageSecure;	
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
		$config_array['abip']                    = JRequest::getVar('abip', '','post', 'string');
		$config_array['ablist']                  = JRequest::getVar('ablist', '','post', 'string');
		$config_array['abiplist']                = JRequest::getVar('abiplist', '','post', 'string');
		$config_array['abiptrylist']             = JRequest::getVar('abiptrylist', '','post', 'publishemailcheck');
		$config_array['spamip']                  = JRequest::getVar('spamip','','post', 'int');
		$config_array['spamlist']                = JRequest::getVar('spamlist', '','post', 'string');
		$config_array['allowedthreatrating']     = JRequest::getVar('allowedthreatrating', '','post', 'int');
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
		$config_array['adminType'] = $oldValue->adminType ;
		$config_array['delete_log']  = $oldValue->delete_log; //JRequest::getVar('delete_log', '0', 'post', 'int');$oldValue->custom_path;

		$modifiedFieldName	=$this->checkModifiedFieldName($config_array, $oldValue, null, null , null );
		
		/*---Code to insert Honey Pot Api key in database----*/
		
		
		$model 	= $this->getModel( 'jsecurelog' );
		
		$msg = JText::_('HONEY_POT_API_KEY');
		
							
		$insertCaptchaKey = $model->insertHoneyPotApiKey($msg,$post['honeypotapikey']);	
		
		/*---Code to insert Honey Pot Api key in database----*/
		
		$config->loadArray($config_array);
		
		$fname = JPATH_COMPONENT_ADMINISTRATOR.'/params.php';
		 
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
	
	function autobanip($abip,$oldValue)
	{
	jimport('joomla.filesystem.file');
	$config	  = new JRegistry('JSecureConfig');
	$app        = JFactory::getApplication();
		
	    $basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		
		$xml	    = $basepath.'/com_jsecure.xml';
		
		require_once($configFile);
		
		if(! is_writable($configFile))
		{
			$link = "index.php?option=com_jsecure&task=ip";
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
	
	
		$newbiplist = '';
		if($oldValue->ablist != '')                      // appending ip Black List
		{
		$newbiplist = $oldValue->ablist . "\n" . $abip;
		}
		else $newbiplist = $abip;
		
		/*//$newbiplist = str_replace("\n\n", '', $newbiplist);*/
		$newbiplist = preg_replace('/(\r\n|\n|\r){1,}/', "$1", $newbiplist); 
		/*checks and replaces two or more newline characters with one newline characters*/
		
	
	    $config_array['publish']	            =  $oldValue->publish;
		$config_array['key']                    =  $oldValue->key;
		$config_array['passkeytype']	        =  $oldValue->passkeytype;
		$config_array['countryblock']	        =  $oldValue->countryblock;
		$config_array['countryblock_frontend']	 =  $oldValue->countryblock_frontend;
		$config_array['country_front_custom_path']	  =  $oldValue->country_front_custom_path;
		$config_array['countryfrnt_options']	  =  $oldValue->countryfrnt_options;
		$config_array['options']                =  $oldValue->options; 
		$config_array['custom_path']			=  $oldValue->custom_path;
		
		$config_array['captchapublish']			= $oldValue->captchapublish;
		$config_array['imageSecure']    		= $oldValue->imageSecure;	
		$config_array['enableMasterPassword']    = $oldValue->enableMasterPassword;
		$config_array['master_password']         = $oldValue->master_password;
		$config_array['include_basic_confg']     = $oldValue->include_basic_confg;
		$config_array['include_image_secure']   = $oldValue->include_image_secure;
		$config_array['include_user_key']        = $oldValue->include_user_key;
		$config_array['include_country_block']        = $oldValue->include_country_block;
		$config_array['include_change_db_prefix']   = $oldValue->include_change_db_prefix;
		$config_array['include_whois']        = $oldValue->include_whois;
		$config_array['include_adminpwdpro']     = $oldValue->include_adminpwdpro;
		$config_array['include_mail']            = $oldValue->include_mail;
		$config_array['include_ip']              = $oldValue->include_ip;
		$config_array['include_mastermail']      = $oldValue->include_mastermail;
		$config_array['include_adminid']         = $oldValue->include_adminid;
		$config_array['include_logincontrol']    = $oldValue->include_logincontrol;
		$config_array['include_metatags']        = $oldValue->include_metatags;
		$config_array['include_purgesessions']	 = $oldValue->include_purgesessions;
		$config_array['include_log']             = $oldValue->include_log;
		$config_array['include_showlogs']        = $oldValue->include_showlogs;
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
		$config_array['ablist']                  = $newbiplist;
		$config_array['abiplist']                = $oldValue->abiplist;
		$config_array['abiptrylist']             = $oldValue->abiptrylist;
		$config_array['spamip']                  = $oldValue->spamip;  //JRequest::getVar('spamip','','post', 'int');
		$config_array['spamlist']                = $oldValue->spamlist; //JRequest::getVar('spamlist', '','post', 'string');
		$config_array['allowedthreatrating']     = $oldValue->allowedthreatrating; //JRequest::getVar('allowedthreatrating', '','post', 'int');
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
		$config_array['adminType']               = $oldValue->adminType ;
		$config_array['delete_log']              = $oldValue->delete_log; //JRequest::getVar('delete_log', '0', 'post', 'int');
		//$oldValue->custom_path;

		//$modifiedFieldName	=$this->checkModifiedFieldName($config_array, $oldValue, $JSecureCommon, $keyvalue, $masterkeyvalue);
		$modifiedFieldName	=$this->checkModifiedFieldName($config_array, $oldValue, null , null , null );

		
		$config->loadArray($config_array);
		
		$fname = JPATH_BASE .'/components/com_jsecure/params.php';
		 
		if (JFile::write($fname, $config->toString('PHP', array('class' => 'JSecureConfig','closingtag' => false)))) 
			$msg = JText::_('The Configuration Details have been updated');
		 else 
			$msg = JText::_('ERRORCONFIGFILE');
	
		if( $modifiedFieldName != ''){
			$basepath   = JPATH_BASE .'/components/com_jsecure/models/jsecurelog.php';
			require_once($basepath);
			$model = new jSecureModeljSecureLog();
			$change_variable = str_replace('<br/>', '\n',  $modifiedFieldName); 
		
			$insertLog = $model ->insertLog('JSECURE_EVENT_CONFIGURATION_FILE_CHANGED', $change_variable);
		}
		$JSecureConfig		  = new JSecureConfig();
		if($JSecureConfig->mpsendemail != '0')
			$result	= $this->sendmail($JSecureConfig,  $modifiedFieldName);
	}
	
	function checkModifiedFieldName($newValue, $oldValue, $JSecureCommon, $keyvalue=null, $masterkeyvalue=null){

	$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
	$commonFile	= $basepath.'/common.php';
	$ModifiedValue= '';
	
	require_once($commonFile);
	
		foreach($newValue as $key){
			$currentKeyName =  key($newValue);
		
			if(isset($oldValue)){
			 
			 if(array_key_exists($currentKeyName, $oldValue)){
			 	$result=($newValue[$currentKeyName] == $oldValue->$currentKeyName) ? '1' : '0';
				
				if(!$result){
			
				if( !isset($JSecureCommon[$currentKeyName])  ||  !isset($newValue[$currentKeyName])  )
				{
					continue;
				}
				
				
					switch($currentKeyName){
		
						
						case 'iplistB':
							$IPB     = explode("\n",$newValue[$currentKeyName]);
							$iplistB = implode(", ",$IPB);
		
							echo $ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $iplistB . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $iplistB . '<br/>');
							break;
							
						case 'iplistW':
							$IPW     = explode("\n",$newValue[$currentKeyName]);
							$iplistW = implode(", ",$IPW);
		
							echo $ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $iplistW . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $iplistW . '<br/>');
							break;	
						case 'iptype':
							$val = ($newValue[$currentKeyName] !=0) ? $iptype[1] :  $iptype[0];
							
							echo $ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $val . '<br/>');
							break;
				
						default:
							echo $ModifiedValue = ($ModifiedValue != '') ? ($ModifiedValue . $JSecureCommon[$currentKeyName] . ' = ' . $newValue[$currentKeyName] . '<br/>') : ( $JSecureCommon[$currentKeyName] . ' = ' . $newValue[$currentKeyName] . '<br/>');
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
			$headers = '';
			
			//JUtility::sendMail($fromEmail, $fromName, $to, $subject, $body,1);
			//$headers .= 'From: '. $fromName . ' <' . $fromEmail . '>';
			
			JFactory::getMailer()->sendMail($fromEmail, $fromName, $to, $subject, $body,1);
			
			//mail($to, $subject, $body, $headers);
		 }	
	}



}