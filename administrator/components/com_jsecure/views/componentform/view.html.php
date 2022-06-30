<?php
/**
 * jSecure Authentication components for Joomla!
 * jSecure Authentication extention prevents access to administration (back end)
 * login page without appropriate access key.
 *
 * @author      $Author: Ajay Lulia $
 * @copyright   Joomla Service Provider - 2016
 * @package     jSecure 3.2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id: view.html.php  $
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class jsecureViewComponentform extends JViewLegacy {
	function display($tpl=null){
		$id = JRequest::getVar('id', '');
		 $db = JFactory::getDBO();
        $query = "SELECT  * FROM #__extensions  WHERE `extension_id` =".$id;
		$db->setQuery($query);
        $name = $db->loadObjectList();
		$query1 = "SELECT * FROM #__jsecurepassword where com_id=".$id;
		$db->setQuery($query1);
        $display = $db->loadObjectList();
        $this->assignref('name',$name);
		$this->assignref('display',$display);
	    $this->addToolbar();
		parent::display($tpl);
	}
	 protected function addToolbar()
	{
		JToolBarHelper::title(JText::_(''), '');
	}
	
	
}

?>