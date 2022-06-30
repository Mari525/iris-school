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
jimport('joomla.application.component.view');
//jimport('joomla.application.application');
jimport('joomla.html.pane');

class jsecureViewLicense extends JViewLegacy {
	
function display($tpl=null){
		
		$app = JFactory::getApplication();
	    $model = $this->getModel('jsecurelog');
		$this->addToolBarForm();
		$license_key = $model->getLicenseKey();
		$license_key =$license_key[0];
		if($license_key != '')
		$license_key = $model->encrypt_decrypt('decrypt', $license_key);
		$this->assignRef('licensekey', $license_key);
		parent::display($tpl);		
	}
	
	
	function addToolBarForm(){
	JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
	JToolBarHelper::apply('applylicense');
	JToolBarHelper::save('savelicense');
	JToolBarHelper::cancel('cancel', 'Close');
	}
		
	function save(){
	        
		$app = JFactory::getApplication();
        $model = $this->getModel('jsecurelog');  		
		$license_key =$_POST['license_key'];	
		$license_key = $model->encrypt_decrypt('encrypt', $license_key);
		$result =$model->setLicenseKey($license_key);
		if($result){
 			$link = 'index.php?option=com_jsecure';
 			$msg  = 'Details Has Been Saved';
 			$app->redirect($link,$msg,'MESSAGE');
 	    }

	}
	function apply(){
		$app = JFactory::getApplication();
		$model = $this->getModel('jsecurelog');
		$license_key =$_POST['license_key'];	
		$license_key = $model->encrypt_decrypt('encrypt', $license_key);
		$result =$model->setLicenseKey($license_key);
		if($result){
 			$link = 'index.php?option=com_jsecure&task=license';
 			$msg  = 'Details Has Been Saved';
 			$app->redirect($link,$msg,'MESSAGE');
 	    }

	}
}
	?>