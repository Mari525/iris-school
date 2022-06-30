<?php
/**
 * Visdata controller for Visforms
 *
 * @author       Aicha Vack
 * @package      Joomla.Site
 * @subpackage   com_visforms
 * @link         http://www.vi-solutions.de 
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2012 vi-solutions
 * @since        Joomla 1.6 
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
require_once JPATH_SITE.'/components/com_visforms/controller.php';
require_once JPATH_ADMINISTRATOR.'/components/com_visforms/models/visdata.php';

use Joomla\Utilities\ArrayHelper;

class VisformsControllerVisformsdata extends VisformsController
{	
	public function __construct($config = array()) {
		parent::__construct($config);
		$this->registerTask('unpublish', 'publish');
		$this->setParentViewReturnUrl();
	}

	//ToDo could be moved in a shared controller
	protected function setParentViewReturnUrl() {
		$return = $this->input->get('return', '');
		$this->parentViewReturnUrl = (!empty($return)) ? JHtmlVisforms::base64_url_decode($return) : JURI::base();
	}

	public function publish() {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $fid = $this->input->get('id', 0, 'int');
        //VisformsTableVisdata expects the parameter fid
        $this->input->set('fid', $fid);
        $pk = $this->input->get('cid', null, 'array');
        //this function can be called from different views, return to return url, if a return param is set in input
	    $dataViewMenuItemExists = JHtmlVisforms::checkDataViewMenuItemExists($fid);
        $mysubmenuexists = JHTMLVisforms::checkMySubmissionsMenuItemExists();
        if (!($dataViewMenuItemExists) && !($mysubmenuexists)) {
			$this->setRedirect($this->parentViewReturnUrl, JText::_('JERROR_ALERTNOAUTHOR'), 'error');
            return false;
        }
        $success = false;
        // Make sure the item ids are integers
		ArrayHelper::toInteger($pk);
        $data = array('publish' => 1, 'unpublish' => 0);
        $task = $this->getTask();
		$value = ArrayHelper::getValue($data, $task, 0, 'int');
        //check for permission
        $user = JFactory::getUser();
        $userId	= $user->get('id');
        $canDo = VisformsHelper::getActions($fid);
		if ($canDo->get('core.edit.data.state')) {
			if (!empty($pk)) {
				$model = $this->getModel('Visdata', 'Visformsmodel');
				try {
					$result = $model->publish($pk, $value);
					if ($value == 1) {
						$this->setMessage(JText::_('COM_VISFORMS_RECORDSET_PUBLISHED'));
					} elseif ($value == 0) {
						$this->setMessage(JText::_('COM_VISFORMS_RECORDSET_UNPUBLISHED'));
					}
					$success = true;
				}
				catch (Exception $e) {
					$this->setMessage($e->getMessage(), 'error');
				}
			} else {
				$success = false;
			}
		} else {
			$this->setMessage(JText::_('COM_VISFORMS_NO_PUBLISH_AUTHOR'), 'error');
			$success = false;
		}
		$this->setRedirect($this->parentViewReturnUrl);
		return $success;
	}
}
?>
