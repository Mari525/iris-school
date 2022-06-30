<?php 
/**
 * jSecure Authentication components for Joomla!
 * jSecure Authentication extention prevents access to administration (back end)
 * login page without appropriate access key.
 *
 * @author      $Author: Ajay Lulia $
 * @copyright   Joomla Service Provider - 2016
 * @package     jSecure3.5
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id: default.php  $
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'helper.php';
$license_key = licenseHelper::getLicenseKey();
$sub_cat_id = licenseHelper::getSubscriptionCatid();
JHtml::_('behavior.framework', true);
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);

$document = JFactory::getDocument();
$document->addScriptDeclaration("window.addEvent('domready', function() {
			$$('.hasTip').each(function(el) {
				var title = el.get('title');
				if (title) {
					var parts = title.split('::', 2);
					el.store('tip:title', parts[0]);
					el.store('tip:text', parts[1]);
				}
			});
			var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
		});
		window.addEvent('domready', function() {

			SqueezeBox.initialize({});
			SqueezeBox.assign($$('a.modal'), {
				parse: 'rel'
			});
		});
		var key = '$license_key';
		var subcatid = '$sub_cat_id';
		window.onload = function(){
		showUpdates();
		showlicense(key,subcatid);
		}
");
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/validate_license.js"></script>');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/auth.js"></script>');
$document->addCustomTag('<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />');
$document->addCustomTag('<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/dashboard.css" />');
$document->addCustomTag('<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/styles.css" />');
$JSecureConfig = $this->JSecureConfig;
?>
<link rel="stylesheet" type="text/css" href="components/com_jsecure/css/modern_jquery.mCustomScrollbar.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="components/com_jsecure/js/modern_jquery.mCustomScrollbar.js"></script>
<script src="components/com_jsecure/js/highcharts.js"></script>
<script src="components/com_jsecure/js/drilldown.js"></script>
<script src="components/com_jsecure/js/data.js"></script>
<script src="components/com_jsecure/js/modules/exporting.js"></script>
<script>
//mscroll
	jQuery(window).load(function(){
//        jQuery('.enabled_plugins .div_enable p').each(function(){
            //if(jQuery(this).text() == 'enable') {
                //jQuery(this).parent().addClass('on');
            //} else {
                //jQuery(this).parent().removeClass('on');
            //}
        //});
		$window_height = jQuery(window).innerHeight();
		$subhead_height = jQuery('.subhead').innerHeight();
		$sidebar_scroll_height = $window_height - $subhead_height - 30;
		jQuery('.task- .container-fluid.container-main .span2, .task-cancel .container-fluid.container-main .span2').css('height',$sidebar_scroll_height);
		
		jQuery(".task- .container-fluid.container-main .span2").mCustomScrollbar({
			autoHideScrollbar:true
		});
		jQuery(".task-cancel .container-fluid.container-main .span2").mCustomScrollbar({
			autoHideScrollbar:true
		});
	});
	
	jQuery(window).resize(function(){
		$window_height = jQuery(window).innerHeight();
		$subhead_height = jQuery('.subhead').innerHeight();
		$sidebar_scroll_height = $window_height - $subhead_height - 30;
		jQuery('.task- .container-fluid.container-main .span2, .task-cancel .container-fluid.container-main .span2').css('height',$sidebar_scroll_height);
	});
	
	jQuery(window).scroll(function () {
		var heisubhead = jQuery('subhead').innerHeight() + 30;
		if(jQuery('.desktopview .subhead').hasClass('subhead-fixed')){
			jQuery('.desktopview .container-fluid.container-main .span2').addClass('fixedsidebar');
			
		} else {
			jQuery('.container-fluid.container-main .span2').removeClass('fixedsidebar');
		}
	});
		
</script>


<script type="text/javascript">
function show_confirm()
{
<?php
if(!($JSecureConfig->enableMasterPassword == '1' and !jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->include_purgesessions == '1'))
{
?>
  if(confirm("<?php echo JText::_('CONFIRM_PURGE_SESSION'); ?>"))
	  return true;
  else
	  return false;
<?php
}
else
	{?>
	alert("Please enter master password");
	return false;
<?php }
?>	  
}
</script>
<?php
if($JSecureConfig->enableMasterPassword == '1' and !jsecureControllerjsecure::isMasterLogged())
{
?>
<form action="index.php" method="post" name="adminForm" autocomplete="off" class="masterpassfrm">
	<table class="adminlist" cellspacing="0" style="border-spacing:0">
		<tr>
			<td width="105"><?php echo JText::_('MASTER_PASSWORD'); ?></td>
			<td><input type="password" name="master_password" class="textarea" value="" size="50" /></td>
		</tr>
		<tr>
        	<td></td>
			<td><input type="submit" name="" value="<?php echo JText::_('JSECURE_LOGIN'); ?>" /></td>
		</tr>
	</table>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_jsecure" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="view" value="auth" />
</form>
<?php
}
?>

<div class="criticalfeature">
<h3>Critical Features</h3>
<div class="enabled_plugins">
     
    <?php 
      $jsecure_enable = $JSecureConfig->publish; 
      if($jsecure_enable == 1){$var = 'on';$status="<p>On<p>";}else{$var = '';$status="<p>Off</p>";}?>
    <div class="div_enable <?php echo $var;?>">
		<div>
                <?php echo "Basic Configuration";
                    echo $status;
                    
                 ?>
		</div>
	</div>
	<?php $autoban_enable = $JSecureConfig->abip;
       if($autoban_enable == 1){$var = 'on';$status="<p>On<p>";}else{$var = '';$status="<p>Off</p>";} ?>
	<div class="div_enable <?php echo $var;?>"> 
		<div>
		   <?php echo "Autoban IP"; 
		   echo $status;
		   ?>
		</div>   
	</div>
    
       <?php $masterPassword_enable = $JSecureConfig->enableMasterPassword;
       if($masterPassword_enable == 1){$var = 'on';$status="<p>On<p>";}else{$var = '';$status="<p>Off</p>";}?>
         <div class="div_enable <?php echo $var;?>"> 
		<div>
		   <?php echo "Master Password"; 
		   echo $status;
		   ?>
		</div>   
	</div>
    <?php $login_control_enable = $JSecureConfig->login_control; if($login_control_enable == 1) 
        {$var = 'on';$status="<p>On<p>";}else{$var = '';$status="<p>Off</p>";}?>
	<div class="div_enable <?php echo $var;?>"> 
		<div>
		   <?php echo "Master Login Control"; 
		   echo $status;
		   ?>
		</div>   
	</div>
    
    <?php $adminpasswordpro_enable = $JSecureConfig->adminpasswordpro;
     if($adminpasswordpro_enable == 1){$var = 'on';$status="<p>On<p>";}else{$var = '';$status="<p>Off</p>";}?>
    <div class="div_enable <?php echo $var;?>"> 
		<div>
		   <?php echo "Administrator Password Protection"; 
		   echo $status;
		   ?>
		  
		</div>   
	</div>
   <?php $country_enable = $JSecureConfig->countryblock;
        if($country_enable == 1){$var = 'on';$status="<p>On<p>";}else{$var = '';$status="<p>Off</p>";}?>
	<div class="div_enable <?php echo $var;?>"> 
		<div>
		   <?php echo "Country Block"; 
		   echo $status;
		   ?>
		</div>   
	</div>
<?php $captcha_enable = $JSecureConfig->captchapublish;
if($captcha_enable == 1){$var = 'on';$status="<p>On<p>";}else{$var = '';$status="<p>Off</p>";}?>
     <div class="div_enable <?php echo $var;?>"> 
		<div>
		   <?php echo "Captcha"; 
		   echo $status;
		   ?>
		</div>   
	</div>
	<!--<div class="div_enable">
		<div>
		   <?php echo " Email Scan"; 
		   $email_enable = $JSecureConfig->publishemailcheck;
		   if($email_enable == 1){echo "<p>On</p>";}else{echo "<p>disable</p>";}
		   ?>
		</div>
	</div>-->
</div>
</div>
<div class="row-fluid" style="background-color:#F2F2F2;">
<div class="span9 graphs" style="">


<!--security level--> 
<div class="row-fluid">
	<div class="span12">
		<h3>Dashboard</h3>
	</div>
</div>


<div class="row-fluid">
	<div class="span6">
		<div id="container-security" style="height: 300px; margin: 0 auto; width:100%;">
		<?php 
		
		   $security_count_enable = $JSecureConfig->publish;
                if($security_count_enable == 1){
		$security_count = array(
		$publish_count = $JSecureConfig->publish,
		$country_count = $JSecureConfig->countryblock,
		$captcha_count = $JSecureConfig->captchapublish,
		//$imageSecure_count = $JSecureConfig->imageSecure,
		$MasterPsswd_count = $JSecureConfig->enableMasterPassword,
		//$sendemail_count = $JSecureConfig->sendemail,
		$abip_count = $JSecureConfig->abip,
		//$spamip_count = $JSecureConfig->spamip,
		//$metatag_count = $JSecureConfig->metatagcontrol,
		//$memail_count = $JSecureConfig->mpsendemail,
		//$allowedthreatrating_count = $JSecureConfig->allowedthreatrating,
		$login_control_count = $JSecureConfig->login_control,
		$adminpasswordpro_count = $JSecureConfig->adminpasswordpro,
		//$adminType = $JSecureConfig->adminType
		);
		//print_r($security_count);die; 
		$security_sum = array_sum($security_count);
		$security_item_count = count($security_count);
		$security_percentage = round((($security_sum/$security_item_count)*100),2);
		$non_secure_percentage = (100-$security_percentage);
		//echo $non_secure_percentage;die;
		}
		else{
                    $security_percentage = 0 ; 
                    $non_secure_percentage = 100;
                }
		?> 
		<script type="text/javascript">
				$(function () {
			$('#container-security').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: 0,
					plotShadow: false
				},
				title: {
					text: 'jSecure Security Level',
					y: 30
				},
                                tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.0.2f}%</b>'
				},
				plotOptions: {
					pie: {
						dataLabels: {
							enabled: true,
                                                        distance: -50,
                                                        
							style: {
								fontWeight: 'bold',
                                                                verticalAlign: 'middle',
                                                                color: 'black'
							}
						},
						startAngle: -90,
						endAngle: 90,
						center: ['50%', '75%']
					}
				},credits: {
				
				enabled:false
				
				},
				series: [{
					type: 'pie',
					name: 'Security Enable Plugins',
					innerSize: '68%',
					data: [{
						name: 'Safety <br/> Level',
                                                y: <?php echo $security_percentage; ?>,
                                                color: '#EB562C'
                                                
					},
					{
						name: 'Threat <br/> Level',
						y: <?php echo $non_secure_percentage; ?>,
						color: '#32CDEC'
					},
					{
						name: '',
						y: 0,
						dataLabels: {
							enabled: true
							}
					}
					]
				}]
			});
		});
		</script>
		</div>
	</div>
	<div class="span6">
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php

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
      var nw = (0.38 * w);
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



   <div id="container_pie_country" style="height: 300px">
<?php 
//$query =  "SELECT `c.name`,`c.published`,`c.published` FROM `#__jsecure_countries`" ;
$query =  "SELECT country ,count(country) as count FROM `#__jsecure_country_block_logs` GROUP BY country ORDER BY count desc";
$db->setQuery($query);
$result = $db->loadObjectList();
$resultcount = count($result);
$result_arr = $result[0];
$check_res = (array)$result_arr;
if(empty($check_res)){
   $flag = 0; 
  $attack_countr_1_name = "attack free";
  $attack_countr_1_attacked = 100;
  $countr_sum = 100 ; 
}else{
   $flag = 1;
$attack_countr_1_name = $result[0]->country;
$attack_countr_2_name = $result[1]->country;
$attack_countr_3_name = $result[2]->country;
$attack_countr_1_attacked = $result[0]->count;
$attack_countr_2_attacked = $result[1]->count;
$attack_countr_3_attacked = $result[2]->count;
$j = 0;
foreach ($result as $r){
   $countr_count[$j] = $r->count;
   $j++;
}
$countr_sum = array_sum($countr_count);
}
//echo $flag;die;
?>
   <script type="text/javascript">
       $(function () {
   $('#container_pie_country').highcharts({
       chart: {
           type: 'pie',
           options3d: {
               enabled: true,
               alpha: 45,
               beta: 0
           }
       },
       title: {
           text: 'Top 3 country wise attack using Country Block',
		   y: 30
       },
       tooltip: {
           pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of <?php echo $countr_sum; ?><br/>'
       },
       plotOptions: {
           pie: {
               allowPointSelect: true,
               cursor: 'pointer',
               depth: 35,
               dataLabels: {
                   enabled: true,
                   format: '{point.name}'
               }
           }
       },credits: {
        
        enabled:false
        
        },
       series: [{
           type: 'pie',
           name: '',
           data: [
               <?php if($flag == '0'){ ?>
                     {
                   name: "<?php echo $attack_countr_1_name; ?>",
                   y: <?php echo $attack_countr_1_attacked; ?>,
                   sliced: true,
                   selected: true,
                   color: '#3A3A3A'
               },  
               <?php } else {
			
			    switch ($resultcount) {
    case 1:?> {
                   name: "<?php echo $attack_countr_1_name; ?>",
                   y: <?php echo $attack_countr_1_attacked; ?>,
                   sliced: true,
                   selected: true,
                   color: '#30CDEC'
               },<?php break;
			   
    case 2:?>			   
               {
                   name: "<?php echo $attack_countr_1_name; ?>",
                   y: <?php echo $attack_countr_1_attacked; ?>,
                   sliced: true,
                   selected: true,
                   color: '#30CDEC'
               },{
                   name: "<?php echo $attack_countr_2_name; ?>",
                   y: <?php echo $attack_countr_2_attacked; ?>,
                   sliced: true,
                   selected: true,
                   color: '#3A3A3A'
               },<?php
			   break;
			   
	case 3:?>		   
			 {
                   name: "<?php echo $attack_countr_1_name; ?>",
                   y: <?php echo $attack_countr_1_attacked; ?>,
                   sliced: true,
                   selected: true,
                   color: '#30CDEC'
               },{
                   name: "<?php echo $attack_countr_2_name; ?>",
                   y: <?php echo $attack_countr_2_attacked; ?>,
                   sliced: true,
                   selected: true,
                   color: '#3A3A3A'
               },{
                   name: "<?php echo $attack_countr_3_name; ?>",
                   y: <?php echo $attack_countr_3_attacked; ?>,
                   sliced: true,
                   selected: true,
                   color: '#EB562C '
               },<?php break;
			   
			   default :?> {
                   name: "<?php echo $attack_countr_1_name; ?>",
                   y: <?php echo $attack_countr_1_attacked; ?>,
                   sliced: true,
                   selected: true,
                   color: '#30CDEC'
               },{
                   name: "<?php echo $attack_countr_2_name; ?>",
                   y: <?php echo $attack_countr_2_attacked; ?>,
                   sliced: true,
                   selected: true,
                   color: '#3A3A3A'
               },{
                   name: "<?php echo $attack_countr_3_name; ?>",
                   y: <?php echo $attack_countr_3_attacked; ?>,
                   sliced: true,
                   selected: true,
                   color: '#EB562C '
               },<?php break;
			   
			   ?>
               <?php }}?>
           ]
       }]
   });
});
   </script>
   </div>
  
  
  
	</div>
</div>
<div class="row-fluid">
<div class="span12">
	<div id="container_graph" style="width: 100%; height: 400px; margin: 0 auto">
  
 <!-------  Graph For No Of WhiteListed And Black Listed Ips  -------->  
<?php
$iplistBlack = $JSecureConfig->iplistB;
if($iplistBlack == ""){
  $black_ips_sum = 0;  
}else{
   $blck_ips = explode("\n",$iplistBlack);
   $black_ips_sum = count($blck_ips);
}
$iplistWhite = $JSecureConfig->iplistW;
if($iplistWhite == ""){
  $white_ips_sum = 0;  
}else{
   $white_ips = explode("\n",$iplistWhite);
   $white_ips_sum = count($white_ips);
}
?>
 
<script type="text/javascript">
$(function () {
    // Create the chart
    $('#container_graph').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'IP Address'
        },
        subtitle: {
            text: 'Total Number of IP Address added in IP Filters as Whitelisted/Blacklisted IP</a>.'
        },
        xAxis: {
            type: 'category',
            width: 130
        },
        yAxis: {
            title: {
                text: 'Total number of IP address'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.0f}'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
    
        },credits: {
	     enabled:false
		},

        series: [{
            name: 'Sum Of Whitelisted/BlackListed Ip',
            colorByPoint: true,
            data: [{
                name: 'Whitelisted IP',
                y: <?php echo $white_ips_sum;?>,
                color: '#EB562C',
                drilldown: null
            
            },{
                name: 'Blacklisted IP',
                y: <?php echo $black_ips_sum;?>,
                color: '#32CDEC',
                drilldown: null
            }]
        }],
        
});
});

</script> 
</div>
</div>
</div>
</div>
<div class="span3 sidegrid">
	<div class="sidegridwrap">
		<div class="sidegridcontainer">
				
		<div class="socialwrap">
			<p>Spread the word</p>
			<div>
				<ul>
                                    
                                    <li><a href="http://www.facebook.com/joomlaserviceprovider/" target="_blank"><img src="<?php echo JURI::root().'administrator';?>/components/com_jsecure/images/menuicons/fb.png" alt="facebook"/></a></li>
					<li><a href="https://twitter.com/JSP_Team" target="_blank"><img src="<?php echo JURI::root().'administrator';?>/components/com_jsecure/images/menuicons/tw.png" alt="twitter"/></a></li>
					<li><a href="http://www.youtube.com/user/jspwebsite" target="_blank"><img src="<?php echo JURI::root().'administrator';?>/components/com_jsecure/images/menuicons/youtube.png" alt="youtube"/></a></li>
					<li><a href="https://plus.google.com/+Joomlaserviceproviderteam" target="_blank"><img src="<?php echo JURI::root().'administrator';?>/components/com_jsecure/images/menuicons/gplus.png" alt="google plus"/></a></li>
				</ul>
			</div>
		</div>
		</div>
		
		<div class="versioncontainer">
			<div class="versionwrap">
				<p>jSecure Authentication</p>
				<p><strong>Version 3.5</strong></p>
				<div id="version"><a href="#"></a></div> 
			</div>
		</div>
		<div id="license"></div>
	</div>
</div>
</div>
<div class="sidepanel-overlay"></div>

