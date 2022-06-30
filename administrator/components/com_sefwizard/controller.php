<?php

defined('_JEXEC') or die('Restricted Access');

/* SEF Wizard extension for Joomla 3.x
--------------------------------------------------------------
 Copyright (C) AddonDev. All rights reserved.
 Website: https://addondev.com
 GitHub: https://github.com/philip-sorokin
 Developer: Philip Sorokin
 Location: Russia, Moscow
 E-mail: philip.sorokin@gmail.com
 Created: January 2016
 License: GNU GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
--------------------------------------------------------------- */

class SefwizardController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		
		$doc->addStyleSheetVersion(JURI::base(true) . '/components/com_sefwizard/assets/css/sefwizard.css');
		
		if(!SefwizardHelper::isEngineInstalled())
		{
			$app->enqueueMessage(JText::_('COM_SEFWIZARD_ENGINE_NOT_INSTALLED'), 'warning');
		}
		else if(!SefwizardHelper::isEngineEnabled())
		{
			$app->enqueueMessage(JText::_('COM_SEFWIZARD_ENGINE_NOT_ENABLED'), 'warning');
		}
		
		parent::display($cachable, $urlparams);
		return $this;
	}
	
	public function saveRobots()
	{
		$this->checkToken();
		
		$type = '';
		$message = '';
		
		JImport('joomla.filesystem.file');
	
		$path = JPATH_ROOT . DIRECTORY_SEPARATOR . "robots.txt";
		$uri = JUri::root() . "robots.txt";
		
		if(JFile::write($path, JFactory::getApplication()->input->post->get('robots', '', 'raw')))
		{
			$message = JText::sprintf('COM_SEFWIZARD_FILE_SAVE_SUCCESS', $uri);
			$type = 'message';
		}
		else
		{
			$message = JText::sprintf('COM_SEFWIZARD_FILE_SAVE_ERROR', $uri);
			$type = 'warning';
		}
		
		$this->setRedirect("index.php?option=com_sefwizard&view=robots", $message, $type);
	}
	
	public function saveParams()
	{
		$this->checkToken();
		
		$message = '';
		
		if($params = JFactory::getApplication()->input->post->get('params', array(), 'array'))
		{
			if(!empty($params))
			{
				$form = $this->getModel('sefwizard')->getForm(null, false);
				
				foreach($params as $key => $param)
				{
					if(!$form->getInput($key, 'params'))
					{
						unset($params[$key]);
					}
				}
				
				if(SefwizardHelper::setParams($params))
				{
					$message = JText::_('COM_SEFWIZARD_SETTINGS_SAVE_SUCCESS');
				}
			}
		}
		
		$this->setRedirect('index.php?option=com_sefwizard&layout=settings', $message);
		
	}
	
	public function saveRedirects()
	{
		$this->checkToken();
		
		$message = JText::_('COM_SEFWIZARD_REDIRECT_MANAGER_RULES_SAVE_SUCCESS');
		
		$input = JFactory::getApplication()->input->post;
		$dbo = JFactory::getDbo();
		
		$values = array();
		$redirects = array(
			'source' => $input->get('source', array(), 'array'),
			'destination' => $input->get('destination', array(), 'array'),
			'code' => $input->get('code', array(), 'array'),
			'regex' => $input->get('regex', array(), 'array'),
			'cs' => $input->get('cs', array(), 'array'),
			'get' => $input->get('get', array(), 'array'),
			'internal' => $input->get('internal', array(), 'array')
		);
		
		foreach($redirects['source'] as $key => $source)
		{
			$internal = empty($redirects['internal'][$key]) ? 0 : 1;
			$regex = empty($redirects['regex'][$key]) ? 0 : 1;
			$code = empty($redirects['code'][$key]) ? 0 : (int) $redirects['code'][$key];
			$get = empty($redirects['get'][$key]) ? 0 : 1;
			$cs = empty($redirects['cs'][$key]) ? 0 : 1;
			
			$destination = empty($redirects['destination'][$key]) ? '' : trim($redirects['destination'][$key]);
			
			$source = empty($redirects['source'][$key]) ? '' : trim($redirects['source'][$key]);
			$queryString = '';
			
			if($source === 'http://example.com/source' || !$destination && !$source)
			{
				continue;
			}
			
			if(!$regex)
			{
				$protocolPlaceholder = strpos($source, '//') === 0 ? 'http:' : '';
				$instance = JUri::getInstance($protocolPlaceholder . $source);
				$source = $instance->getPath();
				$queryString = $instance->getQuery();
				
				if(!$cs)
				{
					$source = mb_strtolower($source);
					$queryString = mb_strtolower($queryString);
				}
				
				if($base = $instance->toString(array('scheme', 'host', 'port')))
				{
					if($protocolPlaceholder)
					{
						$base = substr($base, 5);
					}
					$source = $base . $source;
				}
			}
			
			$values[md5($source . $queryString)] = '(' . $dbo->quote($source) . ',' . $dbo->quote($queryString) . ',' . $dbo->quote($destination) . ", $code, $regex, $cs, $get, $internal)";
			
		}
		
		$dbo->truncateTable('#__sefwizard_redirects');
		
		$table = $dbo->quoteName('#__sefwizard_redirects');
		$source = $dbo->quoteName('source');
		$query = $dbo->quoteName('query');
		$destination = $dbo->quoteName('destination');
		$code = $dbo->quoteName('code');
		$regex = $dbo->quoteName('regex');
		$cs = $dbo->quoteName('cs');
		$get = $dbo->quoteName('get');
		$internal = $dbo->quoteName('internal');
		
		$bundle = array_chunk($values, 500);
		
		foreach($bundle as $values)
		{
			$dbo->setQuery("INSERT INTO $table ($source, $query, $destination, $code, $regex, $cs, $get, $internal) VALUES " . implode(',', $values));
			
			if(!$dbo->execute())
			{
				$message = JText::_('COM_SEFWIZARD_REDIRECT_MANAGER_RULES_SAVE_FAIL');
			}
		}
		
		$this->setRedirect('index.php?option=com_sefwizard&view=redirects', $message);
		
	}
	
	public function enable($key = null, $urlVar = null)
	{
		$message = SefwizardHelper::enableEngine() ? JText::_('COM_SEFWIZARD_ENGINE_ENABLED') : '';
		$this->setRedirect('index.php?option=com_sefwizard', $message);
	}
}
