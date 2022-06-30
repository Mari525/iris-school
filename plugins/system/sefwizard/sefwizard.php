<?php

defined('_JEXEC') or die('Restricted access');

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

class PlgSystemSefwizard extends JPlugin
{
	PUBLIC $events = array();
	
	PRIVATE
		$_execute = false,
		$_uri = array(),
		$_path = '',
		$_langTAG = null,
		$_langFragment = null,
		$_rewrite = 'SERVER',
		$_scriptExecutionTime = false,
		$_showRouterVariables = false,
		$_sefRewrite = false,
		$_sefSuffix = false,
		$_sefSuffixes = array(),
		$_rootlen = 0,
		$_sef = null,
		$_router = null,
		$_id_options = array(),
		$_menu = null,
		$_paginationRewrite = false,
		$_pageTag = '',
		$_showAllTag = '',
		$_limit = null,
		$_page = null,
		$_defaultMenuItem = null,
		$_tagsMap = array(),
		$_raiseDelayedError = false,
		$_component = null,
		$_duplicateHandlingMode = false;
	
	PUBLIC STATIC 
		$previousExceptionHandler = '',
		$emailException = null;	

	
	PUBLIC FUNCTION __construct(&$subject, $config)
    {
		if(JComponentHelper::isEnabled('com_sefwizard'))
		{
			if($this->_execute = JFactory::getApplication()->isSite())
			{
				parent::__construct($subject, $config);
				$this->loadLanguage('', JPATH_ADMINISTRATOR);
				
				if($this->_scriptExecutionTime = $this->params->get('script_execution_time'))
				{
					$this->_countScriptExecutionTime();
				}
				
				if($this->params->get('redirect_manager_enabled'))
				{
					$this->_checkRedirect();
				}
				
				self::$emailException = $this->params->get('smart_error_handler_email_addr');
			
				if($this->params->get('smart_error_handler'))
				{
					JError::setErrorHandling(E_ERROR, 'callback', array($this, 'handleException'));
					self::$previousExceptionHandler = set_exception_handler(array($this, 'handleException'));
					
					$dispatcher = JEventDispatcher::getInstance();
					
					$dispatcher->register('onAfterRoute', function() {
						$this->events['onAfterRoute'] = true;
					});
					
					$dispatcher->register('onAfterDispatch', function() {
						$this->events['onAfterDispatch'] = true;
					});
				}
				else if	($this->params->get('smart_error_handler_email'))
				{
					self::$previousExceptionHandler = set_exception_handler(array('plgSystemSefwizard', 'handlePreviousException'));
				}
				
				if($this->_scriptExecutionTime)
				{
					$this->_countScriptExecutionTime();
				}
			}
		}
    }
	
	PROTECTED STATIC FUNCTION executeComponent($error)
	{
		ob_start();
			require_once JPATH_SITE . '/components/com_sefwizard/sefwizard.php';
		return ob_get_clean();
	}
	
	PUBLIC FUNCTION handleException($error)
	{
		if ($error instanceof Exception || get_class($error) === 'JException')
		{
			$code = $error->getCode();
			$message = $error->getMessage();
			
			$app = JFactory::getApplication();
			$router = $app->getRouter();
			$routerVars = $router->getVars();
			$vars = $app->input->getArray();
			
			$app->allowCache(false);
			
			foreach($routerVars as $key => $var)
			{
				$router->setVar($key, '');
			}
			
			foreach($vars as $key => $var)
			{
				$app->input->set($key, '');
			}
			
			$newVars = ['option' => 'com_sefwizard', 'format' => 'html'];
			
			if ($menuItem = $app->getMenu()->getItems('component', 'com_sefwizard', true))
			{
				$newVars['Itemid'] = $menuItem->id;
			}
			
			foreach($newVars as $key => $var)
			{
				$app->input->set($key, $var);
				$router->setVar($key, $var);
			}
			
			JFactory::$document = null;
			
			$doc = JFactory::getDocument();
			$app->loadDocument($doc);
			
			try
			{
				$contents = self::executeComponent($error);
			}
			catch(Exception $e)
			{
				JDocument::getInstance('error')->setBuffer(null);
				call_user_func(self::$previousExceptionHandler, $error);
			}
			finally
			{
				if ($this->params->get('smart_error_handler_email') && ($code < 400 || $code >= 500))
				{
					self::sendErrEmail($error);
				}
			}
			
			$dispatcher = JEventDispatcher::getInstance();
			$trace = $error->getTrace();
			
			foreach($trace as $item)
			{
				if (!empty($item['class']) && stripos($item['class'], 'plgSystem') !== false) {
					$dispatcher->detach($item['class']);
				}
			}
			
			$dispatcher->detach('PlgSystemSefwizard');
			$dispatcher->detach('PlgSystemCache');
			
			if (!isset($this->events['onAfterRoute']))
			{
				try {
					$dispatcher->trigger('onAfterRoute');
				}
				catch(Exception $e){}
			}
			
			if (!isset($this->events['onAfterDispatch']))
			{
				try {
					$dispatcher->trigger('onAfterDispatch');
				}
				catch(Exception $e){}
			}
			
			try {
				$doc->setBuffer($contents, ['type' => 'component', 'name' => null, 'title' => null]);
				$buffer = $doc->render(false, ['template' => $app->getTemplate(), 'file' => 'index.php', 'params' => $app->getTemplate(true)->params, 'debug' => JDEBUG]);
				
				$dispatcher->trigger('onBeforeRender');
				$app->setBody($buffer);
				$dispatcher->trigger('onAfterRender');
				
				ob_start();
				
				echo $app->toString();
				$dispatcher->trigger('onAfterRespond');
				
				ob_end_flush();
			}
			catch(Exception $e) {
				JDocument::getInstance('error')->setBuffer(null);
				JErrorPage::render($error);
			}
		}
		else if($error instanceof Throwable)
		{	
			error_log($error->getMessage());
			http_response_code(500);
			
			if ($this->params->get('smart_error_handler_email'))
			{
				self::sendErrEmail($error);
			}
			
			$display_errors = strtolower(ini_get('display_errors'));
			
			if ($display_errors == 'on' || $display_errors == 1)
			{
				echo '<br/>' . self::getErrContent($error);
				
				if ($this->params->get('smart_error_handler_stack'))
				{
					echo self::getErrStackContent($error);
				}
			}
		}
		
		exit(1);
	}
	
	public static function getErrContent($error)
	{
		$type = get_class($error);
			
		if ($type === 'Error') {
			$type = 'Fatal error';
		}
		
		return '<b>' . $type . ':</b> ' . $error->getMessage() . ' in <b>' . $error->getFile() . '</b> on line <b>' . $error->getLine() . '</b>';
	}
	
	public static function getErrStackContent($error)
	{
		$html = '';
		$trace = $error->getTrace();
		
		if (!empty($trace))
		{
			$html .= '<br/><br/>Stack:<br/><br/>';
			
			foreach ($trace as $item)
			{
				$html .= 'Line: ' . $item['line'] . ', file: ' . $item['file'] . '<br/>';
			}
		}
		
		return $html;
	}
	
	public static function sendErrEmail($error)
	{
		$config = JFactory::getConfig();
		
		if ($recipient = self::$emailException ? self::$emailException : $config->get('mailfrom'))
		{
			$mailer = JFactory::getMailer();
			
			$mailer->setSender(array(
				$config->get('mailfrom'),
				$config->get('fromname'),
			));
			
			$mailer->addRecipient($recipient);
		
			$uri = JUri::getInstance();
			$host = JStringPunycode::fromPunycode($uri->getHost());
			
			$url = preg_replace("#https?://\K[^/:]+#i", $host, $uri->toString());
			
			$subject = JText::sprintf('PLG_SEFWIZARD_ERR_HEADER', $host);
			$header = '<h3>' . $subject . '</h3>';
			
			$errcontent = self::getErrContent($error);
			$stackcontent = self::getErrStackContent($error);
			
			$errpage = JText::_('PLG_SEFWIZARD_ERR_PAGE') . '<a href="' . $url . '">' . $url . '</a><br/><br/>';
			
			$mailer->isHTML(true);
			$mailer->Encoding = 'base64';
			$mailer->setSubject($subject);
			$mailer->setBody($header . $errpage . $errcontent . $stackcontent);
			
			$mailer->send();	
		}
	}
	
	PRIVATE FUNCTION _checkRedirect()
	{
		$dbo  = JFactory::getDbo();
		$app  = JFactory::getApplication();
		
		$uri    = JUri::getInstance();
		$root   = JUri::root(true);
		$search = array();
		
		$scheme   = $uri->getScheme();
		$hostName = $uri->getHost();
		
		if($this->params->get('redirect_manager_convert_punycode'))
		{
			$hostName = JStringPunycode::fromPunycode($hostName);
		}
		
		$port = $uri->getPort();
		$host = $hostName . ($port ? ':' . $port : '');
		
		$path   = $this->_decodeUri($uri->getPath());
		$rel    = $path === '/' ? '' : mb_substr($path, strlen($root) + 1);
		
		$search[] = $dbo->quote($rel);
		$search[] = $dbo->quote($path);
		
		if(!$rel)
		{
			$search[] = $dbo->quote('//' . $host);
			$search[] = $dbo->quote($scheme . '://' . $host);
		}
		else
		{
			$search[] = $dbo->quote('//' . $host . $path);
			$search[] = $dbo->quote($scheme . '://' . $host . $path);
			
			$relCI  = mb_strtolower($rel);
			$pathCI = mb_strtolower($path);
			
			if($CIcollation = strcmp($rel, $relCI) || strcmp($path, $pathCI))
			{
				$search[] = $dbo->quote($relCI);
				$search[] = $dbo->quote($pathCI);
				$search[] = $dbo->quote('//' . $host . $pathCI);
				$search[] = $dbo->quote($scheme . '://' . $host . $pathCI);
			}
		}
		
		$query = $dbo->getQuery(true);
		$query
			->select('*')
			->from($dbo->quoteName('#__sefwizard_redirects'))
			->where('(' . $dbo->quoteName('source') . ' IN (' . implode(',', $search) . ') OR '. $dbo->quoteName('regex') . ' =1)');
		
		if(isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) !== 'GET')
		{
			$query->where($dbo->quoteName('get') . ' <> 1');
		}
		
		$query->order($dbo->quoteName('id'));
		$dbo->setQuery($query);
		
		if($rows = $dbo->loadObjectList())
		{
			$rx = array();
			$cs = array();
			$ci = array();
			
			$queryString = $this->_decodeUri($_SERVER['QUERY_STRING']);
			
			foreach($rows as $row)
			{
				if($row->regex)
				{
					$rx[] = $row;
				}
				else if(empty($CIcollation) && !$row->cs)
				{
					if(!$row->query || !strcasecmp($row->query, $queryString))
					{
						$ci[] = $row;
					}
				}
				else if($row->cs && (!$row->query || !strcmp($row->query, $queryString)))
				{
					$cs[] = $row;
				}
			}
			
			$rules = !empty($cs) ? $cs : $ci;
			
			if(!empty($rules))
			{
				$rule = null;
				
				if (count($rules) > 1) {
					foreach ($rules as $candidate) {
						if (!empty($candidate->query)) {
							$rule = $candidate;
						}
					}
				}
				
				if (!$rule) {
					$rule = array_shift($rules);
				}
			}
			else
			{
				$uri = $this->_decodeUri(JUri::getInstance()->toString());
				
				foreach($rx as $r)
				{
					$src = str_replace('#', '\#', $r->source);
					
					if(@preg_match("#$src#u" . ($r->cs ? '' : 'i'), $uri, $matches))
					{
						$rule = $r;
						
						$rule->destination = preg_replace_callback('#\$(\d+)#', function($c) use ($matches) {
							return $matches[$c[1]];
						}, $rule->destination);
						
						$destSource = explode('?', $rule->destination);
						$length = count($destSource);
						
						if($length > 2)
						{
							$rule->destination = $destSource[0] . '?' . $destSource[$length-1];
						}
						
						break;
					}
				}
			}
			
			if(!empty($rule))
			{
				if(strpos($rule->destination, '//') === 0)
				{
					$rule->destination = $scheme . ':' . $rule->destination;
				}
				else if($rule->internal)
				{
					$pos = mb_stripos($rule->destination, 'index.php');
					
					if($pos !== false)
					{
						$rule->destination = substr_replace($rule->destination, '', $pos, 9);
						
						if(mb_substr($rule->destination, $pos, 1) === '/')
						{
							$rule->destination = substr_replace($rule->destination, '', $pos, 1);
						}
						
					}
				}
				
				$destination = JUri::getInstance($rule->destination);
				
				$destSource = explode('?', $rule->destination, 2);
				$queryString = !empty($destSource[1]) ? '?' . $destSource[1] : 
					(!isset($destSource[1]) && !empty($queryString) && !$rule->internal ? '?' . $queryString : '');
				
				if(strpos(($destPath = $destination->getPath()), '/') !== 0)
				{
					$destination->setPath($root . '/' . $destPath);
				}
				
				if(!$destination->getHost())
				{
					$destination->setHost($hostName);
					$destination->setPort($port);
				}
				
				if(!$destination->getScheme())
				{
					$destination->setScheme($scheme);
				}
				
				if($fragment = $destination->getFragment())
				{
					$fragment = '#' . $fragment;
				}
				
				$uri = $destination->toString(array('scheme', 'host', 'port', 'path')) . $queryString . $fragment;
				
				if($rule->internal)
				{
					$this->_rewrite = $uri;
				}
				else
				{
					$app->redirect($uri, (int) $rule->code);
				}
				
			}
			
		}
		
	}
	
	
	PRIVATE FUNCTION _encodeUri($uri)
	{
		return rawurlencode(str_replace('+', ' ', $uri));
	}
	
	
	PRIVATE FUNCTION _decodeUri($uri)
	{
		return str_replace(' ', '%20', urldecode($uri));
	}
	
	
	PUBLIC STATIC FUNCTION handlePreviousException($exception)
	{
		$code = $exception->getCode();
		
		if ($code < 400 || $code >= 500)
		{
			self::sendErrEmail($exception);
		}
		
		call_user_func(self::$previousExceptionHandler, $exception);
	}
	
	
	PUBLIC FUNCTION onAfterInitialise()
	{
		$config = JFactory::getConfig();
		
		if($this->_execute = $this->_execute && $config->get('sef'))
		{
			if($this->_scriptExecutionTime)
			{
				$this->_countScriptExecutionTime();
			}
			
			$app = JFactory::getApplication();
			$this->_router = $app->getRouter();
			
			$this->_showRouterVariables = $this->params->get('show_router_variables');
			$components = array('com_tags', 'com_content', 'com_contact', 'com_newsfeeds');
			
			foreach ($components as $key => $component)
			{
				if ($this->params->get("id_$component"))
				{
					if ($params = JComponentHelper::getParams($component))
					{
						if ($key > 0 && $params->get('sef_advanced'))
						{
							$params->set('sef_advanced', 0);
						}
						
						$this->_id_options[$component] = true;
					}
				}
			}

			$this->_duplicateHandlingMode = $this->params->get('duplicate_standard') ? $this->params->get('duplicate_standard_mode', 1) : 0;
			
			$this->_sefRewrite = $config->get('sef_rewrite');
			$this->_menu = $app->getMenu();
			
			$uri = JURI::getInstance($this->_rewrite);
			
			$origPath = $this->_decodeUri($uri->getPath());
			$origQuery = $uri->getQuery();
			
			$path = $origPath;
			
			$len = strlen(JURI::root(true));
			$this->_rootlen = $len;
			
			if(stripos($path, '/index.php') === $len)
			{
				$len += 10;
			}
			
			if($len)
			{
				$path = substr($path, $len);
			}
			
			if($this->_sefSuffix = $config->get('sef_suffix'))
			{
				if($suffixPos = strrpos($path, '.'))
				{
					$suffix = substr($path, $suffixPos + 1);
					
					if($suffix !== 'html')
					{
						$uri->setVar('format', $suffix);
					}
					$path = substr_replace($path, '', $suffixPos);
				}
			}
			
			if($this->params->get('wbamp_integration'))
			{
				$wbamp = JPluginHelper::getPlugin('system', 'wbamp');
			
				if(!empty($wbamp) && isset($wbamp->params))
				{
					$reg = new JRegistry();
					
					if($wbampSuffix = $reg->loadString($wbamp->params)->get('amp_suffix'))
					{
						$wbampSuffix = (!empty($this->_sefSuffix) ? '.' : '/') . $wbampSuffix;
						$wbampSuffixPos = mb_strrpos($path, $wbampSuffix);
						
						if($wbampSuffixPos !== false)
						{
							$path = mb_substr($path, 0, $wbampSuffixPos);
							$this->_sefSuffixes[] = $wbampSuffix;
						}
					}
				}
			}
			
			if($this->_paginationRewrite = $this->params->get('pagination_rewrite'))
			{
				$this->_pageTag = preg_replace('#[^\w\-]#u', '', $this->params->get('page_tag', 'page'));
				$this->_showAllTag = preg_replace('#[^\w\-]#u', '', $this->params->get('showall_tag', 'showall'));
				
				if(preg_match("#(.*?)/(?|(" . $this->_showAllTag . ")|" . $this->_pageTag . "\-(\d+))$#iu", $path, $matches))
				{
					$path = $matches[1];
					$this->_page = $matches[2];
					
					if(($this->_page == 1 && $this->params->get('set_firstpage_noindex')) || ($this->_page == $this->_showAllTag && $this->params->get('set_showall_noindex')))
					{
						JFactory::getDocument()->setMetaData('robots', 'noindex');
					}
				}
			}
			
			$fragments = explode('/', $path);
			$fragments = array_values(array_filter($fragments));
			
			$defTAG = JComponentHelper::getParams('com_languages')->get('site');
			
			if(isset($fragments[0]) && JPluginHelper::isEnabled('system', 'languagefilter'))
			{
				$base = $fragments[0];
				$sefs = JLanguageHelper::getLanguages('sef');
				$languageFilterParams = json_decode(JPluginHelper::getPlugin('system', 'languagefilter')->params);
				
				if(isset($sefs[$base]))
				{
					array_shift($fragments);
					$this->_langTAG = $sefs[$base]->lang_code;
					$fragments = array_values(array_filter($fragments));
					
					$this->_langFragment = $base . '/';
				}
				else
				{
					$this->_langFragment = false;
				}
			}
			
			if(!$this->_langTAG)
			{
				$this->_langTAG = $defTAG;
			}
			
			$path = implode('/', $fragments);
			$this->_defaultMenuItem = $this->_menu->getDefault($this->_langTAG);
			$fixTagRouting = $this->params->get('fix_tag_routing');
			
			$level = count($fragments);
			
			if($level)
			{
				if($fragments[0] === 'component')
				{
					if($level > 3 && array_key_exists('com_' . $fragments[1], $this->_id_options))
					{
						$this->_sef = $this->_getComponentSef(array_pop($fragments), $fragments[1], $fragments[2]);
					}
				}
				else
				{
					$alias = $fragments[$level-1];
					$routes = array($fragments[0]);
					
					for($i=1; $i<$level; $i++)
					{
						$routes[] = $routes[$i-1] . '/' . $fragments[$i];
					}
					
					$routes = array_reverse($routes);
					
					$menuItems = $this->_menu->getMenu();
					$menuItemsMatchingRoute = array();
					
					$homePageCandidates = array();
					$menuItemCandidates = array();
					
					$tagsMenuItems = array();
					
					foreach($menuItems as $menuItem)
					{
						if(in_array($menuItem->language, array($this->_langTAG, '*')))
						{
							if($menuItem->home)
							{
								count($homePageCandidates) && $menuItem->language === $this->_langTAG ? 
									array_unshift($homePageCandidates, $menuItem) : 
										array_push($homePageCandidates, $menuItem);
							}
							else if($menuItem->route === $path)
							{
								$menuItemsMatchingRoute[] = $menuItem;
							}
							else if(in_array($menuItem->route, $routes))
							{
								$menuItemCandidates[] = $menuItem;
							}
							
							if($fixTagRouting && $menuItem->component === 'com_tags' && $menuItem->query['view'] === 'tags')
							{
								$menuItem->language === $this->_langTAG ? array_unshift($tagsMenuItems, $menuItem) : 
									array_push($tagsMenuItems, $menuItem);
							}
						}
					}
					
					if(empty($menuItemsMatchingRoute))
					{
						$categoryFragments = array();
						$menuFragments = array();
						
						$this->_defaultMenuItem = $homePageCandidates[0];
						$terminal = array('article', 'contact', 'newsfeed', 'tag');
						
						if(count($menuItemCandidates))
						{
							usort($menuItemCandidates, function($a, $b) {
								return $a->level < $b->level;
							});
							
							$menuItem = $menuItemCandidates[0];
							
							for($i = $menuItemCandidates[0]->level; $i < $level; $i++)
							{
								$categoryFragments[] = $fragments[$i];
							}
							
							$menuFragments = explode('/', $menuItemCandidates[0]->route);
							
							if(!array_key_exists($menuItem->component, $this->_id_options)
								|| ($this->_duplicateHandlingMode && in_array($menuItem->query['view'], $terminal)))
							{
								$menuItem = $this->_defaultMenuItem;
								
								$categoryFragments = array_merge($menuFragments, $categoryFragments);
								$menuFragments = array();
								$restrictedSearch = true;
							}
							
						}
						else
						{
							$categoryFragments = $fragments;
							$menuItem = $this->_defaultMenuItem;
							$restrictedSearch = true;
						}
						
						$searchMenu = array_key_exists($menuItem->component, $this->_id_options)
							&& (!in_array($menuItem->query['view'], $terminal)
								|| ($menuItem->home ? $this->_duplicateHandlingMode != 2 : !$this->_duplicateHandlingMode));
						
						if(!empty($restrictedSearch))
						{
							$search = $searchMenu;
						}
						else
						{
							$restrictedSearch = !array_key_exists($this->_defaultMenuItem->component, $this->_id_options)
								|| ($this->_duplicateHandlingMode == 2 && in_array($this->_defaultMenuItem->query['view'], $terminal));
							
							$search = $searchMenu || !$restrictedSearch;
						}
						
						if($search)
						{
							$this->_sef = $this->_getSef($categoryFragments, $menuFragments, $menuItem, $restrictedSearch);
						}
					}
				}
			}
			
			if($this->_page && !$this->_sef)
			{
				$this->_sef = $path;
			}
			
			if($fixTagRouting)
			{
				if(!isset($tagsMenuItems))
				{
					$menuItems = $this->_menu->getMenu();
					$tagsMenuItems = array();
					
					forEach($menuItems as $menuItem)
					{
						if($menuItem->component === 'com_tags' && ($menuItem->language === $this->_langTAG || $menuItem->language === '*')
							&& isset($menuItem->query, $menuItem->query['view']) && $menuItem->query['view'] === 'tags')
						{
							$menuItem->language === $this->_langTAG ? array_unshift($tagsMenuItems, $menuItem) : 
								array_push($tagsMenuItems, $menuItem);
						}
					}
				}
				
				if(!empty($tagsMenuItems))
				{
					$tags = isset($this->_tagsMap['tags']) ? $this->_tagsMap['tags'] : $this->_getTags();
					
					if(!empty($tags))
					{
						$tagsMap = array(
							'idToPath' => array(),
							'pathToId' => array()
						);
						
						forEach($tags as $tag)
						{
							$tagsMap['idToPath'][$tag->id] = $tag->path;
							$tagsMap['pathToId'][$tag->path] = $tag->id;
						}
						
						$menuItems = array();
						
						foreach($tagsMenuItems as $menuItem)
						{
							$parentID = isset($menuItem->query['parent_id']) ? $menuItem->query['parent_id'] : $tagsMap['pathToId'][''];
							$menuItems[] = array('tagPath' => $tagsMap['idToPath'][$parentID], 'id' => $menuItem->id);
						}
						
						usort($menuItems, function($a, $b) {
							return $a < $b;
						});
						
						$this->_tagsMap = array('path' => $tagsMap['idToPath'], 'menu' => $menuItems);
						
					}
					
				}
			}
			
			$this->_path = $path;
			
			$this->_router->attachBuildRule(array($this, 'buildBefore'), JRouter::PROCESS_BEFORE);
			$this->_router->attachBuildRule(array($this, 'buildAfter'), JRouter::PROCESS_AFTER);
			
			if(!is_null($this->_sef) || $this->_rewrite !== 'SERVER')
			{
				if($this->_langFragment && mb_substr($defTAG, 0, 2) === $base)
				{	
					if(!empty($languageFilterParams->remove_default_prefix))
					{
						$uri->setPath(preg_replace('#' . preg_quote('/' . $this->_langFragment, '#') . '#u', '/', $origPath, 1));
						$uri->setQuery($origQuery);
						
						$app->redirect($uri->toString(), 301);
					}
				}
				else if($this->_langFragment === false)
				{
					if($cookie = $app->input->cookie->get(JApplicationHelper::getHash('language')))
					{
						if($cookie !== $defTAG)
						{
							$time = empty($languageFilterParams->lang_cookie) ? 0 : time() + 365 * 86400;
							$app->input->cookie->set(JApplicationHelper::getHash('language'), $defTAG, $time, $app->get('cookie_path', '/'), $app->get('cookie_domain'), $app->isSSLConnection());
							JFactory::getLanguage()->setLanguage($defTAG);
						}
					}
				}
				
				$this->_router->attachParseRule(array($this, 'parse'));
			}
			
			if($this->_scriptExecutionTime)
			{
				$this->_countScriptExecutionTime();
			}
			
		}
		
	}
	
	
	PRIVATE FUNCTION _getComponentSef($alias, $option, $view)
	{
		$dbo = JFactory::getDbo();
		
		if($view === 'category')
		{
			$dbo->setQuery($dbo->getQuery(true)
				->SELECT($dbo->quoteName('id'))
				->FROM($dbo->quoteName("#__categories"))
				->WHERE($dbo->quoteName('alias') . ' = ' . $dbo->quote($alias))
				->WHERE($dbo->quoteName('extension') . ' = ' . $dbo->quote("com_$option"))
				->WHERE($dbo->quoteName('language') . ' IN(' . $dbo->quote($this->_langTAG) . ',' . $dbo->quote('*') . ')')
			);
			
			if($id = $dbo->loadResult())
			{
				return "component/$option/$view/$id-$alias";
			}
		}
		else
		{
			$name_table = $dbo->quoteName('#__' . ($option === 'contact' ? 'contact_details' : $option));
			$name_table_tags = $dbo->quoteName('#__tags');
			
			$name_id = $dbo->quoteName('id');
			$name_path = $dbo->quoteName('path');
			$name_alias = $dbo->quoteName('alias');
			$name_language = $dbo->quoteName('language');
			
			$val_alias = $dbo->quote($alias);
			$val_lang = $dbo->quote($this->_langTAG);
			$val_all = $dbo->quote('*');
			
			$query = "SELECT 
						 $name_id, 
						 null AS $name_path 
					  FROM 
						 $name_table 
					  WHERE 
						 $name_alias = $val_alias 
					  AND 
						 $name_language IN($val_lang,$val_all)";
			
			if($fixTagRouting = $this->params->get('fix_tag_routing'))
			{
				$query .= " UNION ALL
					SELECT 
						$name_id,
						$name_path 
					FROM 
						$name_table_tags 
					WHERE 
						$name_language IN($val_lang,$val_all)";
			}
			
			
			$dbo->setQuery($query);
			
			if($list = $dbo->loadObjectList())
			{
				if($fixTagRouting)
				{
					$tags = array();
				
					foreach($list as $key => $item)
					{
						if(!is_null($item->path))
						{
							$tags[] = $item;
							unset($list[$key]);
						}
					}
					
					$this->_tagsMap['tags'] = $tags;
				}
				
				$item = array_shift($list);
				return "component/$option/$view/{$item->id}-$alias";
				
			}
			
		}
	}
	
	
	PRIVATE FUNCTION _getTags()
	{
		$dbo = JFactory::getDbo();
	
		$dbo->setQuery($dbo->getQuery(true)
			->SELECT(array($dbo->quoteName('path'), $dbo->quoteName('id')))
			->FROM($dbo->quoteName('#__tags'))
			->WHERE($dbo->quoteName('language') . ' IN(' . $dbo->quote($this->_langTAG) . ',' . $dbo->quote('*') . ')')
		);
		
		return $dbo->loadObjectList();
	}
	
	
	PRIVATE FUNCTION _getElements($alias, $categoryFragments, $menuFragments, $fragments, $component, $restrictedSearch)
	{
		$query = '';
		
		$categoryLevel = count($categoryFragments);
		$menuLevel = count($menuFragments);
		$fragmentsLevel = count($fragments);
		
		$categoryPath = implode('/', $categoryFragments);
		
		$dbo = JFactory::getDbo();
	
		$table_content = $dbo->quoteName('#__content');
		$table_categories = $dbo->quoteName('#__categories');
		$table_contact_details = $dbo->quoteName('#__contact_details');
		$table_newsfeeds = $dbo->quoteName('#__newsfeeds');
		$table_tags = $dbo->quoteName('#__tags');
		
		$name_id = $dbo->quoteName('id');
		$name_catid = $dbo->quoteName('catid');
		$name_parentid = $dbo->quoteName('parent_id');
		$name_alias = $dbo->quoteName('alias');
		$name_language = $dbo->quoteName('language');
		$name_path = $dbo->quoteName('path');
		$name_level = $dbo->quoteName('level');
		$name_extension = $dbo->quoteName('extension');
		
		$val_alias = $dbo->quote($alias);
		$val_com_tags = $dbo->quote('com_tags');
		$val_language = $dbo->quote($this->_langTAG);
		$val_all = $dbo->quote('*');
		
		// --------------------------------------------------- //
		
		$extensions = array();
		$tables = array();
		
		$defaultComponent = $this->_defaultMenuItem->component;
		
		switch($component)
		{
			case 'com_content' : $tables[$component] = $table_content;
				break;
			case 'com_contact' : $tables[$component] = $table_contact_details;
				break;
			case 'com_newsfeeds' : $tables[$component] = $table_newsfeeds;
		}
		
		if($defaultComponent !== $component && !$restrictedSearch)
		{
			switch($defaultComponent)
			{
				case 'com_content' : $tables[$defaultComponent] = $table_content;
					break;
				case 'com_contact' : $tables[$defaultComponent] = $table_contact_details;
					break;
				case 'com_newsfeeds' : $tables[$defaultComponent] = $table_newsfeeds;
			}
		}
		
		// Search tables
		
		if(!empty($tables))
		{
			foreach($tables as $name => $table)
			{
				$extensions[$name] = $dbo->quote($name);
			}
			
			$extensionList = implode(',', $extensions);
			$aliasList = implode(',', $dbo->quote($restrictedSearch ? $categoryFragments : $fragments));
			
			$query = "(
				SELECT 
					$name_id, 
					$name_language, 
					$name_alias, 
					$name_parentid, 
					$name_path,
					$name_level,
					$name_extension 
				FROM $table_categories
				WHERE $name_alias IN($aliasList)
				AND $name_extension IN ($extensionList)
				AND $name_language IN($val_language,$val_all)
			)";
			
			foreach($tables as $name => $table)
			{
				$query .= " UNION ALL (
					SELECT 
						$name_id, 
						$name_language, 
						$name_alias, 
						$name_catid AS $name_parentid, 
						null AS $name_path,
						null AS $name_level,
						" . $extensions[$name] . " AS $name_extension 
					FROM $table
					WHERE $name_alias = $val_alias 
					AND $name_language IN($val_language,$val_all)
				)";
			}
		}
		
		$fixTagRouting = $this->params->get('fix_tag_routing') && !array_key_exists('tags', $this->_tagsMap);
		
		if($component === 'com_tags' || $fixTagRouting)
		{
			if (!empty($query)) {
				$query .= " UNION ALL ";
			}
			
			$whereClause = '';
			
			if(!$fixTagRouting)
			{
				$whereClause .= "WHERE $name_alias = $val_alias ";
			}
			else
			{
				$this->_tagsMap['tags'] = array();
			}
			
			$whereClause .= ($whereClause ? 'AND' : 'WHERE') . " $name_language IN($val_language,$val_all)";
			
			$query .= "(SELECT 
				$name_id,
				$name_language,
				$name_alias,
				$name_parentid,
				$name_path,
				$name_level,
				$val_com_tags AS $name_extension 
			FROM $table_tags 
			$whereClause)";
		}
		
		if($query)
		{
			$dbo->setQuery($query);
			
			if($elements = $dbo->loadObjectList())
			{
				$items = array();
				$rfragments = array_reverse($fragments);
				$level = $restrictedSearch ? $categoryLevel : $fragmentsLevel;
				
				for($i = 0; $i < $level; $i++)
				{
					$newItems = array();
					$newElements = array();
					
					foreach($elements as $index => $element)
					{
						if($i === 0)
						{
							if($element->extension === 'com_tags')
							{
								if(array_key_exists('tags', $this->_tagsMap))
								{
									$this->_tagsMap['tags'][] = $element;
								}
								
								if($element->alias === $rfragments[$i])
								{
									$element->descendants = array($element->parent_id);
									$element->secondary_path = $element->alias;
									
									$items[] = $element;
								}
								
								unset($elements[$index]);
								
							}
							else if(!$element->path || ($element->alias === $rfragments[$i] 
								&& $this->_strendmatch($element->path, $categoryPath)))
							{
								$element->descendants = array($element->parent_id);
								$element->secondary_path = $element->alias;
								
								$items[] = $element;
								unset($elements[$index]);
							}
						}
						else if($element->alias === $rfragments[$i])
						{
							foreach($items as $key => &$item)
							{
								if($item->descendants[0] == $element->id)
								{
									array_unshift($item->descendants, $element->parent_id);
									
									if($i === 1)
									{
										$item->secondary_path = $element->path . '/' . $item->secondary_path;
									}
									
									$newItems[] = $item;
									unset($elements[$index]);
								}
							}
						}
					}
					
					if($i > 0)
					{
						if($i < $categoryLevel)
						{
							$items = $newItems;
						}
						
						if(empty($newItems))
						{
							break;
						}
						
					}
					
				}
				
				return $items;

			}
		}
		
	}
	
	
	PRIVATE FUNCTION _getMatchingMode($menuItem, &$menuID, $noRoot = false)
	{
		$isParent = in_array($menuItem->query['view'], array('category', 'categories', 'tags'));
		$menuID = isset($menuItem->query['id']) ? $menuItem->query['id'] : (isset($menuItem->query['parent_id']) ? $menuItem->query['parent_id'] : null);
		
		if(!is_null($menuID) && empty($menuID) && !$noRoot)
		{
			$menuID = 1;
		}
		
		switch($this->_duplicateHandlingMode)
		{
			case 0 : return 3;
				break;
			case 1 : return 2;
				break;
			case 2 : return !$isParent ? 0 : (isset($menuID) ? 1 : 3);
				break;
		}
	}
	
	
	PRIVATE FUNCTION _getSef($categoryFragments, $menuFragments, $menuItem, $restrictedSearch)
	{
		$level = count($categoryFragments);
		$alias = $categoryFragments[$level - 1];
		
		$fragments = array_merge($menuFragments, $categoryFragments);
		$fullLevel = count($fragments);
		
		$categoryPath = implode('/', $categoryFragments);
		
		$list = $this->_getElements($alias, $categoryFragments, $menuFragments, $fragments, $menuItem->component, $restrictedSearch);
		
		if(!empty($list))
		{	
			$menuID = null;
			$mode = $this->_getMatchingMode($menuItem, $menuID);
			
			$item = null;
			$otherComponentItems = array();
			
			foreach ($list as $key => &$itemCandidate)
			{
				$ancestorIndex = count($itemCandidate->descendants) - $level;
				$itemCandidate->topAncestor = $itemCandidate->descendants[$ancestorIndex];
				
				array_shift($itemCandidate->descendants);
				
				if($itemCandidate->extension !== $menuItem->component)
				{
					$otherComponentItems[] = $itemCandidate;
					unset($list[$key]);
				}
				else if($mode && $menuID && $itemCandidate->topAncestor == $menuID
					&& $this->_strendmatch($itemCandidate->secondary_path, $categoryPath))
				{
					$item = $itemCandidate;
				}
			}
			
			$list = array_merge($list, $otherComponentItems);
			
			if(!$item)
			{
				$fullPath = implode('/', $fragments);
				$mode = $this->_getMatchingMode($this->_defaultMenuItem, $menuID);
				
				if($mode && $menuID)
				{
					foreach ($list as $itemCandidate)
					{
						if($itemCandidate->extension === $this->_defaultMenuItem->component
							&& $itemCandidate->topAncestor == $menuID
								&& $this->_strendmatch($itemCandidate->secondary_path, $fullPath))
						{
							$item = $itemCandidate;
							break;
						}
					}
				}
				
				if(!$item && $mode > 1)
				{
					foreach($list as $itemCandidate)
					{
						if($this->_defaultMenuItem->component === $itemCandidate->extension
							&& $itemCandidate->secondary_path === $fullPath)
						{
							$item = $itemCandidate;
							break;
						}
					}
				}
				
				if($item)
				{
					$level = $fullLevel;
					$menuFragments = array();
					$categoryFragments = $fragments;
				}
			}
			
			if(!$item && ($mode == 3 || $menuItem->component == 'com_tags'))
			{
				$item = array_shift($list);
			}
			
			if($item)
			{	
				if(in_array($item->extension, array('com_contact', 'com_newsfeeds')))
				{
					array_push($item->descendants, $item->id);
					
					if($descOffset = count($item->descendants) - $level)
					{
						$item->descendants = array_slice($item->descendants, $descOffset);
					}
				}
				else
				{
					$item->descendants = array();
					
					if($item->path)
					{
						$item->descendants[] = $item->id;
					}
					else
					{
						if($level > 1)
						{
							$item->descendants[] = $item->parent_id;
						}
						
						$item->descendants[$level - 1] = $item->id;
					}
				}
				
				for($i = 0; $i < $level; $i++)
				{
					if(isset($item->descendants[$i]))
					{
						$categoryFragments[$i] = $item->descendants[$i] . '-' . $categoryFragments[$i];
					}
				}
				
				$fragments = array_merge($menuFragments, $categoryFragments);
				$sef = implode('/', $fragments);
				
				return $sef;
				
			}
			
			if($this->_duplicateHandlingMode == 2)
			{
				$this->_raiseDelayedError = true;
			}
			
		}
		
	}
	
	
	PUBLIC FUNCTION parse(&$router, &$uri)
	{
		$vars = array();
		$path = $uri->getPath();
		
		if($this->_rewrite !== 'SERVER')
		{
			$rewrite = JUri::getInstance($this->_rewrite);
			$vars = $rewrite->getQuery(true);
			
			if(is_null($this->_sef))
			{
				$path = $rewrite->getPath();
				
				if($this->_rootlen)
				{
					$path = substr($path, $this->_rootlen);
				}
				
				if($path && $path !== '/')
				{
					$this->_sef = substr($path, 1);
				}
				else if(!empty($vars))
				{
					$route = explode('?', $router->build('index.php?' . $rewrite->getQuery()), 2);
					
					if($route[0])
					{
						$this->_sef = substr($route[0], 1);
						
						if(!$this->_sefRewrite)
						{
							if(stripos($this->_sef, 'index.php/') === 0)
							{
								$this->_sef = substr($this->_sef, 10);
							}
						}
						
					}
					
				}
				
			}
			
		}
		
		$lang = $this->_langFragment && mb_stripos($path, $this->_langFragment) === 0 ? $this->_langFragment : '';
		$uri->setPath($lang . $this->_sef);
		
		if(!empty($vars))
		{
			$uri->setQuery($vars);
		}
		
		return $vars;
	}
	
	
	PUBLIC FUNCTION buildBefore(&$siteRouter, &$uri)
	{
		$query = $uri->getQuery(true);
		
		if(isset($query['option']) && in_array($query['option'], array('com_content', 'com_contact', 'com_tags', 'com_newsfeeds')))
		{
			$uri->setVar('sefwizardOverrider', $query);
			$this->fixTagRouting($uri, $query);
			$this->debugVariables($uri, $query);
		}
		
		return $query;
		
	}
	
	
	PUBLIC FUNCTION buildAfter(&$siteRouter, &$uri)
	{
		$queryString = $uri->getQuery(true);
		
		if(isset($queryString['sefwizardOverrider']))
		{
			$query = $queryString['sefwizardOverrider'];
			
			$uri->delVar('sefwizardOverrider');
			unset($queryString['sefwizardOverrider']);
			
			$path = $uri->getPath();
			
			$this->removeID($path, $query);
			$this->rewritePagination($path, $query, $queryString);
			
			$uri->setPath($path);
			$uri->setQuery($queryString);
		}
		
		return $queryString;
		
	}
	
	
	PUBLIC FUNCTION fixTagRouting(&$uri, &$query)
	{
		if($query['option'] === 'com_tags' && !empty($this->_tagsMap['menu']))
		{
			if(!isset($query['Itemid']))
			{
				$id = intval($query['id']);
				$tagPath = $this->_tagsMap['path'][$id];
				
				foreach($this->_tagsMap['menu'] as $menuItem)
				{
					if(!$menuItem['tagPath'] || strpos($tagPath, $menuItem['tagPath']) === 0)
					{
						$uri->setVar('Itemid', $menuItem['id']);
						break;
					}
				}
			}
		}
	}
	
	
	PUBLIC FUNCTION removeID(&$path, $query)
	{
		if(array_key_exists($query['option'], $this->_id_options)
			&& isset($query['id']) && !is_array($query['id']))
		{
			$offset = 0;
			
			if(isset($query['Itemid']))
			{
				$menuitem = $this->_menu->getItem($query['Itemid']);
				
				if($menuitem && !$menuitem->home && strpos($path, $menuitem->route) !== false)
				{
					$offset += strlen($menuitem->route);
				}
			}
			
			if($offset < strlen($path))
			{
				if($query['option'] === 'com_content')
				{
					$id = $this->_getIDfragment($query['id']);
					
					if($query['view'] === 'article')
					{
						$ending = '';
						$pos = strrpos($path, $id, $offset);
						
						if($pos !== false)
						{
							$ending = substr($path, $pos + strlen($id));
							$path = substr_replace($path, '/', $pos);
						}
						
						if(isset($query['catid']) && $offset < strlen($path) - 1)
						{
							$catid = $this->_getIDfragment($query['catid']);
							$path = $this->_removeCatID($path, $catid, $query, $offset);
						}
						
						$path .= $ending;
						
					}
					else
					{
						$path = $this->_removeCatID($path, $id, $query, $offset);
					}
					
				}
				else
				{
					$path = preg_replace('#(?<=.{' . $offset . '})/\d+-#', '/', $path);
				}
			}
			
		}
	}
	
	
	PUBLIC FUNCTION rewritePagination(&$path, &$query, &$queryString)
	{
		if($this->_paginationRewrite && $query['option'] === 'com_content')
		{
			if($path === '/')
			{
				$path = '';
			}
			
			if(isset($queryString['start']))
			{
				$pageNum = $this->_limit ? ceil($queryString['start'] / $this->_limit) : $queryString['start'];
				unset($queryString['start']);
			}
			
			if(isset($queryString['limitstart']))
			{
				$pageNum = $this->_limit ? ceil($queryString['limitstart'] / $this->_limit) : $queryString['limitstart'];
				unset($queryString['limitstart']);
			}
			
			if(isset($queryString['showall']))
			{
				$showAll = $queryString['showall'];
				unset($queryString['showall']);
			}
			
			if(isset($queryString['limit']))
			{
				unset($queryString['limit']);
			}
			
			if(!empty($showAll))
			{
				$path .= "/{$this->_showAllTag}";
			}
			else if(!empty($pageNum))
			{
				$path .= "/{$this->_pageTag}-" . ($pageNum + 1);
			}
		}
		
	}
	
	
	PUBLIC FUNCTION debugVariables($uri, $query)
	{
		if($this->_showRouterVariables)
		{
			$this->_uri[] = array($uri, $query);
		}
	}
	
	
	PRIVATE FUNCTION _getIDfragment($stringID)
	{
		return preg_replace('#[^\d]*([\d]+).*#', '/$1-', (string) $stringID);
	}
	
	
	PRIVATE FUNCTION _removeCatID($path, $catid, $query, $offset)
	{
		$pos = strpos($path, $catid, $offset);
		
		if($pos !== false)
		{
			$path = substr_replace($path, '/', $pos, strlen($catid));
		}
		
		return $path;
		
	}
	
	
	PUBLIC FUNCTION onAfterRoute()
	{
		if($this->_execute)
		{
			if($this->_scriptExecutionTime)
			{
				$this->_countScriptExecutionTime();
			}
			
			$app = JFactory::getApplication();
			$menuItem = $this->_menu->getActive();
			$option = $app->input->get('option');
			$view = $app->input->get('view');
			
			if($this->_duplicateHandlingMode && $menuItem && in_array($option, array('com_content', 'com_contact', 'com_tags', 'com_newsfeeds'))
				&& !array_key_exists($option, $this->_id_options))
			{
				if($menuItem->home)
				{
					$path = $this->_duplicateHandlingMode == 2 ? $this->_path : '';
				}
				else
				{
					$path = preg_replace('#^' . preg_quote($menuItem->route, '#') . '/?#', '', $this->_path);
				}

				if($path)
				{
					if(in_array($option, array('com_contact', 'com_newsfeeds')))
					{
						$path = preg_replace('#(^|/)\d+\-#', '$1', $path);
					}
					else
					{
						if($view === 'article')
						{
							$path = preg_replace('#^\d+\-|/\d+\-[^/]+$#', '', $path);
						}
						else
						{
							$path = preg_replace('#^\d+\-$#', '', $path);
						}
						
						if($path)
						{
							$menuFragments = explode('/', $menuItem->route);
							$categoryFragments = explode('/', $path);
							
							$this->_getSef($categoryFragments, $menuFragments, $menuItem, true);
						}
					}
				}
			}
			
			if($this->_paginationRewrite)
			{
				$layout = $app->input->get('layout');

				if($view === 'article')
				{
					if($this->_page === $this->_showAllTag)
					{
						$app->input->set('showall', 1);
						$this->_router->setVar('showall', 1);
					}
					else if($this->_page)
					{
						$page = $this->_page - 1;
					}
				}
				else
				{
					$componentParams = JComponentHelper::getParams('com_content');
					
					if ($menuItem && !is_null($menuItem->params->get('display_num')))
					{
						$layout = null;
					}
					
					if(in_array($view, array('categories', 'featured')) || 
						$view === 'category' && $layout)
					{
						if($menuItem)
						{
							$leading = $menuItem->params->get('num_leading_articles');
							$intro = $menuItem->params->get('num_intro_articles');
						}
						
						if(!isset($leading))
						{
							$leading = $componentParams->get('num_leading_articles', 0);
						}
						
						if(!isset($intro))
						{
							$intro = $componentParams->get('num_intro_articles', 0);
						}
						
						$this->_limit = $leading + $intro;
						
					}
					else if(empty($_POST))
					{
						if(!($this->_limit = JFactory::getSession()->get('sefwizard_page_limit'))
							&& $menuItem)
						{
							$this->_limit = $menuItem->params->get('display_num', 0);
						}
						
						if(!$this->_limit)
						{
							$this->_limit = $componentParams->get('display_num', 0);
						}
					}
					else if($limit = $app->input->post->getInt('limit'))
					{
						JFactory::getSession()->set('sefwizard_page_limit', $limit);
						$this->_limit = $limit;
					}
					
					$page = $this->_page && $this->_limit ? $this->_limit * ($this->_page - 1) : null;
					
				}
				
				if(!empty($page))
				{
					$app->input->set('limitstart', $page);
					$this->_router->setVar('limitstart', $page);
				}
			}
			
			if($this->_scriptExecutionTime)
			{
				$this->_countScriptExecutionTime();
			}
		}
	}
	
	
	PUBLIC FUNCTION onAfterDispatch()
	{
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		
		if($this->_execute && $doc->getType() === 'html')
		{
			if($this->_raiseDelayedError)
			{
				throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
			}
			
			if($this->_scriptExecutionTime)
			{
				$this->_countScriptExecutionTime();
			}
			
			$option = $app->input->get('option');
			$view = $app->input->get('view');
			$id = $app->input->get('id');
			
			$uri = JURI::getInstance();
			$uriPath = $uri->getPath();
			$query = $uri->getQuery(true);
			
			if($this->_paginationRewrite && $option === 'com_content' && is_numeric($this->_page))
			{
				$cmodel = JModelLegacy::getInstance($view, 'ContentModel');
				
				if($view === 'article')
				{
					$item = $cmodel->getItem($id);
					$text = $item->fulltext ? $item->fulltext : $item->introtext;
					
					$exit = !preg_match('#<hr[^>]+?[\'"]system\-pagebreak[\'"][^>]*>#i', $text);
				}
				else if ($pagination = $cmodel->getPagination())
				{
					$exit = $this->_page > $pagination->get('pagesTotal', $pagination->get('pages.total'));
				}
				else
				{
					$exit = !count($cmodel->getItems());
				}
			
				if($exit)
				{
					throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
				}
				
				if ($this->params->get('rewrite_meta', 1))
				{
					JFactory::getLanguage()->load('plg_content_pagebreak', JPATH_ADMINISTRATOR);
					
					if ($originalTitle = $doc->getTitle())
					{
						if($title_mask = $this->params->get('title_mask'))
						{
							$title = str_replace('%n', $this->_page, $title_mask, $count);
					
							if($count)
							{
								$title = str_replace('%p', JText::_('PLG_SEFWIZARD_PAGE'), $title);
								$title = str_replace('%t', preg_replace('#[\s\-]*' . preg_quote(JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $this->_page), '#') . '#u', '', $originalTitle), $title, $count);
								
								if($count)
								{
									$doc->setTitle($title);
								}
							}
						}
					}
					
					if ($originalDescription = $doc->getMetadata('description'))
					{
						if($description_mask = $this->params->get('description_mask'))
						{
							$description = str_replace('%n', $this->_page, $description_mask, $count);
					
							if($count)
							{
								$description = str_replace('%p', JText::_('PLG_SEFWIZARD_PAGE'), $description);
								$description = str_replace('%d', preg_replace('#[\s\-]*' . preg_quote(JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $this->_page), '#') . '#u', '', $originalDescription), $description, $count);
								
								if($count)
								{
									$doc->setMetaData('description', $description);
								}
							}
						}
					}
					
					if(empty($count) && $this->_limit)
					{
						$page = JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $this->_page);
						
						if ($originalTitle)
						{
							$doc->setTitle($originalTitle . ' - ' . $page);
						}
						
						if ($originalDescription)
						{
							$doc->setMetaData('description', $originalDescription . ' - ' . $page);
						}
					}
				}
			}
			
			
			if (stripos($this->_path, 'component/') === 0 && $this->params->get('duplicate_raw_noindex'))
			{
				$doc->setMetaData('robots', 'noindex');
			}
			else if (!empty($_SERVER['QUERY_STRING']))
			{
				if ($nonsef_noindex = $this->params->get('duplicate_nonsef_noindex'))
				{
					if ($nonsef_noindex == 1)
					{
						$doc->setMetaData('robots', 'noindex');
					}
					else
					{
						$doc->addHeadLink(JURI::current(), 'canonical');
					}
				}
			}
			
			if($this->_rewrite === 'SERVER')
			{
				$duplicate_standard = in_array($option, array('com_content', 'com_contact', 'com_tags', 'com_newsfeeds'));
			
				$duplicate_standard_type = $this->params->get('duplicate_standard');
				$duplicate_thirdparty_type = $this->params->get('duplicate_third_party');
				
				if(($duplicate_standard && $duplicate_standard_type && $app->input->get('view') !== 'archive')
					|| (!$duplicate_standard && $duplicate_thirdparty_type))
				{
					$vars = $this->_router->getVars();
					
					$duplicate_handling = $duplicate_standard ? $duplicate_standard_type : $duplicate_thirdparty_type;
					
					$url_parts = explode('?', JRoute::_('index.php?' . http_build_query($vars), false), 2);
					$path = $url_parts[0];
					
					$exit = false;
					
					if($this->_paginationRewrite && $option === 'com_content')
					{
						$suffix = '';
						
						if($this->_sefSuffix)
						{
							if(preg_match("#^(.+?)(\.[^\.]+)$#", $path, $matches))
							{
								$path = $matches[1];
								$suffix = $matches[2];
							}
							else if(is_numeric($this->_page))
							{
								if(mb_substr($path, -1) === '/')
								{
									if($pos = mb_strrpos($uriPath, '.'))
									{
										$suffix = mb_substr($uriPath, $pos);
									}
									else
									{
										$suffix = '.html';
									}
								}
							}
						}
						
						if(isset($query['start']))
						{
							$pageNum = $this->_limit ? ceil($query['start'] / $this->_limit) : $query['start'];
							unset($query['start']);
						}
						
						if(isset($query['limitstart']))
						{
							$pageNum = $this->_limit ? ceil($query['limitstart'] / $this->_limit) : $query['limitstart'];
							unset($query['limitstart']);
						}
						
						if(isset($query['showall']))
						{
							if(!empty($query['showall']))
							{
								$pageNum = null;
							}
							unset($query['showall']);
							$exit = true;
						}
						
						if(isset($pageNum))
						{
							$path = preg_replace('#/' . $this->_pageTag . '\-\d+$#u', '', $path) . "/{$this->_pageTag}-" . ($pageNum + 1);
							$exit = true;
						}
						else if($exit)
						{
							$path = preg_replace('#/(?:' . $this->_showAllTag . '|' . $this->_pageTag . '\-\d+)$#u', '', $path) . "/{$this->_showAllTag}";
						}
						
						if(!empty($this->_sefSuffixes))
						{
							$path .= implode('', $this->_sefSuffixes);
						}
						
						$path .= $suffix;
						
					}
					
					$root = JURI::root(true);
					
					if (strpos($path, '//') !== false) {
						$path = preg_replace('#/{2,}#', '/', $path);
					}
					
					$canonical = $uri->toString(array('scheme', 'host', 'port')) . $path;
					
					if($exit || $canonical !== $this->_decodeUri(JURI::current()) && stripos($uriPath, "$root/component") !== 0
						&& $uriPath !== "$root/index.php" && $uriPath !== "$root/")
					{
						if ($duplicate_handling == 2 || !empty($url_parts[1]) && preg_match('#\b(?:cat|Item)?id=#i', $url_parts[1]))
						{
							throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
						}
						else
						{
							if (!empty($query)) {
								$canonical .= '?' . http_build_query($query);
							}
							if ($fragment = $uri->getFragment()) {
								$canonical .= '#' . $fragment;
							}
							
							$duplicate_handling == 1 ? $app->redirect($canonical, 301) : $doc->addHeadLink($canonical, 'canonical');
						}
					}
				}
			}
			
			
			if($this->_scriptExecutionTime)
			{
				$this->_countScriptExecutionTime();
			}
			
		}
		
	}
	
	
	PUBLIC FUNCTION onAfterRender()
	{
		$app = JFactory::getApplication();
		
		if($this->_execute && (!JFactory::getConfig()->get('offline')
			|| !JFactory::getUser()->guest))
		{
			if($this->_scriptExecutionTime)
			{
				$this->_countScriptExecutionTime();
			}
			
			$html = $app->getBody();
			
			if($this->_showRouterVariables)
			{
				$html = $this->_addRouterVariables($html);
			}
			
			if($this->_scriptExecutionTime)
			{
				$html = $this->_countScriptExecutionTime($html);
			}
			
			$app->setBody($html);
		}
	}
	
	
	PRIVATE FUNCTION _strendmatch($haystack, $needle)
	{
		if($haystack && $needle)
		{
			$offset = strlen($haystack) - strlen($needle);	
			return $offset >= 0 && strpos($haystack, $needle, $offset) !== false;
		}
	}
	
	
	PRIVATE FUNCTION _addRouterVariables($html)
	{
		$notice = '';
		
		foreach($this->_uri as $uri)
		{
			$uri = array('url' => $uri[0]->toString(), 'vars' => $uri[1]);
			$notice .= '<pre style="margin: 15px; text-align: left">' . print_r($uri, true) . '</pre>';
		}
		
		return preg_replace('#<body[^>]*>#', '$0' . '<div><p style="font-size: 22px; font-weight: bold; margin: 15px; text-align: left">Router variables</p>' . $notice . '</div>', $html);

	}
	
	
	PRIVATE FUNCTION setCanonical($canonical)
	{
		$doc = JFactory::getDocument();
		
		if (method_exists($doc, $canonical))
		{
			$doc->addHeadLink($canonical, 'canonical');
		}
	}
	
	
	PRIVATE FUNCTION _countScriptExecutionTime($html = null)
	{
		static $result = array(), $format = 3;
		
		$backtrace = debug_backtrace();
		$caller = $backtrace[1]['function'];
		
		$result[$caller] = !isset($result[$caller]) ? microtime(true) : 
			number_format((microtime(true) - $result[$caller]), $format);
		
		if($html)
		{
			$total = number_format((array_sum($result)), $format);
			$notice = __CLASS__ . ' (PHP script execution time):';
			
			if($this->_scriptExecutionTime == 1)
			{
				$notice = "\\n {$notice}";
				foreach($result as $name => $time)
				{
					$notice .= "\\n $name: $time sec.";
				}	
				$notice .= "\\n Total execution time: {$total} sec.";
				return preg_replace('#</head>#', "<script>if('console' in window && console.log) console.log('$notice')</script>$0", $html);
			}
			else
			{
				$notice = "<div><p style='margin: 15px; text-align: left'>{$notice}";
				foreach($result as $name => $time)
				{
					$notice .= "<br>$name: $time sec.";
				}	
				$notice .= "<br>total execution time: <b>{$total} sec.</b></div>";
				return preg_replace('#<body[^>]*>#', "$0{$notice}", $html);
			}
		}
		
	}
}
