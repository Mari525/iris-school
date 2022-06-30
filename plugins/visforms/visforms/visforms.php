<?php
/**
 * @copyright      Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class plgVisformsVisforms extends JPlugin
{
	public function onVisformsBeforeJFormDelete($context, $data) {

		// Skip plugin if we are deleting something other than a visforms form or field
		if (($context != 'com_visforms.visfield') && ($context != 'com_visforms.visform')) {
			return true;
		}

		if ($context == 'com_visforms.visfield') {
			$success = true;
			$fid = $data->fid;
			$id = $data->id;

			// Convert the defaultvalues field to an array.
			$registry = new JRegistry;
			$registry->loadString($data->defaultvalue);
			$defaultvalues = $registry->toArray();

			//Remove restrtictions
			//getRestricts
			if ($restricts = VisformsConditionsHelper::setRestricts($data->id, $defaultvalues, $data->name, $fid)) {
				//remove Restrictions
				try {
					VisformsConditionsHelper::removeRestriction($restricts);
				}
				catch (RuntimeException $e) {
					JFactory::getApplication()->enqueueMessage($e->getMessage, 'error');
					return false;
				}
			}

			$db = JFactory::getDbo();
			$tablesAllowed = $db->getTableList();
			if (!empty($tablesAllowed)) {
				$tablesAllowed = array_map('strtolower', $tablesAllowed);
			}
			$tablesToDeleteFrom = array("visforms_" . $fid, "visforms_" . $fid . "_save");
			foreach ($tablesToDeleteFrom as $tn) {
				$tnfull = strtolower($db->getPrefix() . $tn);

				//Delete field in data table when deleting a field
				if (in_array($tnfull, $tablesAllowed)) {

					$tableFields = $db->getTableColumns('#__' . $tn, false);
					$fieldname = "F" . $id;

					if (isset($tableFields[$fieldname])) {

						$query = "ALTER TABLE #__" . $tn . " DROP " . $fieldname;
						$db->setQuery($query);
						try {
							$db->execute();
						}
						catch (RuntimeException $e) {
							JFactory::getApplication()->enqueueMessage($e->getMessage, 'error');
							$success = false;
							continue;
						}
					}
				}
			}

			if (!$success) {
				//set already deleted restrictions again
				VisformsConditionsHelper::setRestriction($restricts);

			}
			return $success;
		}

		//Delete fields in visfields table when deleting a form and delete datatable if table exists
		if ($context == 'com_visforms.visform') {
			$success = true;
			$fid = $data->id;
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__visfields'))
				->where($db->quoteName('fid') . " = " . $fid);

			$db->setQuery($query);
			try {
				$db->execute();
			}
			catch (RuntimeException $e) {
				JFactory::getApplication()->enqueueMessage($e->getMessage, 'error');
				$success = false;
			}
			$tablesToDelete = array("visforms_" . $fid, "visforms_" . $fid . "_save");
			foreach ($tablesToDelete as $tn) {
				$db->setQuery("drop table if exists #__" . $tn);
				try {
					$db->execute();
				}
				catch (RuntimeException $e) {
					JFactory::getApplication()->enqueueMessage($e->getMessage, 'error');
					$success = false;
				}
			}
		}
		return $success;
	}
}
