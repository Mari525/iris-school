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
$ident_qty_list= $this->params->get('ident_qty_list', '_tmp_var_buttons');
$script = '
function reloadPriceInList(product_id, qty){
	var data = {};
	data["change_attr"] = 0;
	data["qty"] = qty;
	if (prevAjaxHandler){
		prevAjaxHandler.abort();
	}
	prevAjaxHandler = jQuery.getJSON(
	"'.Juri::base().'index.php?option=com_jshopping&controller=product&task=ajax_attrib_select_and_price&product_id=" + product_id + "&ajax=1&fid=afl",
	data,
	function(json){
		jQuery(".product.productitem_"+product_id+" .jshop_price span").html(json.price);
	}
	);
}';
$doc->addScriptDeclaration($script);
		foreach($view->rows as $key => $product){
			if($view->rows[$key]->buy_link){
				$view->rows[$key]->$ident_qty_list .= '
				<div class="input-append count_block">
				<button type="button" class="btn list-btn count p_m" 
				onclick = "
				var qty_el = document.getElementById(\'quantity'.$product->product_id.'\');
				var qty = qty_el.value;
				if( !isNaN( qty ) && qty > 1) qty_el.value--;
				var url_el = document.getElementById(\'productlink'.$product->product_id.'\');
				url_el.href=\''.$view->rows[$key]->buy_link.'&quantity=\'+qty_el.value;reloadPriceInList('.$product->product_id.',qty_el.value);return false;">
				'.$qtyminus.'
				</button>
				
				<input type = "text" name = "quantity'.$product->product_id.'" id = "quantity'.$product->product_id.'"
				class = "btn list-btn quantity inputbox" value = "1" onkeyup="
				var qty_el = document.getElementById(\'quantity'.$product->product_id.'\');
				var url_el = document.getElementById(\'productlink'.$product->product_id.'\');
				url_el.href=\''.$view->rows[$key]->buy_link.'&quantity=\'+qty_el.value;reloadPriceInList('.$product->product_id.',qty_el.value);return false;" />
				<button type="button" class="btn list-btn count p_p" 
				onclick = "
				var qty_el = document.getElementById(\'quantity'.$product->product_id.'\');
				var qty = qty_el.value;
				if( !isNaN( qty )) qty_el.value++;
				var url_el = document.getElementById(\'productlink'.$product->product_id.'\');
				url_el.href=\''.$view->rows[$key]->buy_link.'&quantity=\'+qty_el.value;reloadPriceInList('.$product->product_id.',qty_el.value);return false;">
				'.$qtyplus.'
				</button>
				</div>';
				$view->rows[$key]->buy_link .= "\" Id = \"productlink".$product->product_id;
			}
		}
?>