<?php 
/**
 * jSecure Authentication component for Joomla!
 * jSecure Authentication extention prevents access to administration (back end)
 * login page without appropriate access key. 
 *
 * @author      $Author: Ajay Lulia $
 * @copyright   Joomla Service Provider - 2016
 * @package     jSecure3.5
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id: view.html.php  $
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
$configFile	 = $basepath.'/params.php';
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base()."components/com_jsecure/css/styles.css");
require_once($configFile);
$app        = JFactory::getApplication();
$JSecureConfig = new JSecureConfig();
?>

<script type="text/javascript" src="https://www.google.com/jsapi">
</script>

<?php 
if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_graph == '1' )
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));
$link = "index.php?option=com_jsecure";
$app->redirect($link);	

}
else{
$document = & JFactory::getDocument();
$date_week = date ( "Y-m-d", strtotime ( '-6 day') ); 
$db = JFactory::getDbO();
$qry1 =  "SELECT `date`,`incorrect_hits`,`correct_hits`  FROM `#__jsecure_hits`  where `date` >= '$date_week' GROUP BY `date` order by date desc ";
$db->setQuery($qry1);
$db->query();
$num_rows1 = $db->getNumRows();

$results1 = $db->loadObjectList();
$i=0;
foreach($results1 as $items){
   $rd1[$i] = $items ;
   $i++;
   }
  
   if(isset($rd1) == true)
   { 
      echo "<SCRIPT type='text/javascript' >";
        echo " function drawChart() {
	  var data = google.visualization.arrayToDataTable([	  ['Date', 'Correct Hits','Wrong Hits']" ;
      for($i=0; $i<count($rd1); $i++)
       {
          $wh=$rd1[$i]->incorrect_hits;
		  $ch=$rd1[$i]->correct_hits;
		  $d=$rd1[$i]->date;
          echo ",[ '$d' , $ch , $wh]";
       }
      echo "]);
	  w = document.documentElement.clientWidth;
      h = document.documentElement.clientHeight;
      var nw = (0.83 * w);
      var nh = (0.65 *h);
	  var options = {
	  align: 'center',
          title: 'Daily Graph',
		  vAxis: {title: 'Hits',  titleTextStyle: {color: 'red'}},
          hAxis: {title: 'Last Seven Days',  titleTextStyle: {color: 'red'}},
		  'width':nw,
         'height':nh,
		 isHtml:true
        };
		 new google.visualization.ColumnChart(document.getElementById('chartdiv1')).
          draw(data, options);
      }
      

      google.setOnLoadCallback(drawChart);

		</SCRIPT>";
  }
?>


<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" id="adminForm" class="span10">
<!-- start for today -->
 
    <script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});  //atleast required once to load google chart packages
		  //calling chart function to build the graph
	</script>
	  <div id="infodiv1"><?php echo JText::_('DAILY_DESC'); ?></div>
	  <div id="chartdiv1">
	  	<?php if($num_rows1 < 1)
		{
				echo '<div class="notification">'.JText::_("NO_HITS").'</div>';
		}
		?>
	  </div> <!--the daily graph container-->

<!-- end for today -->

<!-- start for for weekly  -->
<?php
$date_week = date("Y-m-d",strtotime("-1 week"));
$cur_month = date('m');
$cur_year = date('Y');
$qry2 = "SELECT date,SUM(incorrect_hits) as incorrect_hits,SUM(correct_hits) as correct_hits,
            DATE_ADD(date, INTERVAL(1-DAYOFWEEK(date)) DAY) as startdate,
            DATE_ADD(date, INTERVAL(7-DAYOFWEEK(date)) DAY) as enddate
            FROM `#__jsecure_hits`  
			WHERE MONTH(date) = '$cur_month' AND YEAR(date) = '$cur_year' 
			GROUP BY WEEK(date) "; 
$db->setQuery($qry2);
$db->query();
$num_rows2 = $db->getNumRows();

$results2 = $db->loadObjectList();
$i=0;
foreach($results2 as $items){
   $rd2[$i] = $items ;
   $i++;
   }
   if(isset($rd2) == true)
   { 
      $first_day_this_month = date('Y-m-01'); 
      $last_day_this_month = date('Y-m-t');
      echo "<SCRIPT type='text/javascript' >";
      echo " function drawChart2() {
	  var data2 = google.visualization.arrayToDataTable([	  ['Date', 'Correct Hits','Wrong Hits']" ;
	  for($i=0;$i<count($rd2);$i++)
       {
          $wh=$rd2[$i]->incorrect_hits;
		  $ch=$rd2[$i]->correct_hits;
		  $d = $rd2[$i]->date;
		  $ldate=$rd2[$i]->enddate;
		  $sdate=$rd2[$i]->startdate;

		  $l=date('d',strtotime($ldate));
		  $s=date('d',strtotime($sdate));

		  if($first_day_this_month>$sdate)
		   {
             $sdate = $first_day_this_month;
		   }
          	   
	
	   echo ",[ 'Week:".($i+1).'\n'."-  From: ".$s.'\n To: '.$l."' , $ch , $wh ]";
	   }
	   $vp='Weekly Graph for '.date('M-Y');
      echo "]);
	  w = document.documentElement.clientWidth;
      h = document.documentElement.clientHeight;
      var nw = (0.83 * w);
      var nh = (0.65 *h);
	     var options2 = {
          title: '$vp',
		  vAxis: {title: 'Hits',  titleTextStyle: {color: 'red'}},
          hAxis: {title: 'Weeks this Month',  titleTextStyle: {color: 'red'}},
		  'width':nw,
          'height':nh
        };
        var chart2 = new google.visualization.ColumnChart(document.getElementById('chartdiv2'));
        chart2.draw(data2, options2); }
		google.setOnLoadCallback(drawChart2);
		</SCRIPT>";     //options2 to set chart properties eg: isStacked to adjust the labels to multiline
  }
?>
    <div id="infodiv2"><?php echo JText::_('WEEKLY_DESC'); ?></div>
    <div id="chartdiv2">
		<?php if($num_rows2 < 1)
		{
				echo '<div class="notification">'.JText::_("NO_HITS").'</div>';
		}
		?>
	</div>  <!-- the weekly graph container-->

<!-- end for for weekly  -->

<!-- start for for monthly -->
<?php
$qry3 =  "SELECT `date`,SUM(incorrect_hits) as incorrect_hits, SUM(correct_hits) as correct_hits  
            FROM `#__jsecure_hits` 
			GROUP BY MONTH(date),YEAR(date) 
			ORDER BY date desc LIMIT 0,12";
$db->setQuery($qry3);
$db->query();
$num_rows3 = $db->getNumRows();

$results3 = $db->loadObjectList();
$i=0;
foreach($results3 as $items){
   $rd3[$i] = $items ;
   $i++;
   }
   if(isset($rd3) == true)
   { 
      echo "<SCRIPT type='text/javascript' >";
      echo " function drawChart3() {
	  var data3 = google.visualization.arrayToDataTable([	  ['Date', 'Correct Hits','Wrong Hits']" ;
	  for($i=0; $i<count($rd3); $i++)
       {
          $wh=$rd3[$i]->incorrect_hits;
		  $ch=$rd3[$i]->correct_hits;
		  $d=$rd3[$i]->date;
          $month = date('M',strtotime($d));
		  $year = date('Y',strtotime($d));
		  $dat = $year.'-'.$month;
          echo ",[  '$dat' , $ch , $wh]";
       }
      echo "]);
	  w = document.documentElement.clientWidth;
      h = document.documentElement.clientHeight;
      var nw = (0.83 * w);
      var nh = (0.65 *h);
	  var options3 = {
          title: 'Monthly Graph',
		  vAxis: {title: 'Hits',  titleTextStyle: {color: 'red'}},
          hAxis: {title: 'Last Twelve Months',  titleTextStyle: {color: 'red'}},
		  'width':nw,
          'height':nh,
		  isHtml:true
		};

        var chart3 = new google.visualization.ColumnChart(document.getElementById('chartdiv3'));
        chart3.draw(data3, options3); }
		google.setOnLoadCallback(drawChart3);
		</SCRIPT>";      // chartName eg:chart3 to set the graph container
  }
  else
  {
  }
?>
    <div id="infodiv3"><?php echo JText::_('MONTHLY_DESC'); ?></div>
	<div id="chartdiv3">
		<?php if($num_rows3 < 1)
		{
				echo '<div class="notification">'.JText::_("NO_HITS").'</div>';
		}
		?>
	</div>   <!-- the monthly graph container-->
		 

<input type="hidden" name="option" value="com_jsecure" />
<input type="hidden" name="task" value="" />
<div id="button">
   Records In Time Period :
         <button class="btn btn-small" name="btdaily" vlaue="Daily" onclick="showDaily();">Daily</button>
		 <button class="btn btn-small" name="btweekly" vlaue="Weekly" onclick="showWeekly();">Weekly</button>
		 <button class="btn btn-small" name="btmonthly" vlaue="Monthly" onclick="showMonthly();">Monthly</button>
   </div>
 </form>
 
<!-- display button -->

   <script type="text/javascript">
   function showDaily()
		  {
		    document.getElementById("chartdiv1").style.display="block";
			document.getElementById("chartdiv2").style.display="none";
			document.getElementById("chartdiv3").style.display="none";
			document.getElementById("infodiv1").style.display="block";
			document.getElementById("infodiv2").style.display="none";
			document.getElementById("infodiv3").style.display="none";
		  }
		  function showWeekly()
		  {
		    document.getElementById("chartdiv1").style.display="none";
			document.getElementById("chartdiv2").style.display="block";
			document.getElementById("chartdiv3").style.display="none";
			document.getElementById("infodiv1").style.display="none";
			document.getElementById("infodiv2").style.display="block";
			document.getElementById("infodiv3").style.display="none";
		  }
		  function showMonthly()
		  {
		    document.getElementById("chartdiv1").style.display="none";
			document.getElementById("chartdiv2").style.display="none";
			document.getElementById("chartdiv3").style.display="block";
			document.getElementById("infodiv1").style.display="none";
			document.getElementById("infodiv2").style.display="none";
			document.getElementById("infodiv3").style.display="block";
		  }		  
 showMonthly();
 </script>
<?php } ?>
