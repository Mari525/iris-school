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
 * @version     $Id: default.php  $
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
$document = JFactory::getDocument();

?>


<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<div class="span10">
<h3><?php echo JText::_('HELP');?></h3>
<table>
	<tr>
		<td>
			<p><b>Drawback:</b></p>
			<p>Joomla has one drawback, any web user can easily know the site is created in Joomla! by typing the URL to access the administration area <b>(i.e. www.site name.com/administration).</b> This makes hackers hack the site easily once they crack id and password for Joomla!. </p>
		</td>
	</tr>
</table>

<table class="table-striped-jsecure" width="100%">
	<tr>
		<td>
			<p><b>Instructions</b></p>
			<p>jSecure Authentication module prevents access to administration (back end) login page without appropriate access key.</p>
		</td>
	</tr>
</table>
<br/>
<table style="border:1px solid #0088CC;">
	<tr>
		<td style="background-color:#F5FAFD; line-height:18px; padding:8px;">
			<p><b>Important!</b></p>
			<p style="color:#0088CC;">In order for jSecure to work the jSecure <b>plugin</b> must be enabled. Go to Extensions>Plugin Manager and look for the "<b>System - jSecure Authentication plugin</b>". Make sure this plug in is enabled.</p>
		</td>
	</tr>
</table>

<div>
<p>&nbsp;</p>
</div>

<h2><?php echo JText::_('SECURITY'); ?></h2>
<div>
	<ul class="nav nav-tabs">
	<li class="active"><a href="#basic_config_tab" data-toggle="tab"><?php echo JText::_('Basic Configuration');?></a></li>
	<li><a href="#email_scan_tab" data-toggle="tab"><?php echo JText::_('EMAIL_SCAN_HEADING');?></a></li>
	<li><a href="#secure_image_tab" data-toggle="tab"><?php echo JText::_('IMAGE_SECURE_HEADING');?></a></li>
	<li><a href="#user_key_tab" data-toggle="tab"><?php echo JText::_('COM_JSECURE_USERKEY');?></a></li>
	<li><a href="#country_block_tab" data-toggle="tab"><?php echo JText::_('COM_JSECURE_COUNTRY_BLOCK');?></a></li>
	<li><a href="#ip_config_tab" data-toggle="tab"><?php echo JText::_('IP_CONFIG');?></a></li>
	<li><a href="#masterpwd_config_tab" data-toggle="tab"><?php echo JText::_('MASTER_PASSWORD');?></a></li>
	<li><a href="#logincontrol_config_tab" data-toggle="tab"><?php echo JText::_('LOGIN_CONTROL');?></a></li>
	<li><a href="#pwdprotect_config_tab" data-toggle="tab"><?php echo JText::_('ADMIN_PASSWORD_PROT');?></a></li>
	<li><a href="#dir_config_tab" data-toggle="tab"><?php echo JText::_('COM_JSECURE_DIRECTORIES');?></a></li>
	<li><a href="#comp_pass_prot" data-toggle="tab"><?php echo JText::_('COM_JSECURE_PROTECT');?></a></li>
	<li><a href="#com_jsecure_autoban" data-toggle="tab"><?php echo JText::_('COM_JSECURE_AUTOBAN');?></a></li>
	</ul>

	<div class="tab-content">
	<div class="tab-pane active" id="basic_config_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>The basic configuration will hide your administrator URL from public access. This is all most people need.</p>
		</td>
		</tr>
		</table>
		<table>
		<tr>
			<td style="line-height:18px; padding:8px;">
			1) Set <b>"enable"</b> to <b>"yes"</b>.<br/><br/>
			2) Set the <b>"Pass Key"</b> to <b>"URL"</b> This will hide the administrator URL.<br/><br/>
			3) In the <b>"Key"</b> field enter the key that will be part of your new administrator URL. For example, if you enter <b>"test"</b> into the key field, then the administrator URL will be <a href="#">http://www.yourwebsite/administrator/?test</a>. Please note that you cannot have a key that is only numbers.<br/>
			   <br/>If you do not enter a key, but enable the jSecure component, then the URL to access the administrator area is /?jSecure <a href="#">(http://www.yourwebsite/administrator/?jSecure)</a>.<br/><br/>
			4) Set the <b>"Redirect Options"</b> field. By default, if someone tries to access you /administrator URL without the correct key, they will be redirected to the home page of your Joomla site. You can also set up a <b>"Custom Path"</b> is you would like the user to be redirected somewhere else, such as a <b>404 error</b> page.</li>
			<br/></br>
			5) Set the <b>"Re-Captcha"</b> to Yes & enter the data site key & secret key to enable Google Re-Captcha validation on Joomla! Administrator login screen. This will block all the automated robot inputs made by hackers using malicious scripts who intend to get illegal access to your website. 
			</td>
		</tr>
		</table>
	</div>
	
	<div class="tab-pane" id="email_scan_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>Provides protection from hackers on website front end by verifying their email id.</p>
		</td>
		</tr>
		</table>
		<table>
		<tr>
			<td style="line-height:18px; padding:8px;">
			<p>There are many dangerous email adress . By using this we can allow website owners to blacklist these email address in administrator. During user registration on frontend we can match these email ids with the one saved in database. If it matches we can stop user from registering in the website from frontend</p>
<p>1) Email Scan Authentication can be enabled or disabled from back end.</p>
<p>2) User can enter all emails which he wants to block during user registration from frontend.</p>
<p>3) Check emails with stopforumspam.com API. If email is listed on the website then it will stop the user from registering from the frontend..</p>
<p>4) Log Blocked Email To DB – If it is enable then spam emails will be stored into database..</p>
<p>5) View Spam Log displays all the spam email details that are stored in database..</p>
			</td>
		</tr>
		</table>
	</div>
	
	<div class="tab-pane" id="secure_image_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>Secure Image Authentication is the latest advancement in jSecure</p>
		</td>
		</tr>
		</table>
		<table>
		<tr>
			<td style="line-height:18px; padding:8px;">
			<p>It provides an additional layer of security to the website by allowing the site administrator to select a personalized secure image from the website back end which will be used for authenticating a user trying to access the Joomla! administrator of the website. Following scenarios are applicable to secure image authentication :</p>
<p>1) Secure Image Authentication can be enabled or disabled from back end.</p>
<p>2) Secure Image Authentication will appear (if enabled) at the time of login only after entering correct passkey from either form based or url based authentication.</p>
<p>3) Secure Image Authentication requires the user to provide the exact same image at the time of login which he/she has uploaded earlier into the jSecure backend settings.</p>
<p>4) Secure Image Authentication will allow the user to login even if the provide image has been renamed but will disallow the login if image properties like file size or dimensions have been manipulated.</p>
<p>5) Secure Image Authentication process failure at the time of login will simply redirect the user to the home page or specified path in jSecure Authentication settings.</p>
			</td>
		</tr>
		</table>
	</div>
	
	<div class="tab-pane" id="user_key_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>This tab contains all the necessary information related to the multiple <b>userkeys</b> feature.</p>
		</td>
		</tr>
		</table>
		<table>
		<tr>
			<td style="line-height:18px; padding:8px;">
			<p><b>Multiple Userkeys</b></p>
			<p>The latest version of jSecure Authentication offers the assignment of userkeys to different authenticated users of your website whom you wish to grant access to your joomla! website's administration.You can give them backend access without sharing your master passkey, i.e, the key which you set in the basic configuration. Instead, you can give each user a separate userkey which will be used by them while logging in.</p>
			</br>
			<p>The Userkey functionality provides you with the following distinctive features :</p>


<p>1) Separate userkey for each user such that your basic configuration's master passkey will remain safe with you.</p>


<p>2) Userkey assignment will be valid for a definite time period, that is, after the active period for a userkey is over, the respective user will not be allowed to login.</p> 


<p>3) A specific userkey can be enabled/disabled at any time irrespective of the time period allotted for that userkey.</p>


<p><b>For example,</b></p>
<p>if a userkey is valid for 4 days but the user has finished his/her tasks on your website's backend in the first 2 days only, then you can simply disable that userkey as that user no longer needs to login into the backend.</p>


<p>4) One-to-one mapping of userkey to a user's account has been made.That is, a user can only login using his/her userkey.</p>


<p><b>For example,</b></p>
<p>have a look at the below situation :</p>


<p><u>USER 1</u></p>
<p>joomla! Username :  Michael</p>
<p>joomla! Password  :  xxxxx </p>
<p>jSecure Userkey    :  1a2b3c</p>




<p><u>USER 2</u></p>
<p>joomla! Username :  Mason</p>
<p>joomla! Password  :  xxxxx </p>
<p>jSecure Userkey    :  4d5e6f</p>






<p>Now, if 'Michael' tries to login with following combination, he will fail :</p>


<p>joomla! Username :  Michael (correct username)</p>
<p>joomla! Password  :  xxxxxx  ( correct password)</p> 
<p>jSecure Userkey    :  4d5e6f  ( incorrect userkey) - This is Mason's userkey</p>


<p>5) Log records are generated for each userkey activity whether it be a correct usage activity or an incorrect one.You can track such activities in 'view logs' section.</p>


<p>6) E-mails will be sent on each userkey activity for correct usage, incorrect usage or both the situations accordingly, if mailing is enabled.</p>  


<p>7) While adding a new Userkey from the 'Add Userkey' form, one can assign the same userkey for multiple users of a particular usergroup at the same time.On the contrary, you can update each of the userkeys as per your needs & requirements separately. </p>




<p><b><u>Suggested Usage </u></p></b>


<p>Following practices are advisable while using multiple userkeys :</p>


<p>1) The following features of jSecure Authentication should be strictly protected using the 'Master Password' protection.This is required because you are letting other users to access your website's backend who can, for example, easily manipulate your 'Basic Configuration' & can change the master passkey.Below are the features :</p>


<p>* Basic Configuration</p>
<p>* User Keys</p>
<p>* View Log</p>
<p>* Mail</p>


<p>2) Although you assign a userkey to a user of your website to have the backend access, he/she will not be allowed to login unless & untill the respective usergroup to which the user belongs has the joomla! permission for accessing the backend.</p>


<p><b>For Example,</b> </p>
<p>take a look at the below situation :</p>


<p>Username  :  Serra </p>
<p>Password   :  xxxxx</p>
<p>Userkey      :  7g8h9i</p>
<p>Usergroup  : Registered (By default, this usergroup has no permission to access the joomla! administration)</p>


<p>So, if you have assigned a userkey to 'Serra' & she attempts to login into your backend, she will fail as the 'Registered' usergroup has no rights to access joomla! backend.But still if you want the 'Registered' usergroup to have backend access, then you need to go to joomla! 'Groups' & provide required permissions to allow them accessing the backend.Or, you can create a new usergroup for efficiently managing those users whom you wish to allow backend access. </p>


<p>3) When you delete a user from joomla! user manager, then the corresponding record from jSecure's multiple userkeys will also get deleted automatically if you had previously assigned a userkey to that user.</p>


<p>4) The multiple userkeys functionality is such that if a user enters a valid userkey,  the joomla! login screen becomes visible even if the userkey is disabled(unpublished), expired or inactive, etc. The userkeys validation will take place on the joomla! login screen after a user enters his/her joomla! username & password. And then, accordingly will be allowed/ disallowed to login into your website's backend. So, you should not confuse this functionality with the jSecure Authentication's existing master passkey functionality  where you set a passkey in Basic Configuration.</p>


<p>5) The 'Manage Userkeys' section lists all the created userkeys in a list  view where time period marked in :</p> 


<p>I) Red color indicates that,</p>


  <p> * the userkey has expired. </p>
<p>(Active period for the userkey is over) </p>


<p>II) Green color indicates two scenarios,</p>


  <p> * Firstly,  the userkey is currently active. </p>
<p>(If a userkey is within its active period)</p>


  <p> * Secondly, the userkey is currently inactive. </p>
<p>(If a userkey is assigned for a future time period ahead of the current date) </p> 

 <p> 6)  In order to get e-mails of userkey usages, the 'Mail' feature's  'Send Mail Details'  option is set to 'Both' by default. If you change it to 'Wrong key' only, then you will only receive mails regarding incorrect  master passkey & incorrect userkey inputs made by users & you will not receive any mails regarding different userkey scenarios (like expired key, disabled key etc). This is because, a userkey will be treated as a valid input if it matches the saved value regardless of the fact that it is expired or disabled.The further validation will decide whether the user will be allowed to login or not.</p>   

		</td>
		</tr>
		</table>
	</div>
	
	
	<div class="tab-pane" id="country_block_tab">
	<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>Country Block feature is the latest advancement in jSecure</p>
		</td>
		</tr>
		</table>
		<table>
		<tr>
			<td style="line-height:18px; padding:8px;">
			<p>By using this feature website administrator can stop all visitors of particular countries from accessing joomla admin. Website admin can block those countries from where most attacks to the website are being generated.</p>
<p>1) Country Block feature can be enabled or disabled from back end.</p>
<p>2) Attacker will be blocked if he belongs to the blocked country and after he enters the correct key.</p>
<p>3) Website Administrator will have to block/unblock countries from jSecure Backend.</p>
<p>4) Website administrator can view blocked country logs in jSecure Backend</p>
		</td>
		</tr>
		</table>
	
	</div>
	
	<div class="tab-pane" id="ip_config_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>This tab allows you to control which IPs have access to your administrator URL.</p>
		</td>
		</tr>
		</table>
		<table>
		<tr>
			<td style="line-height:18px; padding:8px;">
			<p><b>IP tab:</b> This tab allows you to control which IPs have access to your administrator URL.</p>
			<p><b>White Listed IPs:</b> If set to <b>"White Listed IPs"</b> you can make a white list for certain IPs. Only those specific IPS will be allowed to access your administrator URL.</p>
			<p><b>Blocked IPs:</b> If set to <b>"Blocked IPs"</b> you can block certain IPs form accessing your administrator URL.You can block a range of IPs by using format '192.*.*.*'. <strong>Warning!!!</strong>Use of '*.*.*.*' is not permitted.</p>
			</td>
		</tr>
		</table>
	</div>
	<div class="tab-pane" id="masterpwd_config_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>You can block access to the jSecure component from other administrators.Setting to "Yes", allows you to create a password that will be required when any administrator tries to access the jSecure configuration settings in the Joomla administration area. If you do not enter a master password, the default password will be "jSecure".Provides options to include particular sections of the component in master password.
			</p>
		</td>
		</tr>
		</table>
	</div>
	<div class="tab-pane" id="logincontrol_config_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>Login control to restrict multiple users from logging into the site using same username and password.</p>
		</td>
		</tr>
		</table>
	</div>
        <div class="tab-pane" id="pwdprotect_config_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>Add password protection to add extra security layer over the administrator folder using htaccess and htpassword.</p>
		</td>
		</tr>
		</table>
	</div>
	<div class="tab-pane" id="dir_config_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>Directory listing to show list of all files and folders with their permissions on the site.</p>
		</td>
		</tr>
		</table>
	</div>
	<div class="tab-pane" id="comp_pass_prot">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>With this feature you can restrict access to the admin side components that are installed.</p>
			<p>you can set password for the admin side componenta that are installed and set option to "Enabled". This will restrict other administrators from accessing the protected components.</p>
		</td>
		</tr>
		</table>
	</div>
	<div class="tab-pane" id="com_jsecure_autoban">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p><b>Autoban IP: </b>With this feature you add vulnerable IP addresses to Blacklisted IP'S automatically by selecting the time duration and number of invalid admin access attempts.</p>
			<p><b>Spam IP: </b>With this feature the user can stop spam bots from entering his/her website using Project Honey Pot BL Access API.
		</td>
		</tr>
		</table>
		<table>
		<tr>
			<td style="line-height:18px; padding:8px;">
			<p>Below are the steps to enable Spam IP feature:</p>

            <p>a) Log in Joomla Administrator Panel</p>
            <p>b) Click on Components > jSecure Authentication > IP Access Control</p>
            <p>c) Select the option 'Yes' for Spam IP to enable the Spam IP protection</p>
            <p>d) You will see the following options - Dns PHoneyPot.org Key , Allowed Threat Rating, Spam Ip List</p>
           <p>e)  Dns PHoneyPot.org Key - Put the key which project Honey Pot provided you to run their API.</p> 

            <p> Use the Below url to generate the Honey Pot Api Key</p>

             <p><b>http://www.projecthoneypot.org/httpbl_configure.php</b></p>
          
          <p>f) Allowed Threat Rating - Give a Rating from 1 to 255 in this text field. Project Honey Pot assigns rating for each spam IP address. If any spam IP has rating greater than the given rating in the text field , the access to the joomla administrator will be restricted for that spam IP address.</p>

         <p>g) This field shows the list of spam IP address which tried to enter in your website.</p>

        <p><b> Common Scenarios </b> </p>

        <p> a) If Spam IP address option is off then spam IP detection API will not work and user will be redirected to the Image Secure login or to the joomla administrator screen as per the settings.</p>

        <p>b) If Spam IP address option is on then spam IP detection API will work and if the IP is detected as the spam IP then the user will directly be redirected to the website and will not be able to access the Image Secure Login or the joomla administrator screen.</p>


 
      <p> c) If the "Dns PHoneyPot.org Key" in the joomla! back end is blank or incorrect then the spam detection API will not work even if the option is enabled. </p>

			</td>
		</tr>
		</table>
	</div>
	</div>
	</div>

<h2><?php echo JText::_('TOOLS'); ?></h2>
<div>
	<ul class="nav nav-tabs">
	<li class="active"><a href="#mail_config_tab" data-toggle="tab"><?php echo JText::_('MAIL_CONFIG');?></a></li>
	<li><a href="#mastermail_config_tab" data-toggle="tab"><?php echo JText::_('EMAIL_MASTER');?></a></li>
	<li><a href="#log_config_tab" data-toggle="tab"><?php echo JText::_('LOG');?></a></li>
	<li><a href="#view_log_tab" data-toggle="tab"><?php echo JText::_('COM_JSECURE_LOG');?></a></li>
	<li><a href="#metatag_tab" data-toggle="tab"><?php echo JText::_('META_TAG_CONTROL');?></a></li>
	<li><a href="#purge_session_tab" data-toggle="tab"><?php echo JText::_('PURGE_SESSION');?></a></li>
	<li><a href="#hit_graph" data-toggle="tab"><?php echo JText::_('HIT_GRAPH');?></a></li>
	<li><a href="#manage_ips_from_log" data-toggle="tab"><?php echo JText::_('COM_JSEUCRE_MANAGE_IPS_FROM_LOG');?></a></li>
	</ul>

	<div class="tab-content">
	<div class="tab-pane active" id="mail_config_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>This  tab sets a notification  email to be sent every time there is a login attempt into the Joomla administration area. You can set it to send the jSecure key or the incorrect key that was entered.</p>
		</td>
		</tr>
		</table>
	</div>
	<div class="tab-pane" id="mastermail_config_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>These setting allow you to have an email sent every time the jSecure configuration is changed.</p>
		</td>
		</tr>
		</table>
	</div>
	<div class="tab-pane" id="log_config_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>This setting allows you to decide how long the jSecure logs should remain in the database. The longer this is set for, the more database space will be used.</p>
		</td>
		</tr>
		</table>
	</div>
	<div class="tab-pane" id="view_log_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>jSecure will record any attempt that is made to access the Joomla /administrator directory. It will record the users IP, user name (if the login is successful), the nature of their login attempt, and the date the login attempt occurred.</p>
		</td>
		</tr>
		</table>
       </div>
       <div class="tab-pane" id="metatag_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>Meta tag controller to override default metadata of joomla.</p>
		</td>
		</tr>
		</table>
       </div>
         <div class="tab-pane" id="purge_session_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>Purge sessions to expire sessions of all active users.</p>
		</td>
		</tr>
		</table>
       </div>
	   <div class="tab-pane" id="hit_graph">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>This feature shows graphical representation of correct v/s wrong administrator access for different segments of time.</p>
		</td>
		</tr>
		</table>
       </div>
	   <div class="tab-pane" id="manage_ips_from_log">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>With this feature you can simply add/remove IP'S to Blacklisted IP'S from "View Log".</p>
		</td>
		</tr>
		</table>
		</div>
       </div>
	   </div>


<h2><?php echo JText::_('Change log'); ?></h2>
	<div>
	<ul class="nav nav-tabs">
	<li class="active"><a href="#change_log_tab" data-toggle="tab"><?php echo JText::_('Change Log');?></a></li>
	<li><a href="#license_tab" data-toggle="tab"><?php echo JText::_('License');?></a></li>
	</ul>

	<div class="tab-content">
	<div class="tab-pane active" id="change_log_tab">
	<table>
		<tr>
			<td style="line-height:18px; padding:8px;">
			<p>For More information visit <a href="http://joomlaserviceprovider.com" title="visit the site" target="_blank">http://joomlaserviceprovider.com</a></p>
			<p><b>Thanks to</b> the team (Ajay Lulia, Bhavin Shah,Omkar Ambre,Anurag Soni) for developing the Component and Plugin.</p>
			<p><b>Thanks to</b> Aaron Handford, Ajay Lulia for help with the component conceptualization.</p>
			<p><b>Thanks to</b> Sam Moffatt for converting Joomla! 1.0 module to a Joomla! 1.5 system plugin.</p>
            <p><b>Thanks to</b> Alex Khoroshevsky for translating extension and providing Russian language pack for jSecure 3.0.2 for Joomla! 2.5 and Joomla! 3.x.</p>
			</td>
		</tr>
		</table>
		<table>
		<tr>
			<td style="line-height:18px; padding:8px;">
			
			<p><b>3.4(01-Feb-2016):</b><br/><br/>   
			    1. Country block feature </br>
				2. Minor bug fixes </br>
			</p>
			<p><b>3.3(06-Oct-2015):</b><br/><br/>   
			    1. Email Scan during user registration </br>
				2. Minor bug fixes </br>
			</p>
			
			<p><b>3.2(02-Feb-2015):</b><br/><br/>   
			    1. Google Re-Captcha </br>
				2. Secure Image Authentication </br>
				3. Spam IP Protection </br>
			</p>
			
			<p><b>3.1(18-Sep-2014):</b><br/><br/>   
			    1. Multiple Userkeys : Assign multiple userkeys to different users whom you wish to grant access to your joomla! backend without sharing your master passkey.   
			</p><p>2. Changed the look & feel of form based authentication page.
			</p>
			
			<p><b>3.0.3(29-May-2014):</b><br/><br/>
				Fixed the <b>"</b>Resource not found(404)<b>"</b> console error for the file jsecure.css in jSecure login form.	
			</p>
			
			<p><b>3.0.2(23-Jul-2013):</b><br/><br/>
			    <strong>Added Security Feature:</strong><br/>
				1. Auto Ban I.P Address: Auto Ban IP Feature. Blacklist vulnerable IP addresses automatically. 
			</p>
			<p><b>3.0.2(23-Jul-2013):</b><br/><br/>
			    <strong>Added Tool:</strong><br/>
				1. Manage IP's: With this feature you can simply add/remove Blacklisted IP's from "View Log".
			</p>
			
			<p><b>3.0.1(12-Feb-2013):</b><br/><br/>
			    <strong>Added Security Feature:</strong><br/>
				1. Feature to apply password for components installed on the site.
			</p>
			<p><b>3.0.1(12-Feb-2013):</b><br/><br/>
			    <strong>Added Tool:</strong><br/>
				1. Added graphs to display administrator access.
			</p>
			<p><b>3.0(12-Dec-2012):</b><br/><br/>
			    <strong>Added Security Features:</strong><br/>
				1. Master Password:<br/>
				You can block access to the jSecure component from other administrator. Setting to "Yes", allows you to create a password that will be required when any administrator tries to access the jSecure configuration settings in the Joomla administration area.<br/>If you do not enter a master password, the default password will be "jSecure". Provides options to include particular sections of the component in master password.<br/>
				2. Master Login Control:<br/>
				Login control to restrict multiple users from logging into the site using same username and password.<br/>
				3. Admin Password Protection:<br/>
				Added password protection to add extra security layer over the administrator folder using htaccess and htpassword.<br/>
				4. Directory Listing:<br/>
				Directory listing to show list of all files and folders with their permissions on the site.<br/><br/>
				<strong>Added Tools:</strong><br/>
				1. Black Listed/ White Listed IP's:<br/>
				Now range of IPs can  be black listed or white listed by using format '192.*.*.*'. Warning !!! Use of '*.*.*.*' is not permitted. !!!<br/>
				2. Meta Tag Controller:<br/>
				Meta tag controller to override metadata of Joomla.<br/>
				3. Purge Sessions:<br/>
				Using this option will cleanup session of all logged-in users and they let logged-out.<br/>
			</p>
			<p><b>2.1.10(3-Feb-2012):</b><br/>
			1) Fixed JSecureConfig::$iplistB and JSecureConfig::$iplistW bug for Joomla 1.5.X, Joomla 1.6.X & Joomla 1.7.0.<br/>
			2) Fixed issues with mail headers for Joomla 2.5 .<br/>
			3) Added text input feild instead of text area in the form option of Basic Parameters for Joomla 1.5.X, Joomla 1.6.X & Joomla 1.7.0.
			</p>
			
			<p>
			<b>2.1.9(18-Aug-2011):</b><br/>
			1) Separate boxes added for blacklist and whitelist IP Addresses for Joomla 1.5.X, Joomla 1.6.X & Joomla 1.7.0.<br/>
			2) Multiple IP Addresses problem resolved for Joomla 1.5.X, Joomla 1.6.X & Joomla 1.7.0.<br/>
			3) Fixed Master Password & Verify Master field bug for Joomla 1.5.X, Joomla 1.6.X & Joomla 1.7.0.<br/>
			4) Language files updated for description of Verify Master Password field.<br/>
			5) Updated validations for Master Password & Verify Master Password fields.<br/>
			6) Fixed issues with tabs for Joomla 1.7.0 on IE7.
			</p>
			
			<p>
			<b>2.1.9(21-Mar-11):</b><br/>
			Fixed language related issues.
			</p>
			
			<p>
			<b>2.1.8(14-Jan-11):</b><br/>
			Fixed the code for redirection.
			</p>
			
			<p>
			<b>2.1.7(04-Aug-10):</b><br/>
			Fixed save functionality issue on IE8
			</p>
			
			<p>
			<b>2.1.6(28-Jul-10):</b><br/>
			Fixed notices issue.
			</p>
			
			<p>
			<b>2.1.5(20-Jul-10):</b><br/>
			1) Added condition to check the configuration file is writable or not.<br/>
			2) Added redirection on login page after correct key entered.
			</p>
			
			<p>
			<b>2.1.4(03-Jul-10):</b><br/>
			Fixed Email Validation issue.
			</p>
			
			<p>
			<b>2.1.3(02-Jul-10):</b><br/>
			1) Added log feature.<br/>
			2) Fixed white listed ip issue.<br/>
			3) Changed the component parameters to convert in Basic and Advanced configuration.<br/>
			4) Changed the layout of backend.<br/>
			5) Created jSecure component and plugin for Joomla 1.6.
			</p>
			
			<p>
			<b>2.1.2(02-Jun-10):</b><br/>
			Fixed small error.
			</p>
			
			<p>
			<b>2.1.1(31-May-10):</b><br/>
			1) Added Master Password to access the jSecure Authentication.<br/>
			2) Added E-mail option to send the change log in jSecure Authentication.<br/>
			3) User can choose from White Listed IPs / Blocked IPs.<br/>
			4) User Friendly option to add ip address.<br/>
			5) Enter specific IPs(White Listed IPs) that will allow access to administration area.<br/>
			</p>
			
			<p>
			<b>2.1.0(19-Apr-10):</b><br/>
			Fixed security bug.
			</p>
			
			<p>
			<b>2.0.1(14-Apr-10):</b><br/>
			1) Optimized the code.<br/>
			2) Fixed the IP issue in mail.<br/>
			3) Added Licenses information in files.
			</p>
			
			<p>
			<b>2.0(01-Apr-10):</b><br/>
			Added new features
			</p>
			
			<p>
			<b>1.0.9(10-Jun-09):</b><br/>
			Fixed warning message.
			</p>
			
			<p>
			<b>1.0.8(02-Jun-09):</b><br/>
			Fixed the case sensitivity check.
			</p>
			
			<p>
			<b>1.0.7(21-Mar-09):</b><br/>
			Fixed the code for redirection.
			</p>
			
			<p>
			<b>1.0.6(23-Dec-08):</b><br/>
			Fixed security bug. Updated the readme file.
			</p>
			
			<p>
			<b>1.0.5(16-Oct-08):</b><br/>
			Fixed redirection issue.
			</p>
			
			<p>
			<b>1.0.4(26-Sep-08):</b><br/>
			Fix for J1.5 to use proper custom tag and fixed a php error.
			</p>
			
			<p>
			<b>1.0.3(15-Sep-08):</b><br/>
			Fix for J1.5 call to admin login page using index2.php, please update your copy of jSecure Authentication.
			</p>
			
			<p>
			<b>1.0.2(30-Aug-08):</b><br/>
			Fix for J1.5 params (Thanks to Christer)
			</p>
			
			<p>
			<b>1.0: Initial Version 1.0.1:</b><br/>
			Fix for J1.5 Native
			</p>
			</td>
		</tr>
		</table>
	</div>
	
	<div class="tab-pane" id="license_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>This is free software and you may redistribute it under the GPL. jSecure comes with absolutely no warranty. Use at your own risk. For details, see the license at <a href="http://www.gnu.org/licenses/gpl.txt" target="_blank">http://www.gnu.org/licenses/gpl.txt</a> Other licenses can be found in LICENSES folder. </p>
		</td>
		</tr>
		</table>
	</div>
	</div>
	</div>
	</div>
</div>