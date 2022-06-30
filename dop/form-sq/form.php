


<div    id="sqformwrapper" style="border:0px solid brown; display:none"  >
 <div  class="sqform"    >
  <div class="sq_top">
  <span  class="sq_title"  > Заказ обратного звонка </span>
  <div id="otm" style="text-decoration:none"   >x</div>
  </div>

 <form action="/dop/form-sq/send.php"  method="post"  >

<table>
  <tr>
    <td class="tdlabel" >    <label>Ваше имя *</label> </td>
    <td class="tdinput" >    <input placeholder="" name="name" type="text" required /> </td>
  </tr>

 <tr>
    <td class="tdlabel">    <label>  Ваш телефон *  </label> </td>
    <td class="tdinput">       <input placeholder="" name="phone"    id="sqphone"  type="text" required />
  </tr>

<!--

  <tr id="tr_email" >
    <td class="tdlabel">    <label> Ваш email  </label> </td>
    <td class="tdinput">     <input placeholder="" name="email"   type="text" />
  </tr>


  <tr  id="tr_text" >
    <td class="tdlabel">    <label> Сообщение </label> </td>
    <td class="tdinput">     <textarea name="text"    > </textarea>
  </tr>

   -->

   <tr>
    <td class="tdlabel">     </td>
    <td class="tdinput">   <input  name="captcha" id="sqcaptcha"   type="hidden"   value="222" >
  </tr>

</table>
          <p>  Нажимая кнопку ОТПРАВИТЬ, подтверждаю</p>
<p>  согласие на обработку персональных данных</p>

         <p><input value="Отправить" type="submit" class="submit1"  ></p>
</form>


  </div>        <!--   sqform   -->
</div>        <!--   sqformwrapper   -->


<link rel='stylesheet' href='/dop/form-sq/style.css'>





<?php
   if  ($mob=="1") : ?>
                 <link rel='stylesheet' href='/dop/form-sq/style-small.css'>
             <?php else: ?>
<?php endif; ?>



<!--   for android   -->

<?php
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
   if(stripos($ua,'android') !== false) : ?>
                 <link rel='stylesheet' href='/dop/form-sq/android.css'>
             <?php else: ?>
<?php endif; ?>
<!--   for android   -->








<script>
jQuery(function($) {


var captchaRight="OWucmb42X12NG";
var sqcaptcha = document.getElementById("sqcaptcha");
var sqphone =   document.getElementById("sqphone");




$("#sqknopka1").click(function(){
              document.getElementById("sqformwrapper").style.display="block";
 });






        $("#otm").click(function(){
             //alert ('2222');
              document.getElementById("sqformwrapper").style.display="none";
        });



sqphone.onfocus = function() {
      sqcaptcha.value = captchaRight;
  }



});  //jQuery


</script>








