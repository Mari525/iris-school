<?php
/**
 * @author       Aicha Vack
 * @package      Joomla.Site
 * @subpackage   com_visforms
 * @link         http://www.vi-solutions.de
 * @license      GNU General Public License version 2 or later; see license.txt
 * @copyright    2019 vi-solutions
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class visFormCsvHelper
{
	protected $separator;
	protected $input_encoding;
	protected $output_encoding;
	protected $useWindowsCharacterSet;

	public function __construct($params) {
		if ((!function_exists('iconv')) || (isset($params->usewindowscharset) && ($params->usewindowscharset == 0))) {
			$this->useWindowsCharacterSet = false;
		}
		else {
			$this->useWindowsCharacterSet = true;
		}
		$this->output_encoding = "windows-1250//TRANSLIT";
		$this->input_encoding = "UTF-8";
		$this->separator = (isset($params->expseparator)) ? $params->expseparator : ";";
	}

	public function convertCharacterSet($text) {
		if (!$this->useWindowsCharacterSet) {
			return $text;
		}
		// convert characters into window characterset for easier use with excel
		return iconv($this->input_encoding, $this->output_encoding, $text);
	}
}