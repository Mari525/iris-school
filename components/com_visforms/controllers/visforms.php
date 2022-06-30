<?php
/**
 * Visforms default controller
 *
 * @author       Aicha Vack
 * @package      Joomla.Site
 * @subpackage   com_visforms
 * @link         http://www.vi-solutions.de
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2012 vi-solutions
 * @since        Joomla 1.6
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class VisformsControllerVisforms extends JControllerLegacy
{
	public function captcha() {
		include("components/com_visforms/captcha/securimage.php");

		$model = $this->getModel('visforms');
		$options = array();
		// only try to set options if we have an id parameter in query else we use the captcha default options
		$formid = $this->input->get('id', null);
		if (!empty($formid)) {
			$visform = $model->getForm();
			foreach ($visform->viscaptchaoptions as $name => $value) {
				// make names shorter and set all captchaoptions as properties of form object
				$options[$name] = $value;
			}
		}
		$img = new Securimage($options);
		$img->namespace = 'form' . $this->input->getInt('id', 0);
		$img->ttf_file = "components/com_visforms/captcha/elephant.ttf";
		$img->show();
	}

	public function sendVerficationMail() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		require_once 'components/com_visforms/lib/mail/verification.php';
		$app = JFactory::getApplication();
		$verification = new VisformsMailVerification();
		echo $verification->sendVerificationMail();
		$app->close();
	}

	public static function checkVerificationCode() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$verificationMail = $app->input->post->get('verificationAddr', '', 'STRING');
		$code = $app->input->post->get('code', '', 'STRING');
		$valide = VisformsValidate::validate('verificationcode', array('value' => $code, 'verificationAddr' => $verificationMail));
		echo (empty($valide)) ? '0' : '1';
		$app->close();
	}

	public function send() {

		jimport('joomla.filesystem.folder');
		$model = $this->getModel('visforms');
		$visform = $model->getForm();
		// the display state is use in the field.php function setQueryValue in order to decide if url params from a get request should be stored in the session
		// url params (from get) are only stored if $displayStateIsNew and not in an edit view task
		// if we are in the send task, make sure, the display state is set to $displayStateIsRedisplay before any further actions are performed
		$this->setDisplayState($visform);
		$additionalSupportedFieldTypes = $this->input->post->get('addSupportedFieldType', null, 'array');
		if (!empty($additionalSupportedFieldTypes)) {
			$filter = JFilterInput::getInstance();
			foreach ($additionalSupportedFieldTypes as $add) {
				if ($filter->clean($add, 'word')) {
					$model->addSupportedFieldType($add);
				}
			}
		}
		$app = JFactory::getApplication();
		$return = $this->input->post->get('return', null, 'cmd');
		//if we come from module or plugin we remove a potential page cache created by system cache plugin of the page with the form
		$url = isset($return) ? JHTMLVisforms::base64_url_decode($return) : '';
		if (!empty($url)) {
			$cache = JFactory::getCache('page');
			$folder = JPath::clean(JPATH_CACHE . '/page');
			// clean page cache, used by system cache plugin
			if (JFolder::exists($folder)) {
				$cacheresult = $cache->remove($url, 'page');
			}
		}
		// Total length of post back data in bytes.
		$contentLength = (int) $_SERVER['CONTENT_LENGTH'];
		// Maximum allowed size of post back data in MB.
		$postMaxSize = VisformsmediaHelper::toBytes(ini_get('post_max_size'));
		// Maximum allowed size of script execution in MB.
		$memoryLimit = VisformsmediaHelper::toBytes(ini_get('memory_limit'));
		if (!(isset($visform->errors))) {
			$visform->errors = array();
		}
		// Check for the total size of post back data.
		if (($postMaxSize > 0 && $contentLength > $postMaxSize)
			|| ($memoryLimit != -1 && $contentLength > $memoryLimit)) {
			array_push($visform->errors, JText::_('COM_VISFORMS_ERROR_WARNUPLOADTOOLARGE'));
			return $this->getErrorRedirect($url);
		}
		$fields = $model->getValidatedFields();
		if ((!(count($_POST) > 0)) || (!isset($_POST['postid'])) || ($_POST['postid'] != $visform->id)) {
			array_push($visform->errors, JText::_('COM_VISFORMS_INVALID_POST'));
			// Show form again, keep values already typed in
			if ($url != "") {
				$this->setRedirect(JRoute::_($url, false));
				return false;
			} else {
				$this->display();
				return false;
			}
		}
		// include plugin spambotcheck
		if (isset($visform->spambotcheck) && $visform->spambotcheck == 1) {
			JPluginHelper::importPlugin('visforms');
			$results = $app->triggerEvent('onVisformsSpambotCheck', array('com_visforms.visform'));
			foreach ($results as $result) {
				if ($result === true) {
					array_push($visform->errors, JText::_('PLG_VISFORMS_SPAMBOTCHECK_USER_LOGIN_SPAM_TXT'));
					//Show form again, keep values already typed in
					return $this->getErrorRedirect($url);
				}
			}
		}
		// Check that data is ok, in case that javascript may not work properly
		foreach ($fields as $field) {
			if (isset($field->isValid) && $field->isValid == false) {
				//we have at least one invalid field
				//Show form again, keep values already typed in
				return $this->getErrorRedirect($url);
			}
		}
		// Captcha ok?	
		if ($visform->captcha == 1) {
			include("components/com_visforms/captcha/securimage.php");
			$responseField = $visform->context . 'viscaptcha_response';
			$img = new Securimage();
			$img->namespace = 'form' . $this->input->getInt('id', 0, 'int');
			$valid = $img->check($_POST[$responseField]);
			// we may deal with an old version of vfformview plugin and the form id is missing in the request, so we fall back on form0 as namespace
			if ($valid == false) {
				$img = new Securimage();
				$img->namespace = 'form0';
				$valid = $img->check($_POST[$responseField]);
			}

			if ($valid == false) {
				array_push($visform->errors, JText::_('COM_VISFORMS_RECAPTCHA_ERROR') . ' ' . JText::_("COM_VISFORMS_CODE_INVALID"));
				//Show form again, keep values already typed in
				return $this->getErrorRedirect($url);
			}
		}
		if ($visform->captcha == 2) {
			JPluginHelper::importPlugin('captcha');
			try {
				$res = $app->triggerEvent('onCheckAnswer');
			}
			catch (RuntimeException $e) {
				array_push($visform->errors, JText::_('COM_VISFORMS_RECAPTCHA_ERROR') . ' ' . $e->getMessage());
				//Show form again, keep values already typed in
				return $this->getErrorRedirect($url);
			}
			if (!$res[0]) {
				array_push($visform->errors, JText::_('COM_VISFORMS_RECAPTCHA_ERROR') . ' ' . JText::_("COM_VISFORMS_CODE_INVALID"));
				//Show form again, keep values already typed in
				return $this->getErrorRedirect($url);
			}
		}

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$send_once = $app->getUserState('vis_send_once' . $visform->id);
		if (!empty($send_once)) {
			$app->setUserState('vis_send_once' . $visform->id, null);
		}
		else if (isset($_SESSION['vis_send_once' . $visform->id])) {
			unset($_SESSION['vis_send_once' . $visform->id]);
		}
		else {

			array_push($visform->errors, JText::_('COM_VISFORMS_CAN_SENT_ONLY_ONCE'));
			// Show form again, keep values already typed in
			return $this->getErrorRedirect($url);
		}

		// trigger before save event
		JPluginHelper::importPlugin('visforms');
		$onBeforeFormSaveResults = $app->triggerEvent('onVisformsBeforeFormSave', array('com_visforms.form', $visform, $fields));
		if ((!empty($onBeforeFormSaveResults)) && is_array($onBeforeFormSaveResults)) {
			foreach ($onBeforeFormSaveResults as $onBeforeFormSaveResult) {
				if ($onBeforeFormSaveResult === false) {
					return $this->getErrorRedirect($url, $visform->id);
				}
			}
		}
		//save data to db
		try {
			$model->saveData();
		}
		catch (RuntimeException $e) {
			$message = $e->getMessage();
			if (empty($message)) {
				$fields = $model->reloadFields();
			}
			// we get a custom error message set by visforms
			array_push($visform->errors, $e->getMessage());
			// Show form again, keep values already typed in
			return $this->getErrorRedirect($url, $visform->id);
		}

		// trigger after save event
		$app->triggerEvent('onVisformsAfterFormSave', array('com_visforms.form', $visform, $fields));
		// trigger before success action event, allow to override properties in $visforms
		$app->triggerEvent('onVisformsBeforeSuccessAction', array('com_visforms.form', &$visform, $fields));
		//clear user state
		$app->setUserState('com_visforms.' . $visform->context, null);
		$app->setUserState('com_visforms.urlparams.' . $visform->context, null);

		$msg = JText::_('COM_VISFORMS_FORM_SEND_SUCCESS');
		// redirect to specific url no message!
		//get potential custom redirect urls from post
		$rawPlgRedirectUrl = $this->input->post->get('redirecturl', null, 'cmd');
		$plgRedirectUrl = isset($rawPlgRedirectUrl) ? JHTMLVisforms::base64_url_decode($rawPlgRedirectUrl) : '';
		if (!empty($visform->allow_content_plugin_custom_redirect) && !empty($plgRedirectUrl)) {
			$visform->redirecturl = $plgRedirectUrl;
		}
		if (!empty($visform->redirecturl)) {
			$tmpUrl = new JUri($visform->redirecturl);
			$query = $tmpUrl->getQuery(true);
			$urlParams = $model->getRedirectParams($fields, $query, $visform->context);
			if (!empty($urlParams)) {

				$tmpUrl->setQuery($urlParams);
				$visform->redirecturl = $tmpUrl->toString();
			}
			$this->setRedirect(JRoute::_($visform->redirecturl, false));
			return true;
			//no redirect to specific url, a result message is displayed somewhere
		} 
		else {
			$msg = $this->createMessageText($visform, $url);
			if (!empty($visform->redirect_to_previous_page)) {
				if (empty($visform->message_position)) {
					//Joomla! message does not trigger content plugin. So we do it here, although the result may no be completely ok.
					// content plugins which need to add custom css or javascript to page cannot be used in this case!
					$msg = JHtml::_('content.prepare', $msg);
					$app->enqueueMessage($msg);
				} 
				else {
					$app->setUserState('com_visforms.messages.' . $visform->context, $msg);
				}
				if (!empty($url)) {
					$this->setRedirect(JRoute::_($url, false));
				} 
				else {
					$this->setRedirect(JRoute::_(JURI::base(), false));
				}
				return true;
			} 
			else {
				// no textresult or no menuitem for form
				$correspondingFormMenuItem = $model->checkFormViewMenuItemExists($visform->id);
				if (empty($visform->textresult) || empty($correspondingFormMenuItem)) {
					$app->enqueueMessage($msg);
					$this->setRedirect(JRoute::_(JURI::base(), false));
					return true;
				} 
				else {
					//context must be context of visforms view via menu item!
					$context = 'form' . $visform->id;
					$app->setUserState('com_visforms.messages.' . $context, $msg);
					if ($tmpl = $this->input->get('tmpl', null, 'cmd')) {
						$tmpl = "&tmpl=" . $tmpl;
					}
					$this->setRedirect(JRoute::_('index.php?option=com_visforms&view=visforms&layout=message&id=' . $visform->id . '&Itemid=' . $correspondingFormMenuItem . $tmpl, false));
					return true;
				}
			}
		}
	}

	protected function getErrorRedirect($url = '', $formid = 0) {
		if ($url != '') {
			$this->setRedirect(JRoute::_($url, false));
		} 
		else {
			if (!empty($formid)) {
				JFactory::getApplication()->setUserState('vis_send_once' . $formid, '1');
			}
			$this->display();
		}
		return false;
	}

	//the display state is use in the field.php function setQueryValue in order to decide if url params from a get request should be stored in the session
	//url params (from get) are only stored if $displayStateIsNew
	protected function setDisplayState($visform) {
		if (isset($visform->displayState) && $visform->displayState === VisformsModelVisforms::$displayStateIsNew) {
			$visform->displayState = VisformsModelVisforms::$displayStateIsRedisplay;
			JFactory::getApplication()->setUserState('com_visforms.' . $visform->context, $visform);
		}
	}

	protected function createMessageText($visform, $returnUrl) {
		$returnLink = (empty($visform->redirect_to_previous_page)) ? $this->createReturnLinkHtml($visform, $returnUrl) : '';
		if (empty($visform->textresult)) {
			return JText::_('COM_VISFORMS_FORM_SEND_SUCCESS') . $returnLink;
		}
		$message = JHtmlVisforms::replacePlaceholder($visform, $visform->textresult);
		return $message . $returnLink;
	}

	protected function createReturnLinkHtml($visform, $returnUrl) {
		if (empty($returnUrl)) {
			return '';
		}
		$showReturnLink = (!empty($visform->textresult_previouspage_link)) ? $visform->textresult_previouspage_link : 0;
		if (empty($showReturnLink)) {
			return '';
		}
		$linkText = (!empty($visform->return_link_text)) ? $visform->return_link_text : JText::_('COM_VISFORMS_RETURN_TO_PREVIOUS_PAGE_LINK_TEXT');
		return '<p><a href="' . JRoute::_($returnUrl) . '" title="' . $linkText . '">' . $linkText . '</a></p>';
	}
}

?>
