<?php defined('_JEXEC') or die('Restricted access'); 
$view_qtyminus=$this->params->get('view_qtyminus','');
$view_qtyplus=$this->params->get('view_qtyplus','');
$ident_qty_prod_minus= $this->params->get('ident_qty_prod_minus', '_tmp_qty_unit');
$ident_qty_prod_plus= $this->params->get('ident_qty_prod_plus', '_tmp_qty_unit');
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
$view->$ident_qty_prod_minus .=
'<div class="input-append count_block">
<button type="button" class="btn list-btn count p_m" 
onclick = "
var qty_el = document.getElementById(\'quantity\');
var qty = qty_el.value;
if( !isNaN( qty ) && qty > 1) qty_el.value--;reloadPrices();return false;">
'.$qtyminus.'
</button>
';
$view->$ident_qty_prod_plus .=
'<button type="button" class="btn list-btn count p_p" 
onclick = "
var qty_el = document.getElementById(\'quantity\');
var qty = qty_el.value;
if( !isNaN( qty )) qty_el.value++;reloadPrices();return false;">
'.$qtyplus.'
</button>
</div>
';
?>