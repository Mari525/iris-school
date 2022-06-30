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
 * @version     $Id: jsecure.class.php  $
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class jsecure {
		
	static function sendmail($JSecureConfig,$key, $success=null, $MultiKeyLogin=null){
		
		 $config   = new JConfig();
		 $to       = $JSecureConfig->emailid;	
	     $to       = ($to) ? $to :  $config->mailfrom;
		 $addresses = explode(',', $to);
		 
		 if($to){
			//$from = $config->mailfrom;
			$fromEmail  = $config->mailfrom;
			$fromName  = $config->fromname;
			$subject   = $JSecureConfig->emailsubject;
			
			$headers = 'From: '. $fromName . ' <' . $fromEmail . '>';
			
			
			if($MultiKeyLogin == null){
			switch($success){
			case 1:
				$body = JText::_($key).$_SERVER['REMOTE_ADDR'] ;
				break;
			
			default:
				$body      = JText::_( 'BODY_MESSAGE' ) .$_SERVER['REMOTE_ADDR'];
				$body	  .= " ";
				if($key=="")
				{
					$body     .= "";
				}
				else
				{
					$body     .= JText::_( 'USING_KEY' ).'"'.$key.'"';
				}	
				break;
			}
			
			}
			else if($MultiKeyLogin == 1){
			$body      = JText::_( 'JSECURE_EVENT_ACCESS_ADMIN_USING_CORRECT_USER_KEY_MAIL' ) .$_SERVER['REMOTE_ADDR'];
				$body	  .= " ";
				if($key=="")
				{
					$body     .= "";
				}
				else
				{
					$body     .= JText::_( 'USING_KEY' ).'"'.$key.'"';
				}
			}
			else if($MultiKeyLogin == 2){
			$body      = JText::_( 'JSECURE_EVENT_ACCESS_ADMIN_USING_EXPIRED_KEY_MAIL' ) .$_SERVER['REMOTE_ADDR'];
				$body	  .= " ";
				if($key=="")
				{
					$body     .= "";
				}
				else
				{
					$body     .= JText::_( 'USING_KEY' ).'"'.$key.'"';
				}
			}
			else if($MultiKeyLogin == 3){
			$body      = JText::_( 'JSECURE_EVENT_ACCESS_ADMIN_USING_DISABLED_KEY_MAIL' ) .$_SERVER['REMOTE_ADDR'];
				$body	  .= " ";
				if($key=="")
				{
					$body     .= "";
				}
				else
				{
					$body     .= JText::_( 'USING_KEY' ).'"'.$key.'"';
				}
			}
			else if($MultiKeyLogin == 4){
			$body      = JText::_( 'JSECURE_EVENT_ACCESS_ADMIN_USING_INCORRECT_COMBINATION_MAIL' ) .$_SERVER['REMOTE_ADDR'];
				$body	  .= " ";
				if($key=="")
				{
					$body     .= "";
				}
				else
				{
					$body     .= JText::_( 'USING_KEY' ).'"'.$key.'"';
				}
			}
			//$return = JFactory::getMailer()->sendMail($fromEmail, $headers, $to, $subject, $body,1);
			
			for($i=0;$i<count($addresses);$i++){
			$return = JFactory::getMailer()->sendMail($fromEmail, $headers, $addresses[$i], $subject, $body,1);
			}																												//mailing updated for multiple addresses.		
			
			if ($return !== true) {
			return new JException(JText::_('COM_JSECURE_MAIL_FAILED'), 500);
		}
		 }	
	}

	static function checkUrlKey($JSecureConfig){
		
		$session = JFactory::getSession();
		$session->set('UserKey',$_SERVER['QUERY_STRING']);
		$my = JFactory::getUser();
		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
		$logFile	= $basepath.'/jsecurelog.php';
		require_once($logFile);
		$model = new jSecureModeljSecureLog();
		if(!isset($_SERVER['HTTP_REFERER']))
                   {
				  
		if((preg_match("/administrator\/*index.?\.php$/i", $_SERVER['PHP_SELF']))) {
			
			$sendemaildetails = $JSecureConfig->sendemaildetails;
			
			if(!$my->id && $JSecureConfig->key != md5(base64_encode($_SERVER['QUERY_STRING']))) {
					
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('*');
					$query->from('#__jsecure_keys');
					$query->where($db->quoteName('key') . ' = ' . $db->quote(md5($_SERVER['QUERY_STRING'])));
					$db->setQuery($query);
					$records = $db->loadObjectlist();
					if(!empty($records)){
					return true;
					}
					else{
					if($sendemaildetails == '2' || $sendemaildetails == '3'){
						$JSecureConfig->sendemail == '1' ? jsecure::sendmail( $JSecureConfig, $_SERVER['QUERY_STRING']) : '';
					}
					$change_variable = 'Wrong Key = '.$_SERVER['QUERY_STRING']; 
			        $insertLog = $model->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_WRONG_KEY', $change_variable);
				      //insert hits value
					  if($_SERVER['QUERY_STRING']!="")
				      {
                        $model->incorrectHits();
						
						   if($JSecureConfig->abip == '1')           //autoBanIp                                                 //changes by me
				           {
					        $model->autoblockip();
					       }
					  }
					  elseif((preg_match("/administrator\/*index.?\.php$/i", $_SERVER['PHP_SELF'])))
				      {
                        $model->incorrectHits();
						
						if($JSecureConfig->abip == '1')                //autoBanIp                                            //changes by me
				           {
					        $model->autoblockip();
					       }
					  }
					return false;
					}
					
			} else {
				if($sendemaildetails == '1' || $sendemaildetails == '3'){
					$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig, 'ACCESS_ADMIN_USING_CORRECT_KEY', 1): '';
				}
					$model->correctHits();
					$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_CORRECT_KEY');
				return true;
		    }
		}
                   }
                   else{
                   	return false;
                   }
	
	}
	
	 static function formAction($JSecureConfig){

		$oriKey = JRequest::getVar('passkey','');
		if($oriKey == ''){
			return;
		}
		$oriKey = JRequest::getVar('passkey','');
		$session = JFactory::getSession();
		$session->set('UserKey',$oriKey);
		$sendemaildetails = $JSecureConfig->sendemaildetails;
		$userkey          = md5(base64_encode(JRequest::getVar('passkey','')));
		$passkey          = $JSecureConfig->key;
		if($userkey != $passkey){
			
			
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__jsecure_keys');
			$query->where($db->quoteName('key') . ' = ' . $db->quote(md5($oriKey)));
			$db->setQuery($query);
			$records = $db->loadObjectlist();
			if(!empty($records)){
			return true;
			}
			else{
			$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
			$logFile	= $basepath.'/jsecurelog.php';
			require_once($logFile);
			$model = new jSecureModeljSecureLog();
			$change_variable = 'Wrong Key = '.JRequest::getVar('passkey',''); 
			$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN', $change_variable);
			$model->incorrectHits();
				if($sendemaildetails == '2' || $sendemaildetails == '3'){
					$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,$oriKey): '';
				}
			
			   if($JSecureConfig->abip == '1')                         //autoBanIp                                   
			   	{
				$model->autoblockip();
			    }
			return false;
			}
			
			
			  
			
		} else {
			if($sendemaildetails == '1' || $sendemaildetails == '3'){
				//$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,$oriKey): '';
				$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,'ACCESS_ADMIN_USING_CORRECT_KEY',1): '';
			}
			$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
			$logFile	= $basepath.'/jsecurelog.php';
			require_once($logFile);
			$model = new jSecureModeljSecureLog();
			$model->correctHits();
			$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_CORRECT_KEY');
		  	return true;
		}
	}	

	static function checkIps($JSecureConfig){
		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
		$logFile	= $basepath.'/jsecurelog.php';
		require_once($logFile);
		$model = new jSecureModeljSecureLog();
		$ABdenyaccess = 0;
        $denyaccess = 0;
        $allowaccess = 0;
		$iptype = $JSecureConfig->iptype; //url key
		$autoban = $JSecureConfig->abip; //autoban enable/disable
		if(isset($JSecureConfig->iplistB)){
			$iplistB = $JSecureConfig->iplistB;
		}else{
			$iplistB ="";
		}
		if(isset($JSecureConfig->iplistW)){
			$iplistW = $JSecureConfig->iplistW;
		}else{
			$iplistW ="";
		}
		//$iplistW = $JSecureConfig->iplistW;
		$ablist = $JSecureConfig->ablist;
		$IPB = explode("\n",$iplistB);
		$IPW = explode("\n",$iplistW);
		$AB = explode("\n",$ablist);
		
		if($autoban == 1)
		{
		foreach($AB as $ip){
			if($ip!=""){
			if(!strpos("*",$ip)){
			$thisip = explode("*", $ip);
			$blockip = $thisip[0];
			if (substr($_SERVER['REMOTE_ADDR'], 0, strlen($blockip)) === $blockip) {
               $ABdenyaccess = 1;
               }
			}
			}
		 }
		}
				
		foreach($IPB as $ip){
			if($ip!=""){
			if(!strpos("*",$ip)){
			$thisip = explode("*", $ip);
			$blockip = $thisip[0];
			if (substr($_SERVER['REMOTE_ADDR'], 0, strlen($blockip)) === $blockip) {
               $denyaccess = 1;
               }
			}
			}
		}
	foreach($IPW as $ip){
		if($ip!=""){
			if(!strpos("*",$ip)){
			$thisip = explode("*", $ip);
			$allowip = $thisip[0];
			if (substr($_SERVER['REMOTE_ADDR'], 0, strlen($allowip)) === $allowip) {
               $allowaccess = 1;
               }
			}
		}
		}
		
		if($autoban){
		$posAB = strpos($ablist,$_SERVER['REMOTE_ADDR']);
				
				if ($posAB === false and $ABdenyaccess != 1)
				{
					//return true;
				}
				else
				{
					$IpAddress='Ip Address:'.$_SERVER['REMOTE_ADDR'];
					$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_BLOCK_IP', $IpAddress);
					return false;
				}
		}
		
		switch($iptype){
			case 0:
				$posB = strpos($iplistB,$_SERVER['REMOTE_ADDR']);
				
				if ($posB === false and $denyaccess != 1)
				{
					//return true;
				}
				else
				{
					$IpAddress='Ip Address:'.$_SERVER['REMOTE_ADDR'];
					$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_BLOCK_IP', $IpAddress);
					return false;
				}
				break;
				
			case 1:
			if($iplistW != '')
			$posW = strpos($iplistW,$_SERVER['REMOTE_ADDR']);
			else
			$posW = true;
				if ($posW === false and $allowaccess != 1 )
				{
   					$IpAddress='Ip Address:'.$_SERVER['REMOTE_ADDR'];
					$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_BLOCK_IP', $IpAddress);
					return false;
				}
				else
				{
   					return true;
				}
				break;
				
			default:
				return true;
				break;
		}
		return true;
	}

	 static function displayForm(){
		
?>
		
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Administration Login</title>
<link type="text/css" rel="stylesheet" href="<?php echo JURI::root(); ?>plugins/system/jsecure/jsecure/css/global.css" />
<!-- html5.js for IE less than 9 -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body>
<div class="mainwrapper">
	<div class="maincontainer">
		<div class="jsecure-form-grid">
			<div class="jsecure-logo-grid">
				Logo Will Come Here
			</div>
			<h4 class="jsecure-login-title">Administration Login</h4>
			<form class="jsecure-submit-form" name="key" action="index.php" method="POST" autocomplete="off">
				<input class="textbox" type="text" name="passkey" placeholder="ENTER KEY VALUE" />
				<!--<input type="submit" class="button" name="submit" value="submit"/>-->
				<button class="button" type="submit" name="submit" value="submit">SUBMIT</button>
			</form>
		</div>
	</div>
</div>
</body>
</html>

<?php
	}
	
function checkComponentprotect($com,$extension)
	{
	if($extension != "")
		{
		$com = $extension;
		}
	   $display_form =0;
	   $display = array();
	   $db = JFactory::getDBO();
        //$query = "SELECT  * FROM #__extensions  WHERE `element` = "."'".$com."'";
		$query = "SELECT  * FROM #__extensions  WHERE `element` = "."'".$com."' AND `type` = 'component' AND `protected` =0 AND `enabled` =1";
		$db->setQuery($query);
        $name = $db->loadObjectList();
		if(isset($name[0]->extension_id))
		{
		$query1 = "SELECT com_id,status FROM #__jsecurepassword where com_id=".$name[0]->extension_id;
		$db->setQuery($query1);
        $display = $db->loadObjectList();
		$extId=$name[0]->extension_id;
		} else {
			$extId="";
		}
		$session_variable = $com.$extId;
		$session    = JFactory::getSession();
		$checkedComponent = $session->get($session_variable);
		if(isset($display[0]) and $display[0]->status == 1 and $checkedComponent!=1)
		{
			$app    = JFactory::getApplication();
 			$link = 'index.php?option=com_jsecure&task=componentform&id='.$name[0]->extension_id;
 			$app->redirect($link);
		}
			
	}
	
	
	static function SecureImageForm()
	{
		?>
		
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Secure Image Login</title>
<link type="text/css" rel="stylesheet" href="<?php echo JURI::root(); ?>plugins/system/jsecure/jsecure/css/global.css" />
<!-- html5.js for IE less than 9 -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body>
<div class="mainwrapper">
	<div class="maincontainer">
		<div class="jsecure-form-grid">
			<div class="jsecure-logo-grid">
				Logo Will Come Here
			</div>
			<h4 class="jsecure-login-title">Secure Image Login</h4>
			<form class="jsecure-submit-form" enctype="multipart/form-data" name="key" action="" method="POST" autocomplete="off">
				<input style="padding: 10px;background: #777;color: #fff;padding-left: 25px;margin-bottom: 8px" type="file" name="Secureimage" id="Secureimage" placeholder="ENTER KEY VALUE" />
				<!--<input type="submit" class="button" name="submit" value="submit"/>-->
				<button class="button" type="submit" name="authenticate" value="submit">AUTHENTICATE</button>
			</form>
		</div>
	</div>
</div>
</body>
</html>

<?php
	}
	
	static function SecureImageAuthentication($fileProperties,$JSecureConfig)
	{
			$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
			$logFile	= $basepath.'/jsecurelog.php';
			require_once($logFile);
			$model = new jSecureModeljSecureLog();
			
			$sendemaildetails = $JSecureConfig->sendemaildetails;
			
			if(empty($fileProperties['Secureimage']['error'])){
			
			$fileType = $fileProperties['Secureimage']['type'];
			$fileExplode = explode("/",$fileType);
			$fileExtension = $fileExplode[1];

			foreach(glob('../administrator/components/com_jsecure/images/tempimage/*.*') as $existingfile)
			if(is_file($existingfile))
			@unlink($existingfile);
			$moved = move_uploaded_file($fileProperties['Secureimage']['tmp_name'], '../administrator/components/com_jsecure/images/tempimage/temp_image.'.$fileExtension);
			
			if(isset($moved) && $moved == true){
			
				foreach(glob('../administrator/components/com_jsecure/images/secureimage/*.*') as $securefile)
				if(is_file($securefile))
				{
				   $primaryImagePath = $securefile;
				}
				
				foreach(glob('../administrator/components/com_jsecure/images/tempimage/*.*') as $tempfile)
				if(is_file($tempfile))
				{
				   $temporaryImagePath = $tempfile;
				}
			
				if(file_get_contents($primaryImagePath)){
				$primaryImage = md5(@file_get_contents($primaryImagePath));  
				}
				
				if(file_get_contents($temporaryImagePath)){
				$temporaryImage = md5(@file_get_contents($temporaryImagePath));
				}
				
				if(isset($primaryImage) && isset($temporaryImage)){
					if($primaryImage ==  $temporaryImage){
					
					foreach(glob('../administrator/components/com_jsecure/images/tempimage/*.*') as $uploadedfile)
					if(is_file($uploadedfile))
					@unlink($uploadedfile);
					
					$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_AUTHENTIC_SECURE_IMAGE');
					
					if($sendemaildetails == '1' || $sendemaildetails == '3'){
						
						$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,'JSECURE_EVENT_ACCESS_ADMIN_USING_AUTHENTIC_SECURE_IMAGE_MAIL',1): '';
					}
					
					return true;
					}
					else{
					
					foreach(glob('../administrator/components/com_jsecure/images/tempimage/*.*') as $uploadedfile)
					if(is_file($uploadedfile))
					@unlink($uploadedfile);
					
					$insertLog = $model ->insertLog('JSECURE_EVENT_ACCESS_ADMIN_USING_FALSE_SECURE_IMAGE');
					
					if($sendemaildetails == '2' || $sendemaildetails == '3'){
						
						$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,'JSECURE_EVENT_ACCESS_ADMIN_USING_FALSE_SECURE_IMAGE_MAIL',1): '';
					}
					
					return false;
					}
				}
			
			
			  }
			
			
			}
			
			else{
			
			return false;
			
			}
	
	}
	
	
	
		static function urlCaptchaformAction($JSecureConfig){
	
		
	
	/*---Code to get Google Recaptcha Api Key from Database---*/
		
		$db = JFactory::getDBO(); 
		
		$sql = "SELECT Value from #__apikeys where Name='Re-Captcha Secret Key'";
		
		$db->setQuery($sql);
			
		$rows = $db->loadObjectList();
		
			
		$captchakey = $rows[0]->Value;	
		
		/*---Code to get Google Recaptcha Api Key from Database---*/
		
		/*---Code to get Captcha Api response from google ---*/
			
		$flag="";
		

		
					
		if(isset($_POST['g-recaptcha-response'])){
          $captcha=$_POST['g-recaptcha-response'];
		  
		 
        }
					
		//$ip="175.100.145.226";
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		if($ip == "::1"){
		
		$ip="127.0.0.1";
		}
						
		$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$captchakey."&response=".$captcha."&remoteip=".$ip);
		
				
		/*---Code to get Captcha Api response from google ---*/	
		
		$mystring=$response.success;
		$findme="false";
		
		
		$pos = strpos($mystring, $findme);
        
		
		
		
		
		if($pos == true){
			$flag = 0;
		}
		else{
			$flag = 1;
		}
				
			
		if($flag == 0){
		return false;
		}
		else{		
		  	return true;
			
		}
	
	
	}
	
	/*This function checks user ip is spam or not by project honeypot*/
	
	static function checkSpammer($JSecureConfig){
	
		
	$app = JFactory::getApplication();
	$sendemaildetails = $JSecureConfig->sendemaildetails;
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	if($ip == "::1"){
	
	$ip="127.0.0.1";
	}
	if ($ip !='')	
		{
	// logic to reverse the IP address
	$converseIP = implode('.',array_reverse(explode('.', $ip)));
	
	/*---Code to fetch captcha key from database----*/
		
		 $db = JFactory::getDBO();
		
		 $sql1 = "SELECT Value from #__apikeys where Name='http:BL Access Key'";
		
		 $db->setQuery($sql1);
				
		 $rows = $db->loadObjectList();
	
		 $honeypotApiKey = $rows[0]->Value;
		 		 
		/*---Code to fetch captcha key from database----*/
		
		/*---- Get Maximum Allowed Threat Score ----*/
		
		$allowedThreatRating = $JSecureConfig->allowedthreatrating;
		
		$length = strlen($honeypotApiKey);
		
				
		if(strlen($honeypotApiKey) == 12) //  API Key Length Validation
		{
			$lookupaddress = $honeypotApiKey .'.' .$converseIP .'.dnsbl.httpbl.org.';
			$lookupResponse = gethostbyname($lookupaddress);
			
					
			if($lookupaddress != $lookupResponse){
				
							
				$explodedArray = explode('.',$lookupResponse);
				$PermissibleThreatLevel = $explodedArray[2];
				$SpammerCategory = $explodedArray[3];
				
				
						
								
				if ($PermissibleThreatLevel >= $allowedThreatRating)
				{
					$spambotStatus = true;
						switch ($SpammerCategory) {
							case "0":
								$SpammerCategory = "Search Engine Spam Attack";
								$spambotStatus = false;
								break;
							case "1":
								$SpammerCategory = "Apprehensive Spam Attack";
								if ($PermissibleThreatLevel < 25) { $spambotStatus = false; }
								break;
							case "2":
								$SpammerCategory = "Feeder Spam Attack";
								$spambotStatus = true;
								break;
							case "3":
								$SpammerCategory = "Apprehensive Feeder Spam Attack";
								$spambotStatus = true;
								break;
							case "4":
								$SpammerCategory = "Data dump Spam Attack";
								$spambotStatus = true;
								break;
							case "5":
								$SpammerCategory = "Apprehensive Data dump Spam Attack";
								$spambotStatus = true;
								break;
							case "6":
								$SpammerCategory = "Feeder Data dump Spam Attack";
								$spambotStatus = true;
								break;
							case "7":
								$SpammerCategory = "Apprehensive Feeder Data dump Spam Attack";
								$spambotStatus = true;
								break;
					
					
					}
					
					
					/*----Condition when User is Spammer*/
					if($spambotStatus == true)
						{	
							/*--Code to insert Spam Ip In Database--*/
						
							
							$sql = "INSERT INTO `#__spamip`(`spamip`,`spamtype`) VALUES('$ip','$SpammerCategory')";
							$db->setQuery($sql) ;
							$db->query();
							
							$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure/models';
							$logFile	= $basepath.'/jsecurelog.php';
							require_once($logFile);
							$model = new jSecureModeljSecureLog();
							$insertLog = $model ->insertLog('JSECURE_SPAM_IP_ADDED_TO_DATABASE' , "Spam IP = ".$ip);
							
							$path          = '';
							$path .= $JSecureConfig->options == 1 ? JURI::root().$JSecureConfig->custom_path : JURI::root();
							
							if($sendemaildetails == '2' || $sendemaildetails == '3'){
						
								$JSecureConfig->sendemail == '1' ? jsecure::sendmail($JSecureConfig,'JSECURE_EVENT_ACCESS_ADMIN_USING_SPAM_IP_MAIL',1): '';
							}
							
							$app->redirect($path);
							
							/*--Code to insert Spam Ip In Database--*/
						
						}
						
						
				}
				
				
			
						
			
		}			
	}
	
	}
	
	}
	
		/*This function checks user ip is spam or not by project honeypot*/
		
		/*--- Function to save email spammer user info in db ----*/
		
		static function savespamuserInfo($userinfo,$JSecureConfig){
		
		if($JSecureConfig->publishlogdb == '1'){
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		 
		 
		if($ip == "::1"){
		
		$ip="127.0.0.1";
		}

		

			 $db = JFactory::getDBO(); 
			$sql = "INSERT INTO #__jsecure_spam_userinfo (name,username,email,ip) VALUES ('".$userinfo['fullname']."','".$userinfo['username']."','".$userinfo['email']."','".$ip."')";
			$db->setQuery($sql);
			$rows = $db->loadObjectList();
		
		}
		return;
		
	}
	
	static function checkCountryBlock(){
	
	$countrydetail= array();
	if (!empty($_SERVER['HTTP_CLIENT_IP']))  
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
   	$countrydetail[] = $ip;
	 
	$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$ip);
	$countrycode = $xml->geoplugin_countryCode ;
	$countryname =$xml->geoplugin_countryName;

	$countrydetail[] = $countrycode;
	$countrydetail[] = $countryname;

	return $countrydetail;
	
	}
}
?>