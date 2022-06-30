

<?php
     if  ( $mob==1) : ?>

      <div id="floatlabel" style="position:fixed; bottom:20px; right:0"  >
                                  <!--
                                  <div class="letter" data-modal="modal-1" style="background-color:yellow" > <img src="/images/letter1.png" alt="cart"   />  </div>
                                    -->

                       <div> <a href="tel:+79037142271" title="phone"  > <img src="/images/letter2.png" alt="phone"   /> </a> </div>

                       <div> <a href="https://wa.me/79037142271"  title="whatsapp"  > <img src="/images/whatsapp.png" alt="whatsapp"   /> </a> </div>



       </div>     <!--  floatlabel    -->


     <?php else: ?>


           <div id="floatlabel" style="position:fixed; bottom:20px; right:0"  >

                    <!--
                     <div class="letter"   > <img src="/images/letter1.png" alt="Отправить письмо" title="Отправить письмо"   />  </div>

                           <div> <a href="tel:+74952222222" title="Позвонить"  > <img src="/images/letter2.png" alt="phone"   /> </a> </div>

                                                   <div> <a href="https://wa.me/79162222222"  title="whatsapp"  > <img src="/images/whatsapp.png" alt="whatsapp"   /> </a> </div>
                          -->



           </div>     <!--  floatlabel    -->


<?php endif; ?>



<?php
 // Any tablet device.
if( $detect->isTablet() )
 { echo("
 <style> #floatlabel img {width:100px} </style>
 "); }

// Exclude tablets.
if( $detect->isMobile() && !$detect->isTablet() )
{  }


?>
