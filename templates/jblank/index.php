<?php

defined('_JEXEC') or die;


// init $tpl helper
require dirname(__FILE__) . '/php/init.php';

?><?php echo $tpl->renderHTML(); ?>
<head>


     <script src="/dop/jquery-3.2.1.min.js"  ></script>

    <jdoc:include type="head" />



       <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <link rel="stylesheet"  href="/user/user.css"  />
     <link rel="stylesheet"  href="/user/user_.css"  />
     <link rel="stylesheet"  href="/user/shop.css"  />






<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">




    <meta name="viewport" content="width=1150">


<?php

$app  = JFactory::getApplication(); $sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

  include_once 'dop/device/Mobile_Detect.php';
$detect = new Mobile_Detect;
$mob=0;

// both planshet, phone
if ( $detect->isMobile() ) {   }

// planshet only
if( $detect->isTablet() )
{   }

// phone only
if( $detect->isMobile() && !$detect->isTablet() )    {   $mob=1; }

//include_once 'dop/mobredir.php';

if( $mob==1 )
{
  echo  ' <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
          <link rel="stylesheet" type="text/css" media="only screen" href="/user_small/small.css" />
          <link rel="stylesheet" type="text/css" media="only screen" href="/user_small/small_shop.css" />
         ';
}




?>

<!--   for android   -->
<?php
 $ua = strtolower($_SERVER['HTTP_USER_AGENT']); if(stripos($ua,'android') !== false)
 { echo  ' <link rel="stylesheet" type="text/css" media="only screen" href="/user_small/android.css" />  '; }
?>
<!--   for android   -->



 <!--  highslide  -->
<?php
  if   ( ($mob==20) AND (isset($_REQUEST["category_id"])) )  {   include_once 'dop/highslide.php';}
   ?>
<!--  highslide  -->





</head>
<body >

<?php
     if  ( $mob=="1") { include_once 'user_small/head_mob.php';  }
     else             { include_once 'user/head.php';  }
?>








<!--   menu   -->
  <div class="wrapper menu" >
       <div class="center" >

        </div>    <!--  center    -->
   </div>         <!--   wrapper   -->
<!--   menu   -->


<!--   banner   -->

  <?php
             if  ($mob=="0") : ?>
              <div class="wrapper banner" id="banner" >
                  <div class="center" style="position:relative; top:0" >
                      <jdoc:include type="modules" name="slider-banner" style="xhtml"  />
                            <div style="position:absolute; top:130px; left:90px;  width:650px;  border:0px solid brown">
                                 <jdoc:include type="modules" name="banner" style="xhtml"  />
                            </div>
                  </div>    <!--  center    -->
              </div>         <!--   wrapper   -->

             <?php else: ?>
             <div class="wrapper banner" id="banner" >
                 <div class="center"  >
                      <jdoc:include type="modules" name="banner" style="xhtml"  />
                </div>
               </div>
             <?php endif; ?>




<!--   banner   -->



<!--   rukov   -->
  <div class="wrapper author" id="rukov"  >
       <div class="center"  >
             <jdoc:include type="modules" name="pre_component"   style="xhtml"  />

<?php  $Itemid=$_REQUEST["Itemid"];  if  ($Itemid=="101") {   echo ('        <img src="/images/rukov.jpg" />   ') ;    }
                      ?>

        </div>    <!--  center    -->
   </div>         <!--   wrapper   -->
<!--   rukov   -->



    <div class="wrapper preim" >
       <div class="center" >

                            <jdoc:include type="modules" name="preim" style="xhtml"  />
        </div>    <!--  center    -->
   </div>         <!--   wrapper   -->





<!--   banner2   -->
  <div class="wrapper author" id="banner2" >
       <div class="center" style="position:relative; top:0px; left:0px " >
             <jdoc:include type="modules" name="banner2"   style="xhtml"  />
              <?php   $Itemid=$_REQUEST["Itemid"];
              if  ($Itemid=="101") { include_once 'dop/primechanie.txt'; }
              ?>

        </div>    <!--  center    -->
   </div>         <!--   wrapper   -->
<!--   banner2   -->







<div class="wrapper middletable" style="position:relative; top:0px; left:0px "    >
    <div class="middletable2" style="position:relative; top:0px; left:0px "    >
        <div id="center" class="center"   >

            <?php  $Itemid=$_REQUEST["Itemid"];
             if  ($Itemid<>"101") : ?>
                    <jdoc:include type="modules" name="breadcrumbs" />
             <?php else: ?>
             <?php endif; ?>


<?php
    // test Joomla messages
    //$tpl->app->enqueueMessage('Notice message, example', 'notice');
   // $tpl->app->enqueueMessage('Warning message, example', 'warning');
   // $tpl->app->enqueueMessage('Error message, example', 'error');
   // $tpl->app->enqueueMessage('Simple message, example');
    //throw new Exception('Fatal error message example');
?>




    <div class="component-wrapper">
        <?php if ($tpl->isError()) : ?>
            <jdoc:include type="message" />
        <?php endif; ?>
        <jdoc:include type="component" />
    </div>     <!--   component-wrapper   -->


                   <jdoc:include type="modules" name="post_component"  style="xhtml"  />



                   </div> <!--    #center    -->
    </div> <!--    middletable2    -->
</div> <!--    middletable    -->



<!--   slider   -->
<div class="wrapper" >
       <div class="center" >
                     <jdoc:include type="modules" name="slider"   style="xhtml" />
        </div>    <!--  center    -->
   </div>         <!--   wrapper   -->
<!--   slider    -->



 <!--   otzivi   -->
<div class="wrapper" >
       <div class="center" >
                     <jdoc:include type="modules" name="otzivi"   style="xhtml" />
        </div>    <!--  center    -->
   </div>         <!--   wrapper   -->
<!--   otzivi    -->



 <!--   events   -->
<div class="wrapper" >
       <div class="center" >
                     <jdoc:include type="modules" name="events"   style="xhtml" />
        </div>    <!--  center    -->
   </div>         <!--   wrapper   -->
<!--   events    -->





  <div class="wrapper under" >
       <div class="center" >
          <jdoc:include type="modules" name="mainmenu"  style="xhtml"   />

                            <jdoc:include type="modules" name="under" style="xhtml"  />
        </div>    <!--  center    -->
   </div>         <!--   wrapper   -->





<!--  float label   -->
<?php
     include_once 'dop/floatlabel.php';
?>
<!--  float label   -->




<?php
       include_once 'dop/form-sq/form.php';
?>

<!--  cartform   -->
<?php
     if (isset($_REQUEST["option"]))
        {   $option=$_REQUEST["option"];  if  ( $option=="com_jshopping"  )  { include_once 'dop/cartform.php'; }   }
?>
<!--  cartform   -->

  <?php       // include_once 'dop/script.php';
               include_once 'dop/counters.txt';
  ?>


   <?php if ($tpl->isDebug()) : ?>
        <jdoc:include type="modules" name="debug" />
    <?php endif; ?>




      <?php  $Itemid=$_REQUEST["Itemid"];
             if  ($Itemid=="101") : ?>
                  <style>
                  div.middletable { width:100% ;  background-color-:#f8f598; }
div.middletable  {  background-image:url('/images/11.png'); background-position:0% 50%; background-repeat:no-repeat; }
div.middletable2  {  background-image:url('/images/10.png'); background-position:100% 50%; background-repeat:no-repeat; }

                   </style>
             <?php else: ?>
             <?php endif; ?>




</body>
</html>
