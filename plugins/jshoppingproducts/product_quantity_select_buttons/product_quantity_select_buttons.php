<?php
/**
* @version      0.0.1 21.12.2016
* @author       Brooksus
* @package      JoomShopping
* @copyright    Copyright (C) 2016 Brooksite.ru. All rights reserved.
* @license      2016. Brooksite.ru (http://brooksite.ru/litsenzionnoe-soglashenie.html).
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php
jimport('joomla.application.component.controller');

class plgJshoppingProductsProduct_quantity_select_buttons extends JPlugin{

	function onBeforeDisplayProductListView(&$view){
		$include_to_list=$this->params->get('include_to_list',1);
		if ($include_to_list!="0"){
			$include_css=$this->params->get('include_css',1);
			$doc=JFactory::getDocument();
			if ($include_css!="0"){
				$doc->addStyleSheet(JURI::base().'plugins/jshoppingproducts/product_quantity_select_buttons/style.css');
			}
			include_once dirname(__FILE__) . '/helper_count_list.php';
		}
	}
	
	function onBeforeDisplayProductView(&$view){
		$include_to_prod=$this->params->get('include_to_prod',1);
		if ($include_to_prod!="0"){
			$include_css=$this->params->get('include_css',1);
			$doc=JFactory::getDocument();
			if ($include_css!="0"){
				$doc->addStyleSheet(JURI::base().'plugins/jshoppingproducts/product_quantity_select_buttons/style.css');
			}
			include_once dirname(__FILE__) . '/helper_count.php';
		}
	}
	
	function onBeforeDisplayCartView(&$view){
		$include_to_cart=$this->params->get('include_to_cart',1);
		if ($include_to_cart!="0"){
			$include_css=$this->params->get('include_css',1);
			$doc=JFactory::getDocument();
			if ($include_css!="0"){
				$doc->addStyleSheet(JURI::base().'plugins/jshoppingproducts/product_quantity_select_buttons/style.css');
			}
			include_once dirname(__FILE__) . '/helper_count_cart.php';
		}
	}
}
?>