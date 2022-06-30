<?php
   /**
      * Helper class for jSecure Authentication
      *
      * @package    Joomla.Tutorials
      * @subpackage Modules
      * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
      * @license        GNU/GPL, see LICENSE.php
      * mod_helloworld is free software. This version may have been modified pursuant
      * to the GNU General Public License, and as distributed it includes or
      * is derivative of works licensed under the GNU General Public License or
      * other free or open source software licenses.
   */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.filesystem.folder');	

class licenseHelper {

    public static function getLicenseKey(){
		$db = JFactory::getDBO();
		$query ="SELECT license_key from #__jsecure_license";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$license_key =$rows[0]->license_key;
		return $license_key; 
   }
   
   public function getSubscriptionCatid(){
	   $xml_file = JPATH_ROOT.'/'.'administrator'.'/'.'components'.'/'.'com_jsecure'.'/'.'jsecure.xml';
	   $xml  = simplexml_load_file($xml_file);
	   $sub_cat_id = $xml->subscription_cat_id;
	   return $sub_cat_id;
   }
   
}
?>