<div id = "jshop_module_cart">
<table  >
<tr>

  <td  style=" vertical-align:top;text-align:left;  " >

   <a href = "<?php print SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1)?>">
     <img src="images/cart.png" alt="cart" title="cart"   />
  </a>
 </td>

    <td>
      <span id = "jshop_quantity_products"><?php print $cart->count_product?></span>&nbsp;<?php print JText::_('PRODUCTS')?>
    -
      <span id = "jshop_summ_product"><?php print formatprice($cart->getSum(0,1))?></span>

      <br/>
       <a href = "<?php print SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1)?>">
       <?php print JText::_('GO_TO_CART')?>
       </a>
    </td>
</tr>



</table>
</div>



