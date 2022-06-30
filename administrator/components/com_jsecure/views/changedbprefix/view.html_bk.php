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
 * @version     $Id: view.html.php  $
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');
jimport('joomla.application.application');
jimport('joomla.html.pane');

class jsecureViewChangedbprefix extends JViewLegacy {
	protected $form;
	protected $item;
	protected $state;
	
function display($tpl=null){
		
        $this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		
		$app = JFactory::getApplication();
		$table_prefix = $app->getCfg('dbprefix');
		
		$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	= $basepath.'/params.php';
		require_once($configFile);
		$JSecureConfig = new JSecureConfig();
		
		$this->assignRef('table_prefix',$table_prefix);
		$this->assignRef('JSecureConfig',$JSecureConfig);
		
		jimport('joomla.html.pagination');
		$this->addToolbar();	
		
		//log end here
		parent::display($tpl);		
	}
	
	function randomPassword(){
	$app = JFactory::getApplication();
	
	$charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

	 for($i = 0; $i < 5; $i++)
	 {
	   $random_int = mt_rand();
	   $pass .= $charset[$random_int % strlen($charset)];
	 }
		$pass = $pass._;
		$model = $this->getModel('jsecurelog');
		
		if($pass){
		$dbprefix = $model->changedbprefix($pass);
		}
		
    if($dbprefix){
 			
			$image = JURI::base().'components/com_jsecure/images/ok-green.png';
			
			$dbprefix  .= '<p class="dbprefixclass"><img src='.$image.'> Database Prefix Updated in Configuration File and Database</p>';
			
			$dbprefix  .= '</div>';
			
			$dbprefix = $this->encryptor('encrypt',$dbprefix);
			
			$link = 'index.php?option=com_jsecure&task=changedbprefix&msg='.$dbprefix;
						
			$app->redirect($link);
			
			}
	}
	
	function submitdbprefix(){
		$app = JFactory::getApplication();	
		$model = $this->getModel('jsecurelog');
		$prefix = $_POST['db_prefix'];
		//$prefix = str_replace(' ','',$prefix);				
						
		if($prefix){
			$dbprefix = $model->changedbprefix($prefix);	
			
			if($dbprefix){
 			
			$image = JURI::base().'components/com_jsecure/images/ok-green.png';
			
			$dbprefix  .= '<p class="dbprefixclass"><img src='.$image.'> Database Prefix Updated in Configuration File and Database</p>';
			
			$dbprefix  .= '</div>';
			
			$dbprefix = $this->encryptor('encrypt',$dbprefix);
			
			$link = 'index.php?option=com_jsecure&task=changedbprefix&msg='.$dbprefix;
						
			$app->redirect($link);
			
			}
					
		}
	
	}
	
	function encryptor($action,$string) {
	
	
    $output = false;

    $encrypt_method = "AES-256-CBC";
    //pls set your unique hashing key
    $secret_key = 'muni';
    $secret_iv = 'muni123';

    // hash
    $key = hash('sha256', $secret_key);
	
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
			
    //do the encyption given text/string/number
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    		
	}
    else if( $action == 'decrypt' ){
    	//decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
	}
	
	function dbbackup(){
		$app = JFactory::getApplication();
		$dbhost = $app->getCfg('host');
		$dbuser = $app->getCfg('user');
		$dbpass = $app->getCfg('password');
		$dbname = $app->getCfg('db');
		$this->exportsql($dbhost,$dbuser,$dbpass,$dbname);
	}
	
	function exportsql($host,$user,$pass,$name,$tables=false, $backup_name=false, $replacements=array('OLD_DOMAIN.com','NEW_DOMAIN.com')){
	
	set_time_limit(3000); $mysqli = new mysqli($host,$user,$pass,$name); $mysqli->select_db($name); $mysqli->query("SET NAMES 'utf8'");
	$queryTables = $mysqli->query('SHOW TABLES'); while($row = $queryTables->fetch_row()) { $target_tables[] = $row[0]; }	if($tables !== false) { $target_tables = array_intersect( $target_tables, $tables); } 
	$content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `".$name."`\r\n--\r\n\r\n\r\n";
	
	foreach($target_tables as $table){
		if (empty($table)){ continue; } 
		
		$pos = strpos($table,'_jsecure');
		if($pos == true){
			$jsecureTablesArray[] = $table;
			continue;
		}
		
		$result	= $mysqli->query('SELECT * FROM `'.$table.'`');  	$fields_amount=$result->field_count;  $rows_num=$mysqli->affected_rows; 	$res = $mysqli->query('SHOW CREATE TABLE '.$table);	$TableMLine=$res->fetch_row(); 
		$content .= "\n\n".$TableMLine[1].";\n\n";
		for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
			while($row = $result->fetch_row())	{ //when started (and every after 100 command cycle):
				if ($st_counter%100 == 0 || $st_counter == 0 )	{$content .= "\nINSERT INTO ".$table." VALUES";}
					$content .= "\n(";    for($j=0; $j<$fields_amount; $j++){ $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); if (isset($row[$j])){$content .= '"'.$row[$j].'"' ;}  else{$content .= '""';}	   if ($j<($fields_amount-1)){$content.= ',';}   }        $content .=")";
				//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
				if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {$content .= ";";} else {$content .= ",";}	$st_counter=$st_counter+1;
			}
		} $content .="\n\n\n";
	}
	
	foreach($jsecureTablesArray as $table){
		if (empty($table)){ continue; } 
		
		$result	= $mysqli->query('SELECT * FROM `'.$table.'`');  	$fields_amount=$result->field_count;  $rows_num=$mysqli->affected_rows; 	$res = $mysqli->query('SHOW CREATE TABLE '.$table);	$TableMLine=$res->fetch_row(); 
		$content .= "\n\n".$TableMLine[1].";\n\n";
		for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
			while($row = $result->fetch_row())	{ //when started (and every after 100 command cycle):
				if ($st_counter%100 == 0 || $st_counter == 0 )	{$content .= "\nINSERT INTO ".$table." VALUES";}
					$content .= "\n(";    for($j=0; $j<$fields_amount; $j++){ $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); if (isset($row[$j])){$content .= '"'.$row[$j].'"' ;}  else{$content .= '""';}	   if ($j<($fields_amount-1)){$content.= ',';}   }        $content .=")";
				//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
				if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {$content .= ";";} else {$content .= ",";}	$st_counter=$st_counter+1;
			}
		} $content .="\n\n\n";
	}
	
	
	$content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";                              if (function_exists('DOMAIN_or_STRING_modifier_in_DB')) { $content = DOMAIN_or_STRING_modifier_in_DB($replacements[0], $replacements[1], $content); }
	//$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
	$backup_name = $backup_name ? $backup_name : $name."___(".date('d-m-Y')."_".date('H-i-s').")__random_".rand(1,11111111).".sql";
	
	ob_get_clean(); header('Content-Type: application/octet-stream');	header("Content-Transfer-Encoding: Binary"); header("Content-disposition: attachment; filename=\"".$backup_name."\"");
	echo $content; exit;
	}
	
    protected function addToolbar()
	{		
			JToolBarHelper::title(JText::_('jSecure Authentication'), 'generic.png');
			JToolBarHelper::cancel('cancel');
	}
			
}
?>