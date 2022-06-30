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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );
JToolBarHelper::title(JText::_('COM_JSECURE'), 'article.png');
class jsecureViewGraph extends JViewLegacy 
{
   function display($tpl = null)
	{ 
	   $this->addToolbar();
	  parent::display($tpl);
	}
	 protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
			JToolBarHelper::cancel('cancel');
			
	}
}
?>	
	