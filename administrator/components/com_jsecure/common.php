<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
$JSecureCommon 
= array('publish'						  => JText::_('ENABLE'),
			'enableMasterPassword' => JText::_('MASTER_PASSWORD_ENABLED'),
            'include_basic_confg' => JText::_('PASS_KEY_MSTR_PWD_ENABLED'),
            'include_adminpwdpro' => JText::_('REDIRECT_MSTR_PWD_ENABLED'),
            'include_mail' => JText::_('MAIL_MSTR_PWD_ENABLED'),
            'include_ip' => JText::_('IP_MSTR_PWD_ENABLED'),
            'include_adminid' => JText::_('ADMINID_MSTR_PWD_ENABLED'),
            'include_logincontrol' => JText::_('LOGINCNTRL_MSTR_PWD_ENABLED'),
            'include_metatags' => JText::_('METATAGS_MSTR_PWD_ENABLED'),
            'include_purgesessions' => JText::_('PURGESESSIONS_MSTR_PWD_ENABLED'),
            'include_mastermail' => JText::_('MASTER_MAIL_MSTR_PWD_ENABLED'),
            'include_log' => JText::_('DELETE_LOG_MSTR_PWD_ENABLED'),
            'include_showlogs' => JText::_('SHOW_LOGS_MSTR_PWD_ENABLED'),
            'include_directorylisting' => JText::_('DIRECTORY_LISTING_MSTR_PWD_ENABLED'), 
			'master_password'		  => JText::_('MASTER_PASSWORD'),
			'passkeytype'				  => JText::_('PASS_KEY'), 
			'key'							  => JText::_('KEY'), 
			'options'						  => JText::_('REDIRECT_OPTIONS'), 
			'custom_path'				  => JText::_('CUSTOM_PATH'), 
			'sendemail'					  => JText::_('SEND_MAIL'), 
			'sendemaildetails'		  => JText::_('SEND_MAIL_DETAILS'), 
			'emailid'						  => JText::_('EMAIL_ID'),
			'emailsubject'				  => JText::_('EMAIL_SUBJECT'),
			'iptype'						  => JText::_('IP_TYPE'),
			'iplistB'						  => JText::_('ACCESS_IP'),
			'iplistW'						  => JText::_('ACCESS_IP'),
			'abip'                    => JText::_('TIME_INTERVAL'),
		    'abiptrylist'             => JText::_('ATTEMPTS'),
			'mpsendemail'				  => JText::_('CONFIGURATION_SEND_MAIL'),
			'mpemailsubject'			  => JText::_('CONFIGURATION_EMAIL_SUBJECT_DESCRIPTION'),
			'login_control'					  => JText::_('LOGIN_CONTROL'),
            'adminpasswordpro'					  => JText::_('ADMIN_PWD_PRO'),
            'metatagcontrol'					  => JText::_('META_TAG_CONTROL'),
            'metatag_generator'					  => JText::_('METATAG_GENERATOR'),
            'metatag_keywords'					  => JText::_('METATAG_KEYWORD'),
            'metatag_description'					  => JText::_('METATAG_DESCRIPTION'),
            'metatag_rights'					  => JText::_('METATAG_RIGHTS'),
			'delete_log'					  => JText::_('CHANGED_LOG_DESC'),
			);


$enableMasterPassword = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_basic_confg  = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_adminpwdpro  = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_mail         = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_ip           = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_mastermail   = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_log          = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_showlogs     = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_directorylisting = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_adminid      = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_logincontrol = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_metatags     = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$include_purgesessions = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$login_control         = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$adminpasswordpro      = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$metatagcontrol        = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$abip        = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );
$abiplist= array(
		   '5'	=> "5 min.s",
		   '10'	=> "10 min.s",
		   '20'	=> "20 min.s",
		   '30'	=> "30 min.s",
		   '60'	=> "1 hr",
		   '120'	=> "2 hrs"
);
$abiptrylist= array(
		   '5'	=> "5",
		   '10'	=> "10",
		   '20'	=> "20",
		   '30'	=> "30",
		   '40'	=> "40",
		   '50'	=> "50"
);

$passkeytype =  array(
		   '0'	=> JText::_('URL'),
		   '1'	=> JText::_('FORM')
);

$options = array(
		   '0'	=> JText::_('REDIRECT_INDEX'),
		   '1'	=> JText::_('CUSTOM_PATH')
);

$sendemail = array(
		   '0'	=> JText::_('NO'),
		   '1'	=> JText::_('YES')
);

$sendemaildetails = array(
		   '1'	=> JText::_('SEND_CORRECT_KEY'),
		   '2'	=> JText::_('SEND_WRONG_KEY'),
		   '3'	=> JText::_('SEND_BOTH')
);

$iptype = array(
		   '0'	=> JText::_('BLOCKED_IP'),
		   '1'	=> JText::_('WHISH_IP')
);

$mpsendemail = array(
		   '0'	=> JText::_('NO'),
		   '1'	=> JText::_('YES')
);

$delete_log= array(
		   '0'	=> JText::_('Forever'),
		   '1'	=> JText::_('1 Month'),
		   '2'	=> JText::_('2 Month'),
		   '3'	=> JText::_('3 Month'),
		   '4'	=> JText::_('4 Month'),
		   '5'	=> JText::_('5 Month')
);
?>