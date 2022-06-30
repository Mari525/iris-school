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
ini_set('display_errors', '0');
error_reporting(0);
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('behavior.framework', true);
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
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
");



/*added*/

$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
$configFile	= $basepath.'/params.php';
require_once($configFile);

$config = $this->JSecureConfig;
$iplistB = $config->iplistB ;

$IPB = explode("\n",$iplistB);
$IPB = array_map('trim',$IPB);
/**/
$app        = JFactory::getApplication();
$JSecureConfig = $this->JSecureConfig;
if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_autobanip == '1' )
{
 JError::raiseWarning(404, JText::_('NOT_AUTHERIZED')); 
 $link = "index.php?option=com_jsecure";
 $app->redirect($link);
}
else
{
?>

<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php?option=com_jsecure" method="post" name="adminForm" id= "adminForm" class="span10">
<h3><?php echo JText::_('ADMIN_ACCESS_LOG');?></h3>
<table width="100%">
<tr>
	<td >
		<div id="filter-bar" class="btn-toolbar">
<div class="filter-search btn-group pull-left">
<input id="search" type="text" title="Search" value="<?php echo $this->search;?>" placeholder="<?php echo JText::_('FILTER_IP'); ?>" name="search" onchange="document.adminForm.submit();">
					</div>
					<div class="btn-group pull-left">
					<button class="btn tip" rel="tooltip" type="submit" data-original-title="Search" onclick="this.form.submit();">
					<i class="icon-search"></i>
					 </button>
					<button class="btn tip" rel="tooltip" onclick="document.id('search').value='';this.form.submit();" type="button" data-original-title="Clear">
					<i class="icon-remove"></i>
					</button>
					</div>
					<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
					</div>
	</td>
</tr>
</table>
<table class="table table-striped">
<thead>
	<tr>
		<th width="2%">
			<?php echo JText::_( 'Num' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'IP' ); ?>
		</th>
		<th class="title" nowrap="nowrap">
			<?php echo JText::_( 'User Name' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'Code' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'Log' ); ?>
		</th>
		<th class="title">
			<?php echo JText::_( 'Date' ); ?>
		</th>
		<th class="title"  width="12%">
			<?php echo JText::_( 'Action' ); ?>
		</th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="13" align="center">
			<?php 
			echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>
<tbody>
	<?php 
	$i=0;$k = 0;
	foreach($this->data as $row){
	$user='';
/* check if the user has been deleted - START */
		 $db = JFactory::getDBO();
	     $query = $db->getQuery(true);
	     $query->select('*');
	     $query->from('#__users');
		 $query->where('id ='.$row->userid);
	     $db->setQuery($query);
	     $record = $db->loadObject();
		 
/* check if the user has been deleted - END */
if(!empty($record)){
$user = JUser::getInstance($row->userid);
}
else{
$user->username = 'N/A';
}
$denyaccess = 0;
		foreach($IPB as $ip){
			if($ip!=""){
					
					$flag = array();
					$denyaccess = 0;

					$checkip = $row->ip;

					if( $row->ip == '::1' )
						$checkip = '127.0.0.1';	
					if( $ip  == '::1' )
						$ip = '127.0.0.1';	

					$blackip = explode('.', $ip);
					$checkip = explode('.', $checkip );

					for( $z = 0 ;$z < 4 ; $z++)
					{
						if( $blackip[ $z ] == '*' )
						{
							$flag[ $z ] = true;//..it matches & is true
						}
						else
						{
							if( $blackip[ $z ] === $checkip[ $z ]  )
							{					
								$flag[ $z ] = true;//..it matches & is true
							}
							else
							{
								$flag[ $z ] = false;//not matches
							}

						}
	
					}//end of for loop....


					if( $flag[0] == true &&  $flag[1] == true && $flag[2] == true && $flag[3] == true )
					{
							$denyaccess = 1;
							break;
					}
					
			}

		}//end of for loop of blacklisted Ip....

	?>
	
	<?php
    
    if($row->ip == '::1'){
    $row->ip = '127.0.0.1';
    }
    
    ?>
	
	<tr class="<?php echo "row$k"; ?>">
		<td>
			<?php echo  $this->pagination->getRowOffset( $i ); ?>
		</td>
		<td align="left">
		<span class="bold hasTip" title="<?php echo JText::_('IP Address')."::".JText::_('Click here to view information of this IP address');?>">
			<a class="modal" title="IP INFO"  href="index.php?option=com_jsecure&amp;task=ipinfo&amp;ip=<?php echo $row->ip;?>&amp;tpl=component" rel="{handler: 'iframe', size: {x: 800, y: 500}}">  
    			<?php echo $row->ip; ?>
   			</a>
		</span>	
		</td>	
		<td align="left">
			<?php echo $user->username; ?>
		</td>
		<td align="left">
			<?php echo JText::_($row->code); ?>
		</td>
		<td align="left">
			<?php echo str_replace("\n","<br/>",$row->change_variable); ?>
		</td>
		<td align="left">
			<?php echo str_replace("\n","<br/>",$row->date); ?>
		</td>
		<td align="left"><?php 
			if( $denyaccess  )
			{
				?>
				<span class="bold hasTip btn"  title="<?php echo JText::_('UNBLOCK_IP')."::".JText::_('COM_JSECURE_UNBLOCK');?>">
				<a href="index.php?option=com_jsecure&amp;task=unblockip&amp;ip=<?php echo $row->ip;?>" >		<?php echo JText::_( 'COM_JSECURE_UNBLOCK' ); ?></a>
				</span><?php 
			
			}
			else
			{
				?>
				<span class="bold hasTip btn"  title="<?php echo JText::_('BLOCK_IP')."::".JText::_('COM_JSECURE_BLOCK');?>">
				<a href="index.php?option=com_jsecure&amp;task=blockip&amp;ip=<?php echo $row->ip;?>" >		<?php echo JText::_( 'COM_JSECURE_BLOCK' ); ?></a>
				</span><?php 
			}
					?>
		</td>

	</tr>
	<?php
		$k = 1 - $k;	$i++;
	}
	?>
</tbody>
</table>
<input type="hidden" name="option" value="com_jsecure" />
<input type="hidden" name="task" value="log" />
<input type="hidden" name="boxchecked" value="0" />
</form>
<?php 
} ?>