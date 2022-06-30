<?php 
/**
 * jSecure Authentication plugin for Joomla!
 * jSecure Authentication extention prevents access to administration (back end)
 * login page without appropriate access key.
 *
 * @author      $Author: Ajay Lulia $
 * @copyright   Joomla Service Provider - 2016
 * @package     jSecure3.5
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id: jsecure.php  $
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
error_reporting(E_ALL & ~E_STRICT);
jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');
$lang = JFactory::getLanguage();

if($lang->getName()=="English (United Kingdom)")
{
	JFactory::getApplication()->loadLanguage(null, 'plg_system_jsecurelite');
}

require_once('jsecure/jsecure.class.php');

$basepath     = JPATH_ADMINISTRATOR .'/components/com_jsecure';
$configFile	  = $basepath.'/params.php';
				
require_once($configFile);		

class plgSystemJSecure extends JPlugin {
	 private static $_configuration;
	
	function plgSystemCanonicalization(& $subject, $config) {
		
		parent :: __construct($subject, $config);
	}	
	
	function onUserLogout(){
	
	 self::$_configuration = 1;
	 echo self::$_configuration;
	 $session    = JFactory::getSession();
	 $this->params->logout = true;
	}
	
	function onUserLogin($user, $options = array()){

		$JSecureConfig = new JSecureConfig();
	 	$publish  = $JSecureConfig->publish;
		if(!$publish) {			
			return true;
		}
		$app           = JFactory::getApplication();
		
		if ($app->isAdmin()) {	
			$session    = JFactory::getSession();
			$EnteredKey = $session->get('UserKey');
			if($JSecureConfig->key != md5(base64_encode($EnteredKey))){
			$userkey = $this->validate_userkey($user['username'],$EnteredKey); 	
			}
			if($JSecureConfig->captchapublish){
			
			$this->validateCaptcha();
			}
		}		
		if($JSecureConfig->login_control)
		{
			$res = $this->check_user_login($user['username']);
			if(count($res)>0)
			{
				  $app= JFactory::getApplication();
				  $message = JText::_("Username: ".$user['username']." is already logged in from another site.You can't login again.");
				  $link = JURI::root()."administrator/index.php?option=com_login";
				  $app->redirect($link,$message,'error');
			}
		}
	}
	
	function onAfterDispatch() {
		
		
	    $db = JFactory::getDBO();
		$sql = "SELECT Value from #__apikeys where Name='Re-Captcha Site Key'";
		$db->setQuery($sql);
		
		$rows = $db->loadObjectList();
		
		$option = JRequest::getVar('option');
		
		$JSecureConfig = new JSecureConfig();
	
		if($JSecureConfig->captchapublish && $JSecureConfig->publish){
		
			if($option == "com_login"){
		?>
	
		    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
			<script type="text/javascript">
			$(document).ready(function(){
			  $(".btn").css({"padding-top": "7px"});
			  $("#mod-login-username").css({"height":"27px"});
			  $("#mod-login-password").css({"height":"27px"});
			  $("#mod-login-secretkey").css({"height":"27px"});
			  $("#element-box").css({"width":"301px"});
		   
			var html = '<div class="g-recaptcha" data-sitekey="<?php echo $rows[0]->Value;?>"></div>';
		   
			  
			$(".btn-group").prepend(html); 
		   
			});
			</script> 
	
		<?php
			}
		}

		
		$session    = JFactory::getSession();
		$logout = self::$_configuration;
		$app           = JFactory::getApplication();
		
		if ($app->isAdmin()) 
		{
			$extension = JRequest::getVar('extension', '');
			$com = JRequest::getVar('option', '');
			
            //$protected = jsecure::checkComponentprotect($com,$extension);
			$pro = new jsecure();
			$protected = $pro->checkComponentprotect($com,$extension);

		$config        = new JConfig();
		$JSecureConfig = new JSecureConfig();
		$app           = JFactory::getApplication();
		$path          = '';
		$path         .= $JSecureConfig->options == 1 ? JURI::root().$JSecureConfig->custom_path : JURI::root();
		$jsecure 	   =  new jsecure();
		$publish       = $JSecureConfig->publish;
		
		if(!$publish){			
			return true;
		}


		$session    = JFactory::getSession();
		$checkedKey = $session->get('jSecureAuthentication');

		if(!empty($checkedKey)){			
			return true;
		}
		
		$submit       = JRequest::getVar('submit', '');
		$passkey      = $JSecureConfig->key;

		$authenticate  =  JRequest::getVar('authenticate');
		if($authenticate == 'submit'){
		$SecureImageAuthentication = jSecure::SecureImageAuthentication($_FILES,$JSecureConfig);
		if($SecureImageAuthentication == true){
		$session->set('jSecureAuthentication', 1);
		$link = JURI::root()."administrator/index.php?option=com_login";
		$app->redirect($link);
		}
		else{
		$app->redirect($path);
		}
		}
		
		
		
		
		if($submit == 'submit'){
		
			$resultFormAction = jsecure::formAction($JSecureConfig);
			
			/*Condition for country blocking  */
			
			if(!empty($resultFormAction)){
				$countryblock =$JSecureConfig->countryblock;
				if($countryblock){
					$countrydetail = jSecure::checkCountryBlock();
					$ip = $countrydetail[0];
					$countrycode =$countrydetail[1];
					$countryname =$countrydetail[2];
					
					if($countrycode!="" && $countryname!="")
					
					{
					
					$result = $this->get_blocked_countries($countrycode,$ip,$countryname);
					$country_status =$result[0]->published;
					
					if($country_status == 0)
					{
						  $app= JFactory::getApplication();
						  $link = JURI::root();
						  $app->redirect($link);
					}
				}
				
				}		
            }			
			
			/*This function checks user ip is spam or not by project honeypot*/
			
			$spamsettings = $JSecureConfig->spamip;
									
			if($spamsettings)
			jsecure::checkSpammer($JSecureConfig);
			
			/*This function checks user ip is spam or not by project honeypot*/
			
			if(!empty($resultFormAction)){
				if($JSecureConfig->imageSecure == 1){
				jsecure::SecureImageForm();
				}
				else{
				$session->set('jSecureAuthentication', 1);
				$link = JURI::root()."administrator/index.php?option=com_login";
				$app->redirect($link);
				}
				
			} else {
				$app->redirect($path);
			}
		}
		
		$resultBloackIPs = jsecure::checkIps($JSecureConfig);
		
		if(!$resultBloackIPs){
			$app->redirect($path);
		}
		
		$task        = $JSecureConfig->passkeytype;
	
		switch($task){
			case 'form':
		
			$resultFormAction = jsecure::formAction($JSecureConfig);
			if(empty($resultFormAction)){
			jsecure::displayForm();
			}
			exit;
			break;

			case 'url':
		
			/*Function to check country block */
			$urlKeyResponse = jsecure::checkUrlKey($JSecureConfig);	
			if(!empty($urlKeyResponse)){
			$countryblock =$JSecureConfig->countryblock;
			
			if($countryblock){
			$countrydetail = jSecure::checkCountryBlock();
			$ip = $countrydetail[0];
			$countrycode =$countrydetail[1];
			$countryname =$countrydetail[2];
			if($countrycode!="" && $countryname!=""){
			$result = $this->get_blocked_countries($countrycode,$ip,$countryname);
		
		
			$country_status =$result[0]->published;
			
			if($country_status == 0)
			{
				  $app= JFactory::getApplication();
				  $link = JURI::root();
				  $app->redirect($link);
			}
			
			}
			}
		}
			/*This function checks user ip is spam or not by project honeypot*/
			
			$spamsettings = $JSecureConfig->spamip;
							
			if($spamsettings)
			jsecure::checkSpammer($JSecureConfig);
			
			/*This function checks user ip is spam or not by project honeypot*/
			
			$session    = JFactory::getSession();
			//$resultUrlKey = jsecure::checkUrlKey($JSecureConfig);	
			if((!empty($urlKeyResponse))||($country_status != 0)){
				if($JSecureConfig->imageSecure == 1){
				
				jsecure::SecureImageForm();
				}
				else{
				
				$session->set('jSecureAuthentication', 1);
				return true;
				}
			
			
			
			}
			else{
				
				$app->redirect($path);
				}
			exit;
			break;	
			
			default:
				$session    = JFactory::getSession();
				$resultUrlKey = jsecure::checkUrlKey($JSecureConfig);
				if(!empty($resultUrlKey)){
		
					$session->set('jSecureAuthentication', 1);
					return true;
				  } 
				
				else {
					$app->redirect($path);
				}
			break;
		}

	  }
	  else
	  {
		/* starts meta tag control for front side */
		$JSecureConfig = new JSecureConfig();
		$document = JFactory::getDocument();
		$publish       = $JSecureConfig->publish;
		if(!$publish)
		{			
			return true;
		}
   
        if($JSecureConfig->metatagcontrol)
		{
           // Set global info in callback function
           $global_info['sitename'] = $app->getCfg('sitename');
           $document_info['title'] = $document->getTitle();
           $document_info['description'] = $document->getDescription();
           $document_info['keywords'] = $document->getMetaData('keywords');
           $document_info['author'] = $document->getMetaData('author');
		   $document_info['rights'] = $document->getMetaData('rights');
           $document_info['generator'] = $document->getGenerator();
            
		   $customgenerator = $document->getMetaData('generator');
			if($JSecureConfig->metatag_generator)
			{
               $document->setGenerator(str_replace('"', '&quot;', $JSecureConfig->metatag_generator)); 
			}
			if($JSecureConfig->metatag_keywords)
			{
               $document->setMetaData('keywords', str_replace('"', '&quot;', $JSecureConfig->metatag_keywords)); 
			}
			if($JSecureConfig->metatag_description)
			{
				 $document->setDescription(str_replace('"', '&quot;',$JSecureConfig->metatag_description));
           	}
			if($JSecureConfig->metatag_rights)
			{
			   $document->setMetaData('rights', str_replace('"', '&quot;', $JSecureConfig->metatag_rights));
            }
	
		}
		else
		{
           return;
		}
	  }
	
	}
	
	  function get_blocked_countries($countrycode,$ip,$countryname)
  {

    $db = JFactory::getDBO(); 
    $query = "SELECT published from #__jsecure_countries WHERE country_code='".$countrycode."'";
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	$countrystatus = $rows[0]->published;
	

	if($countrystatus ==0){
	
	$countryname= addslashes($countryname);
	$date =date("Y-m-d H:i:s");
	$sql = "INSERT into #__jsecure_country_block_logs(ip,country,date) Value('".$ip."','".$countryname."','".$date."')" ;
	$db->setQuery($sql);
	$result = $db->query();
	
	}

	return $rows;
  }

  function check_user_login($username)
  {
    $db = JFactory::getDBO(); 
	$query = "SELECT * from #__session WHERE username='".$username."' AND userid!=0 ";
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	return $rows;
  }
  
  function validate_userkey($username,$eneteredKey)
  {
	$db = JFactory::getDBO(); 
	$query = "SELECT user_id, ". $db->quoteName('key') .", start_date, end_date, status from #__jsecure_keys a inner join #__users b on a.user_id = b.id WHERE b.username="."'".$username."'";
	$db->setQuery($query);
	$record = $db->loadObject();
	$JSecureConfig = new JSecureConfig();
	$sendemaildetails = $JSecureConfig->sendemaildetails;
	
	  if(!empty($record)) {
		
		if($record->key == md5($eneteredKey)) {
			
			if($record->status == 1) {
				$current = strtotime("today");
				if($record->start_date <= $current && $record->end_date >= $current) {
				$session = JFactory::getSession();
				$session->set('LogUserId',$record->user_id);
				
				$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
				$logFile	= $basepath.'/jsecurelog.php';
				require_once($logFile);
				$model = new jSecureModeljSecureLog();
				$model->correctHits();
				$change_variable = 'User Key = '.$eneteredKey;
				$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_CORRECT_USER_KEY',$change_variable);
				
				if($sendemaildetails == '1' || $sendemaildetails == '3') {
				$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,$eneteredKey,$success=null,1): '';
				}
				
				return true;
				
				}
				else {
				$session = JFactory::getSession();
				$session->set('LogUserId',$record->user_id);
				$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
				$logFile	= $basepath.'/jsecurelog.php';
				require_once($logFile);
				$model = new jSecureModeljSecureLog();
				$change_variable = 'Expired User Key = '.$eneteredKey;
				$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_EXPIRED_KEY', $change_variable);
				$model->incorrectHits();
				
				if($sendemaildetails == '2' || $sendemaildetails == '3') {
				$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,$eneteredKey,$success=null,2): '';
				}
				
				$session->destroy();
				return false;
				}
			}
			else {
				  
				$session = JFactory::getSession();
				$session->set('LogUserId',$record->user_id);
				$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
				$logFile	= $basepath.'/jsecurelog.php';
				require_once($logFile);
				$model = new jSecureModeljSecureLog();
				$change_variable = 'Disabled User Key = '.$eneteredKey;
				$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_DISABLED_KEY', $change_variable);
				$model->incorrectHits();
				
				if($sendemaildetails == '2' || $sendemaildetails == '3') {
				$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,$eneteredKey,$success=null,3): '';
				}
				
				$session->destroy();
				return false;
			}
		}
		else {
				$session = JFactory::getSession();
				$session->set('LogUserId',$record->user_id);
				$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
				$logFile	= $basepath.'/jsecurelog.php';
				require_once($logFile);
				$model = new jSecureModeljSecureLog();
				$change_variable = 'Incorrect User Key & User Account combination using User Key = '.$eneteredKey;
				$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_INCORRECT_COMBINATION', $change_variable);
				$model->incorrectHits();
				
				if($sendemaildetails == '2' || $sendemaildetails == '3') {
				$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,$eneteredKey,$success=null,4): '';
				}
				
				$session->destroy();
				return false;
		}
	 }	
     else {
	 $session = JFactory::getSession();
	 $db = JFactory::getDBO(); 
	 $query = "SELECT id from #__users WHERE username="."'".$username."'";
	 $db->setQuery($query);
	 $user = $db->loadObject();
	 $session->set('LogUserId',$user->id);
	 $basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
	 $logFile	= $basepath.'/jsecurelog.php';
	 require_once($logFile);
	 $model = new jSecureModeljSecureLog();
	 $change_variable = 'Incorrect User Key & User Account combination using User Key = '.$eneteredKey;
	 $insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_INCORRECT_COMBINATION', $change_variable);
	 $model->incorrectHits();
	 
	 if($sendemaildetails == '2' || $sendemaildetails == '3') {
	 $JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,$eneteredKey,$success=null,4): '';
	 }
				
	 $session->destroy();
	 return false;
	}
  }
	
	
	
	function validateCaptcha(){
		
		$JSecureConfig = new JSecureConfig();
		$session = JFactory::getSession();
		$resultFormAction = jsecure::urlCaptchaformAction($JSecureConfig);

			if(!empty($resultFormAction)){
				
				return true;
				
			} else {
				$session->destroy();
				return false;
			}
	}
	
	public function onUserBeforeSave($user, $isnew, $new)
	{
		$site = JFactory::getApplication()->isSite();
		$JSecureConfig = new JSecureConfig();
		
		$JSecureConfig->bXMLAvailable = (phpversion() > '5' && class_exists('SimpleXMLElement') == true);
		
		//Check if it is site part and not administrator part and also check is user is new
		
		if ( !$site || !$isnew) {
            return true;
        }
        
        $this->params->set('current_user_action','Register');
        
        $formInfo = JRequest::getVar('jform');
        $userinfo = array(
            "fullname" => $formInfo['name'],
            "username" => $formInfo['username'],
            "email" => $formInfo['email1']
        );
		
		//Check if email scan is enabled or not
		
		if($JSecureConfig->publishemailcheck == 1){
			$spamTag = '';
			
			$spammer = $this->isUserSpammer($JSecureConfig,$userinfo,$spamTag);
			
			if(!$spammer){		
			//user is not spammer		
			return true;		
			}else{        
			// save spammer user info in db
			 jsecure::savespamuserInfo($userinfo,$JSecureConfig);
				
			}
			
			$msg = JText::_('Registration failed.Entered email id is spam.Please contact site support');
			JLog::add($msg, JLog::ERROR, 'jerror');
			$app = JFactory::getApplication();
			$app->redirect('index.php');
			$app->close();
			
			return false;
		}
		
	}
	
	/*-- This function checks if user is spammer by matching his email id. If user is spammer he won't be allowed to register on frontend ---*/
	
	function isUserSpammer($JSecureConfig,$userdata, &$spamTag){
		
		
		// Condition to not check admin user
			if ($this->isUserAdmin($userdata)) {
				return false;
			}			
			$file_get_contents = function_exists('file_get_contents');						
			$curlavailable = $this->CurlAvailable();
			$this->Spambotcheck($JSecureConfig,$curlavailable,$file_get_contents,$userdata);
			
			 if ($JSecureConfig->spambottag == false || strlen($JSecureConfig->spambottag) == 0 || strpos($JSecureConfig->spambottag,"SPAMBOT_TRUE") === false) {
            // user is not not a spammer
				$spampresent = "";
				return false;
			}
        
			// user is spammer		
			$spampresent = $JSecureConfig->spambottag;
			return true;	

	
	}
	
	function isUserAdmin($userdata){
	
	if ($userid = JUserHelper::getUserId($userdata['username'])) {
            $db = JFactory::getDbo();
            $query = 'SELECT g.id AS group_id FROM `#__usergroups` AS g LEFT JOIN `#__user_usergroup_map` AS map ON map.group_id=g.id WHERE map.user_id=' . $db->quote($userid);
			
			
			
            $db->setQuery($query);
            //A user can be member of more than one user groups
            $ugps = $db->loadObjectList();
            //check if any of this groups has admin rights
            foreach ($ugps as $ugp) {
                $groupId = $ugp->group_id;
                if (JAccess::checkGroup($groupId, 'core.admin') == 1) { // user is admin
                    return true;
                }
            }
            return false;
        }
        return false;
	
	}
	
	function CurlAvailable() {
        $ext = 'curl';
        if (extension_loaded($ext)) {
            return true;
        } else {
            return false;
        }
    }
	
	
	function Spambotcheck($JSecureConfig,$curlavailable,$file_get_contents,$userdata){
	
		
	if(!$curlavailable && !$file_get_contents){
		$JSecureConfig->spambottag =  'SPAMBOT_FALSE';
		return;
	
	}
	
	$this->verifyUserData($JSecureConfig,$userdata);
	
		if ($this->isSpamRecognized($JSecureConfig)) {
				return;
		}

		// check email against blacklisted emails in administrator
        $this->checkBlackListedEmails($JSecureConfig,$userdata);
		if ($this->isSpamRecognized($JSecureConfig)) {
            return;
        }
        
        // this is it: check against the online providers
		$this->stopForumSpamCheck($JSecureConfig,$userdata,$curlavailable);
		
		
        //$this->checkSpambotProviders();	
	
	}
	
	function stopForumSpamCheck($JSecureConfig,$userdata,$curlavailable){
	
		//If stopForumSpamCheck option is disabled then return false.
		
		if(!$JSecureConfig->publishforumcheck)	{
			return;		
		}
		
		if(!$JSecureConfig->bXMLAvailable){
			return;	
		}
		
		$forumFrequency = $JSecureConfig->forumfrequency;
		
		$stopforumurl = 'http://www.stopforumspam.com/api?email='.$userdata['email'];
		
		$checkspamonline = $this->getResponse($stopforumurl,$curlavailable);
		
		if (strpos($checkspamonline,'rate limit exceeded') !== false) {
			return;
		}
		
		if (strpos($checkspamonline, '<') !== 0) {
            return;
        }
		
		$spamxml = new SimpleXMLElement($checkspamonline);
         
		$emailFrequency = array();
		
		$emailFrequency = $spamxml->frequency;
		 
		if($emailFrequency >= $JSecureConfig->forumfrequency){
		 $spamMail = FALSE;
			 if ($spamxml->appears == 'yes') {
                    $spamMail = TRUE;
                 	
			 }
		
		}
		
		if($spamMail){
		 $JSecureConfig->spambottag =  'SPAMBOT_TRUE';
		
		}
		
	}
	
	function getResponse($stopforumurl,$curlavailable){
		
		$websitestatus = $this->checkwebsitestatus($stopforumurl,$curlavailable);
		
		if($websitestatus == false){
		
			echo 'Not able to connect to server';
			return $stopforumurl;
		
		}
		else {
            if (function_exists('file_get_contents') && ini_get('allow_url_fopen') == true) {
                // Use file_get_contents
                $stopforumurl = @file_get_contents($stopforumurl);
            } else {
                // Use cURL (if available)
                if ($curlavailable) {
                    $curl = @curl_init();
                    curl_setopt($curl, CURLOPT_URL, $stopforumurl);
                    curl_setopt($curl, CURLOPT_VERBOSE, 1);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    $stopforumurl = @curl_exec($curl);
                    curl_close($curl);
                } else {
                    $stopforumurl = 'Unable to connect to server';
                    return $stopforumurl;
                }
            }
            return $stopforumurl;
        }
		
	
	}
	
	function checkwebsitestatus($stopforumurl,$curlavailable){
	
	
	
		if ($curlavailable) {
            // check if url is working or not
            $curl = @curl_init($stopforumurl);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_FAILONERROR, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            @curl_exec($curl);
						
            if (curl_errno($curl) != 0) {
                return false;
            } else {
                return true;
            }
            curl_close($curl);
        } else {
            //curl is not loaded
            return false;
        }
	
	}
	
	function checkBlackListedEmails($JSecureConfig,$userdata){
	
	// check email against blacklisted emails in administrator
        $this->checkAdminEmailsListing($JSecureConfig,$userdata);
        if ($this->isSpamRecognized($JSecureConfig)) {
            return;
        }
	}
	
	function checkAdminEmailsListing($JSecureConfig,$userdata){
	
	if($userdata['email'] == ""){
	
	return;
	
	}
	
	$blacklistemaillist = $JSecureConfig->blacklistemail;
	
	$emailinblacklist = $this->clearEMailBlacklist($blacklistemaillist);
	
	if($emailinblacklist == ""){
	
	return;
	
	}
	
	$emailinblacklist = explode(',', $emailinblacklist);
			
	$emailcount = count($emailinblacklist);
			
		for ($i = 0; $i < $emailcount; $i++) {
		
			// check valid email domain Eg - @email.com
            $regex = '/\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            if (preg_match($regex,$emailinblacklist[$i])) {
				
				
				$emailinblacklist[$i] = trim($emailinblacklist[$i]);
				
				// echo 'User Email-->'.$userdata['email'].'<br/>';
				// echo 'Blacklist Email-->'.$emailinblacklist[$i].'<br/>';
                // valid email domain port
                if (strpos($userdata['email'], $emailinblacklist[$i]) !== false) {
												
				 // email in blacklist
                   $JSecureConfig->spambottag =  'SPAMBOT_TRUE';
				
				}
								
			}
			
		}
		
			
	}
	
	 function clearEMailBlacklist($blacklistemaillist) {
	 			 
        if ($blacklistemaillist != '') {
            //delete blanks
            $blacklistemaillist = str_replace(' ', '', $blacklistemaillist);
            //delete ',' at stringend
            while ($blacklistemaillist[strlen($blacklistemaillist) - 1] == ',') {
                $blacklistemaillist = substr($blacklistemaillist, 0, strlen($blacklistemaillist) - 1);
            }
						
        }

        return $blacklistemaillist;
    }
	
	function isSpamRecognized($JSecureConfig) {
		
        if (strpos($JSecureConfig->spambottag,'SPAMBOT_TRUE') !== false || strpos($JSecureConfig->spambottag,'SPAMBOT_FALSE') !== false) {
		
            return true;
        }
						
        return false;
    }
	
	function verifyUserData($JSecureConfig,$userdata){
	
		
		if(!$JSecureConfig->publishemailcheck){
			$userSpamEmail = '';
		}
	
		$userSpamEmail = $this->isvalidEmail($userdata['email']);
		
			
		if($userSpamEmail == ""){
		
			$JSecureConfig->spambottag = 'SPAMBOT_FALSE';
			
			return;
		
		}
	
	}
	
	function isvalidEmail($email){
	
			
	
		 if ($email != '') {
            $regex = '/^([a-zA-Z0-9_\.\-\+%])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            return preg_match($regex, $email) ? $email : '';
        }

        return '';
	
	}
	
	
	/*--- function to check whether curl is abailable or not ---*/
	
	public function onContentPrepare($context, &$row, $params, $page = 0)
	{
	
	$JSecureConfig = new JSecureConfig();	
	if($JSecureConfig->publish==1&&$JSecureConfig->countryblock==1&&$JSecureConfig->countryblock_frontend==1){
	
    $countrydetail = jSecure::checkCountryBlock();
	$ip = $countrydetail[0];
	$countrycode =$countrydetail[1];
    $countryname =$countrydetail[2];
	if($countrycode!="" && $countryname!="")
	{
	$result = $this->get_blocked_countries($countrycode,$ip,$countryname);
	
	$country_status =$result[0]->published;
	   if($country_status == 0)
	   {
	   $app= JFactory::getApplication();
	   $src = JPATH_SITE."\plugins\system\jsecure\\404.html";
			  
	  $dest = JPATH_SITE."\\404.html";
	  JFile::copy($src,$dest);
	  if(($JSecureConfig->countryfrnt_options==1)&&($JSecureConfig->country_front_custom_path != "")){
		   $path = $JSecureConfig->country_front_custom_path;
		   header("Location: http://www.$path");
		  exit;
		   
	   }else{
	  $link = JURI::root()."404.html";
	   $app->redirect($link);
	   }
	   }	
	}
	}
	}
	
  }


?>