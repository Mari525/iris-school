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

class jsecureViewDirectory extends JViewLegacy {
	protected $form;
	protected $item;
	protected $state;
	function display($tpl=null){
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->addToolbar();
		parent::display($tpl);
	}
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
			JToolBarHelper::cancel('cancel');
			JToolBarHelper::help('help');
	}
}

?>