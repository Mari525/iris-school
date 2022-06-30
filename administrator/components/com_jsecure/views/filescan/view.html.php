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
jimport( 'joomla.application.application' );
jimport('joomla.html.pane');

class jsecureViewFilescan extends JViewLegacy {
	protected $form;
	protected $item;
	protected $state;
	
function display($tpl=null){
		
        $this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		
		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		require_once($configFile);
		$JSecureConfig = new JSecureConfig();
		
		$this->assignRef('JSecureConfig',$JSecureConfig);
		
		jimport('joomla.html.pagination');
		$this->addToolbar();	
		
		//log end here
		parent::display($tpl);		
	}
	
	function startscanner(){
			
         $config = new JConfig();
         $configuration = array(
                            'hostname' => $config->host,
                            'username' => $config->user,
                            'password' => $config->password,
                            'database' => $config->db,
                            'char_set' => 'utf8',
                            'dbcollat' => 'utf8_general_ci',
                            'ignoreDir' => 'checksum,cache,import,custom,_notes,.svn',
                            'includeType' => 'php,js,htm,html,css,tpl,ini,txt',
                            'start_dir' => JPATH_SITE,
                         );
		//ini_set('memory_limit','1024M');				 
		
		$model = $this->getModel('jsecurelog');	
		
		$results = $model->filescan($configuration);
		$this->assignRef('results',$results);
		$this->display();
		
		
		
		
		}
	
	protected function addToolbar()
	{		
			JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
			JToolBarHelper::cancel('cancel');
	}
			
}
?>