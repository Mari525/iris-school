<?php
/**
 * visdforms controller for Visforms
 *
 * @author       Aicha Vack
 * @package      Joomla.Administrator
 * @subpackage   com_visforms
 * @link         http://www.vi-solutions.de 
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2012 vi-solutions
 * @since        Joomla 1.6 
 */

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

class VisformsControllerVisform extends JControllerForm
{
	public function __construct($config = array()) {
		parent::__construct($config);
		$this->registerTask('fields', 'formParts');
		$this->registerTask('datas', 'formParts');
		$this->registerTask('pdfs', 'formParts');
	}
	
	protected function postSaveHook(JModelLegacy $model, $validData = array()) {
		$item = $model->getItem();
		$id = $item->get('id');
		if ($id) {
			// create a new datatable if it doesn't already exist
			$model->createDataTables($id, $validData['saveresult']);
		}
		$spambotParams = $item->get('spamprotection');
		if (($spambotParams['spbot_log_to_db'])) {
			$model->createSpambotTableIfNotExist();
		}
	}
	
	public function batch($model = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// set the model
		$model = $this->getModel('Visform', '', array());
		// preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_visforms&view=visforms' . $this->getRedirectToListAppend(), false));
		return parent::batch($model);
	}

	public function formParts() {
		$fid = $this->input->getInt('id', -1);
		$task = $this->input->getCmd('task', 'fields');
		$context = "$this->option.edit.$this->context";
		$this->getModel()->checkin($fid);
		$this->releaseEditId($context, $fid);
		$this->setRedirect( "index.php?option=com_visforms&view=vis$task&fid=".$fid);
		return true;
	}
	
	protected function allowEdit($data = array(), $key = 'id') {
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();
		$userId = $user->get('id');
		// check general edit permission first
		if ($user->authorise('core.edit', 'com_visforms.visform.' . $recordId)) {
			return true;
		}

		// fallback on edit.own
		// first test if the permission is available
		if ($user->authorise('core.edit.own', 'com_visforms.visform.' . $recordId)) {
			// Now test the owner is the user.
			$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId) {
				// need to do a lookup from the model
				$record = $this->getModel()->getItem($recordId);
				if (empty($record)) {
					return false;
				}
				$ownerId = $record->created_by;
			}

			// if the owner matches 'me' then do the test
			if ($ownerId == $userId) {
				return true;
			}
		}
		
		return false;
	}
}
