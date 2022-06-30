<?php defined('_JEXEC') or die('Restricted access');
$view_qtyminus=$this->params->get('view_qtyminus','');
$view_qtyplus=$this->params->get('view_qtyplus','');
if (!$view_qtyminus) {
	$qtyminus="-";
} else {
	$qtyminus='<i class="'.$view_qtyminus.'"></i>';
}
if (!$view_qtyplus) {
	$qtyplus="+";
} else {
	$qtyplus='<i class="'.$view_qtyplus.'"></i>';
}

foreach($view->products as $key => $product){
			$view->products[$key]['_qty_unit'] =
			'<div class="input-append count_block">
			<button type="button" class="btn list-btn count p_m" onclick = "
			var qty_el = document.getElementsByName(\'quantity['.$key.']\');
			for ( keyVar in qty_el) {
			if( !isNaN( qty_el[keyVar].value ) && qty_el[keyVar].value > 1) qty_el[keyVar].value--;
			}return false;">
			'.$qtyminus.'
			</button>
			<button type="button" class="btn list-btn count p_p" onclick = "
			var qty_el = document.getElementsByName(\'quantity['.$key.']\');
			for ( keyVar in qty_el) {
			if( !isNaN( qty_el[keyVar].value )) qty_el[keyVar].value++;
			}return false;">
			'.$qtyplus.'
				</button>
			</div>';
		}
?>