<?php
/**
 * Visform field Visdatasortorder
 *
 * @author       Aicha Vack
 * @package      Joomla.Administrator
 * @subpackage   com_visforms
 * @link         http://www.vi-solutions.de
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2012 vi-solutions
 * @since        Joomla 1.6
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');
require_once JPATH_ADMINISTRATOR . '/components/com_visforms/helpers/visforms.php';

class JFormFieldVisDataSortOrder extends JFormFieldList
{
	protected $type = 'VisDataSortOrder';
	protected $unSortable = array('submit', 'reset', 'image', 'fieldsep', 'hidden', 'signature');

	protected function getOptions() {

		$options = array();
		$options[] = JHtml::_(
			'select.option', '',
			JText::_('COM_VISFORMS_SELECT_SORT_FIELD'), 'value', 'text',
			false
		);
		$options[] = JHtml::_(
			'select.option', 'id',
			JText::_('COM_VISFORMS_ID'), 'value', 'text',
			false
		);
		$options[] = JHtml::_(
			'select.option', 'created',
			JText::_('COM_VISFORMS_SUBMISSIONDATE'), 'value', 'text',
			false
		);
		$id = 0;
		//extract form id
		$form = $this->form;
		$link = $form->getValue('link');
		if (isset($link) && $link != "") {
			$parts = array();
			parse_str($link, $parts);
			if (isset($parts['id']) && is_numeric($parts['id'])) {
				$id = $parts['id'];
			}
		}

		// Create options according to visforms form and fields settings
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('frontendsettings'))
			->from($db->qn('#__visforms'))
			->where($db->qn('id') . ' = ' . $id);
		$db->setQuery($query);
		try {
			$formFrontendSettings = $db->loadResult();

		}
		catch (RuntimeException $e) {
		}
		if (!empty($formFrontendSettings)) {
			$formFrontendSettings = VisformsHelper::registryArrayFromString($formFrontendSettings);
			if (isset($formFrontendSettings['displayip']) && (($formFrontendSettings['displayip'] == "1") || ($formFrontendSettings['displayip'] == "2"))) {
				$options[] = JHtml::_(
					'select.option', 'ipaddress',
					JText::_('COM_VISFORMS_IP'), 'value', 'text',
					false
				);
			}
			if (isset($formFrontendSettings['displayismfd']) && (($formFrontendSettings['displayismfd'] == "1") || ($formFrontendSettings['displayismfd'] == "2"))) {
				$options[] = JHtml::_(
					'select.option', 'ismfd',
					JText::_('COM_VISFORMS_MODIFIED'), 'value', 'text',
					false
				);
			}
			if (isset($formFrontendSettings['displaymodifiedat']) && (($formFrontendSettings['displaymodifiedat'] == "1") || ($formFrontendSettings['displaymodifiedat'] == "2"))) {
				$options[] = JHtml::_(
					'select.option', 'modified',
					JText::_('COM_VISFORMS_MODIFIED_AT'), 'value', 'text',
					false
				);
			}
		}
		$query = $db->getQuery(true);
		$unSortable = implode('","', $this->unSortable);
		// Create options according to visfield settings
		$query->select($db->qn(array('id', 'label')))
			->from($db->qn('#__visfields'))
			->where($db->qn('fid') . '=' . $id)
			->where($db->qn('published') . '=' . 1)
			->where('('. $db->qn('frontdisplay') . 'is null or ' . $db->qn('frontdisplay') . '=' . 1 .' or ' . $db->qn('frontdisplay') . '=' . 2 .')')
			->where('not' . $db->qn('typefield') .  'in ("'.$unSortable.'")');
		$db->setQuery($query);
		try {
			$fields = $db->loadObjectList();
			if ($fields) {
				foreach ($fields as $field) {
					$tmp = JHtml::_(
						'select.option', $field->id,
						$field->label, 'value', 'text',
						false
					);

					// Add the option object to the result set.
					$options[] = $tmp;
				}
			}
		} catch (RuntimeException $e) {
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
