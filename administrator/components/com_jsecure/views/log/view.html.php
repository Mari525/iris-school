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

class jsecureViewLog extends JViewLegacy {

	protected $categories;
	protected $items;
	protected $pagination;
	protected $state;

	
	function display($tpl=null){

		$this->categories	= $this->get('CategoryOrders');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		$app    = &JFactory::getApplication();
		
		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		require_once($configFile);
		$JSecureConfig = new JSecureConfig();
		$search     = JRequest::getVar('search','');

		$model = $this->getModel('jsecurelog');

		//delete log 
		if($JSecureConfig->delete_log != 0)
			$deleteLog = $model->deleteLog($JSecureConfig->delete_log);

		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );
		
		$data = $model->getData();		
		$total = $model->getTotalList();

		// Create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
        $this->addToolbar();
		$JSecureConfig = new JSecureConfig();
		$this->assignRef('JSecureConfig',$JSecureConfig);
		$this->assignref('data',$data);
		$this->assignref('pagination',$pagination);
		$this->assignRef('search',$search);
		
		//echo "<pre>"; print_r($this->pagination);die;
		
		parent::display($tpl);
	}
	protected function addToolbar()
	{
			JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
			JToolBarHelper::cancel('cancel');
			JToolBarHelper::help('help');
	}
	function ipinfo($tpl=null){
		$ip = JRequest::getVar('ip','127.0.0.1');
		//$ipInfo = get_meta_tags('http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress='.$ip);		//syntax deprecated
		
		$json2 = file_get_contents("http://www.geoplugin.net/php.gp?ip=".$ip."");
		$data = unserialize($json2);
		$ipInfo = $data;
		
		/* This will return the below output
		Array ( [geoplugin_request] => 116.193.129.24 [geoplugin_status] => 200 [geoplugin_credit] => Some of the returned data includes GeoLite data created by MaxMind, available from http://www.maxmind.com. [geoplugin_city] => Kolkata [geoplugin_region] => Bengal [geoplugin_areaCode] => 0 [geoplugin_dmaCode] => 0 [geoplugin_countryCode] => IN [geoplugin_countryName] => India [geoplugin_continentCode] => AS [geoplugin_latitude] => 22.5697 [geoplugin_longitude] => 88.369698 [geoplugin_regionCode] => 28 [geoplugin_regionName] => Bengal [geoplugin_currencyCode] => INR [geoplugin_currencySymbol] => ? [geoplugin_currencySymbol_UTF8] => ₨ [geoplugin_currencyConverter] => 61.6054 ) 
		*/
		
		$this->assignref('ipInfo',$ipInfo);
		parent::display($tpl);
	}
	/*added*/
	function changeipstatus()
	{
		$ip = JRequest::getVar('ip','127.0.0.1');
		$task = JRequest::getVar('task' , 'blockip' );
		

		jimport('joomla.filesystem.file');	
		$app    = &JFactory::getApplication();

		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		
		require_once($configFile);
		
		if(! is_writable($configFile)){
			$link = "index.php?option=com_jsecure";
			$msg = 'Configuration File is Not Writable /administrator/components/com_jsecure/params.php ';
			$app->redirect($link, $msg, 'notice'); 
			exit();
		}

	
		$oldValue = new JSecureConfig();
		
		if( $task == 'unblockip' )	
		{
			$this->unblockip( $oldValue , $ip );
		}
		else
		{	/* by default will block ip */

			$oldValue->iplistB = $oldValue->iplistB . '
'.$ip;/* can add validation before adding */
		
		}


		$config = new JRegistry('JSecureConfig');
		$config->loadObject($oldValue);	
		
		$msg = JText::_('ERRORCONFIGFILE');

		if (JFile::write($configFile, $config->toString('PHP', array('class' => 'JSecureConfig','closingtag' => false))))
		{
			if( $task == 'unblockip' )	
			{
				$msg  = 'Ip successfully UnBlocked';
			}
			else
			{
				$msg  = 'Ip successfully Blocked';
			}
		}
		
			
		$link = 'index.php?option=com_jsecure&task=log';
	return $app->redirect($link,$msg,'MESSAGE');
	}


	function unblockip( &$oldValue , $ip )
	{
		$ip = trim( $ip );	
	
		$iplistB = $oldValue->iplistB ;
		$IPB = explode("\n",$iplistB);
		$IPB = array_map('trim',$IPB);
		$IPB = array_flip( $IPB ) ;
	
		if( !isset( $IPB[ $ip ] ) )
		{
			//raise error & return
		}
	
		unset( $IPB[ $ip ] );
		
		$IPB = array_flip( $IPB );	
		$iplistB = implode( "\n" , $IPB );
		
		$oldValue->iplistB = $iplistB;
	
	return true;
	}


}

?>