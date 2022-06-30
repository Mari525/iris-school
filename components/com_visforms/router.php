<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die( 'Restricted access' );
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\CMS\Component\Router\RouterBase;

class VisformsRouter extends RouterView
{
	protected $noIDs = false;
	public function __construct($app = null, $menu = null)
	{
		$visforms = new RouterViewConfiguration('visforms');
		$visforms->setKey('id');
		$visforms->addLayout('message');
		$this->registerView($visforms);
		$visformsdata = new RouterViewConfiguration('visformsdata');
		$visformsdata->setKey('id');
		$visformsdata->removeLayout('default');
		$visformsdata->addLayout('data');
		$visformsdata->addLayout('detail');
		$visformsdata->addLayout('dataeditlist');
		$visformsdata->addLayout('detailedit');
		$this->registerView($visformsdata);
		$data = new RouterViewConfiguration('data');
		$data->setKey('layout')->setParent($visformsdata, 'id');
		$this->registerView($data);

		$mysubmissions = new RouterViewConfiguration('mysubmissions');
		$mysubmissions->setKey('id');
		$this->registerView($mysubmissions);
		$edit = new RouterViewConfiguration('edit');
		$edit->setKey('id');
		$this->registerView($edit);
		parent::__construct($app, $menu);
		//ToDo Joomla! 3.9 should have fixed the MenuRules lookup bug -> Revert to Joomla! MenuRules
		JLoader::register('VisformsRouterRulesMenu', __DIR__ . '/helpers/route/MenuRules.php');
		$this->attachRule(new VisformsRouterRulesMenu($this));
		//$this->attachRule(new JComponentRouterRulesMenu($this));
		$this->attachRule(new StandardRules($this));
		JLoader::register('VisformsRouterRulesVisforms', __DIR__ . '/helpers/route/VisformsRules.php');
		$this->attachRule(new VisformsRouterRulesVisforms($this));
		$this->attachRule(new NomenuRules($this));
		$test = true;
	}

	public function getVisformsSegment($id, $query)
	{
		if (!strpos($id, ':')) {
			$db = \JFactory::getDbo();
			$dbQuery = $db->getQuery(true)
				->select($db->qn('name'))
				->from($db->qn('#__visforms'))
				->where($db->qn('id') . ' = ' . (int) $query['id']);
			$db->setQuery($dbQuery);
			$id .= ':' . $db->loadResult();
		}

		return array((int) $id => $id);
	}
	public function getVisformsdataSegment($id, $query)
	{
		return $this->getVisformsSegment($id,$query);
	}

	public function getMysubmissionsSegment($id, $query)
	{
		return $this->getVisformsSegment($id,$query);
	}

	public function getVisformsId($segment, $query) {
		return (int) $segment;
	}

	public function getVisformsdataId($segment, $query) {
		return (int) $segment;
	}

	public function getMysubmissionsId($segment, $query) {
		return (int) $segment;
	}

	public function getEditSegment($id, $query)
	{
		return $this->getVisformsSegment($id,$query);
	}

	public function getEditId($segment, $query) {
		return (int) $segment;
	}
}