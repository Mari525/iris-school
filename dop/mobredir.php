<!--  mob redirect script   -->

<?php




$uri = Joomla\CMS\Uri\Uri::getInstance();
$uri1= $uri->toString();

$raspset=0;
if (isset($_REQUEST["rasp"])) {  $rasp=$_REQUEST["rasp"];  $raspset=1;  }
$pos     = strripos($uri1, "rasp"); if ($pos === false) {  }  else { $raspset=1;  }


//$uri2=$uri1."&rasp=hor" ;
$uri2=$uri1."?rasp=hor" ;

        //  echo $uri1;
        //  echo "<br/>";
         // echo $uri2;


if  (         ($mob=="1")    AND   (  $raspset=="0" )       )
 : ?>

 <!--  for normal mob   -->
<script>
var loc="<?  echo $uri2; ?>";
screen_width = document.documentElement.clientWidth;
screen_heght = document.documentElement.clientHeight;

if (screen_width>screen_heght)
{
//alert('redir');
window.location=loc; }
</script>
<!--  for normal mob   -->

        <?php else: ?>
 <!--  for  notmob, or  mob with hor arg  -->
 <!--  for  notmob, or  mob with hor arg  -->
<?php endif; ?>



<?php
 if  (   $raspset=="1" )  {$mob=12; }
?>

<!--  mob redirect script   -->
