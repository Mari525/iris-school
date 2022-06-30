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
defined( '_JEXEC' ) or die( 'Restricted access' );

include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
$document = JFactory::getDocument();
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/auth.js"></script>');
$basepath   = JPATH_ADMINISTRATOR .'/components/com_jsecure';
		$configFile	 = $basepath.'/params.php';
		$app        = JFactory::getApplication();
		require_once($configFile);
		
		$JSecureConfig = new JSecureConfig();
if (!jsecureControllerjsecure::isMasterLogged() and $JSecureConfig->enableMasterPassword == '1' and $JSecureConfig->include_directorylisting == '1' )
{
JError::raiseWarning(404, JText::_('NOT_AUTHERIZED'));	
$link = "index.php?option=com_jsecure";
$app->redirect($link);
}
else{
?>
<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form action="index.php" method="post" name="adminForm" class="span10">
	

		<?php
/*

CONFIGURATION
=============
Edit the variables in this section to make the script work as
you require.

Include URL - If you are including this script in another file, 
please define the URL to the Directory Listing script (relative
from the host)
*/
$includeurl = false;

/*
Start Directory - To list the files contained within the current 
directory enter '.', otherwise enter the path to the directory 
you wish to list. The path must be relative to the current 
directory and cannot be above the location of index.php within the 
directory structure.
*/
$startdir = '../';

/*
Show Thumbnails? - Set to true if you wish to use the 
scripts auto-thumbnail generation capabilities.
This requires that GD2 is installed.
*/
$showthumbnails = true; 

/*
Memory Limit - The image processor that creates the thumbnails
may require more memory than defined in your PHP.INI file for 
larger images. If a file is too large, the image processor will
fail and not generate thumbs. If you require more memory, 
define the amount (in megabytes) below
*/
$memorylimit = false; // Integer

/*
Show Directories - Do you want to make subdirectories available?
If not set this to false
*/
$showdirs = true;

/* 
Force downloads - Do you want to force people to download the files
rather than viewing them in their browser?
*/
$forcedownloads = false;

/*
Hide Files - If you wish to hide certain files or directories 
then enter their details here. The values entered are matched
against the file/directory names. If any part of the name 
matches what is entered below then it is not shown.
*/
$hide = array(
				'dlf',
				'index.php',
				'Thumbs',
				'.htaccess',
				'.htpasswd'
			);
			
/* Only Display Files With Extension... - if you only wish the user
to be able to view files with certain extensions, add those extensions
to the following array. If the array is commented out, all file
types will be displayed.
*/
/*$showtypes = array(
					'jpg',
					'png',
					'gif',
					'zip',
					'txt'
				);*/
			 
/* 
Show index files - if an index file is found in a directory
to you want to display that rather than the listing output 
from this script?
*/			
$displayindex = false;

/*
Allow uploads? - If enabled users will be able to upload 
files to any viewable directory. You should really only enable
this if the area this script is in is already password protected.
*/
$allowuploads = false;

/* Upload Types - If you are allowing uploads but only want
users to be able to upload file with specific extensions,
you can specify these extensions below. All other file
types will be rejected. Comment out this array to allow
all file types to be uploaded.
*/
/*$uploadtypes = array(
						'zip',
						'gif',
						'doc',
						'png'
					);*/

/*
Overwrite files - If a user uploads a file with the same
name as an existing file do you want the existing file
to be overwritten?
*/
$overwrite = false;

/*
Index files - The follow array contains all the index files
that will be used if $displayindex (above) is set to true.
Feel free to add, delete or alter these
*/

$indexfiles = array (
				'index.html',
				'index.htm',
				'default.htm',
				'default.html'
			);
			
/*
File Icons - If you want to add your own special file icons use 
this section below. Each entry relates to the extension of the 
given file, in the form <extension> => <filename>. 
These files must be located within the dlf directory.
*/
$filetypes = array (
				'png' => 'jpg.gif',
				'jpeg' => 'jpg.gif',
				'bmp' => 'jpg.gif',
				'jpg' => 'jpg.gif', 
				'gif' => 'gif.gif',
				'zip' => 'archive.png',
				'rar' => 'archive.png',
				'exe' => 'exe.gif',
				'setup' => 'setup.gif',
				'txt' => 'text.png',
				'htm' => 'html.gif',
				'html' => 'html.gif',
				'fla' => 'fla.gif',
				'swf' => 'swf.gif',
				'xls' => 'xls.gif',
				'doc' => 'doc.gif',
				'sig' => 'sig.gif',
				'fh10' => 'fh10.gif',
				'pdf' => 'pdf.gif',
				'psd' => 'psd.gif',
				'rm' => 'real.gif',
				'mpg' => 'video.gif',
				'mpeg' => 'video.gif',
				'mov' => 'video2.gif',
				'avi' => 'video.gif',
				'eps' => 'eps.gif',
				'gz' => 'archive.png',
				'asc' => 'sig.gif',
			);
			
/*
That's it! You are now ready to upload this script to the server.

Only edit what is below this line if you are sure that you know what you
are doing!
*/

if($includeurl)
{
	$includeurl = preg_replace("/^\//", "${1}", $includeurl);
	if(substr($includeurl, strrpos($includeurl, '/')) != '/') $includeurl.='/';
}

error_reporting(0);
if(!function_exists('imagecreatetruecolor')) $showthumbnails = false;
if($startdir) $startdir = preg_replace("/^\//", "${1}", $startdir);
$leadon = $startdir;
if($leadon=='.') $leadon = '';
if((substr($leadon, -1, 1)!='/') && $leadon!='') $leadon = $leadon . '/';
$startdir = $leadon;

if($_GET['dir']) {
	//check this is okay.
	
	if(substr($_GET['dir'], -1, 1)!='/') {
		$_GET['dir'] = strip_tags($_GET['dir']) . '/';
	}
	
	$dirok = true;
	$dirnames = split('/', strip_tags($_GET['dir']));
	for($di=0; $di<sizeof($dirnames); $di++) {
		
		if($di<(sizeof($dirnames)-2)) {
			$dotdotdir = $dotdotdir . $dirnames[$di] . '/';
		}
		
		if($dirnames[$di] == '..') {
			$dirok = false;
		}
	}
	
	if(substr($_GET['dir'], 0, 1)=='/') {
		$dirok = false;
	}
	
	if($dirok) {
		 $leadon = $leadon . strip_tags($_GET['dir']);
	}
}
$opendir = $includeurl.$leadon;
if(!$leadon) $opendir = '.';
if(!file_exists($opendir)) {
	$opendir = '.';
	$leadon = $startdir;
}

clearstatcache();
if ($handle = opendir($opendir)) {
	while (false !== ($file = readdir($handle))) { 
		//first see if this file is required in the listing
		if ($file == "." || $file == "..")  continue;
		$discard = false;
		for($hi=0;$hi<sizeof($hide);$hi++) {
			if(strpos($file, $hide[$hi])!==false) {
				$discard = true;
			}
		}
		
		if($discard) continue;
		if (@filetype($includeurl.$leadon.$file) == "dir") {
			if(!$showdirs) continue;
		
			$n++;
			if($_GET['sort']=="date") {
				$key = @filemtime($includeurl.$leadon.$file) . ".$n";
			}
			else {
				$key = $n;
			}
			$dirs[$key] = $file . "/";
		}
		else {
			$n++;
			if($_GET['sort']=="date") {
				$key = @filemtime($includeurl.$leadon.$file) . ".$n";
			}
			elseif($_GET['sort']=="size") {
				$key = @filesize($includeurl.$leadon.$file) . ".$n";
			}
			else {
				$key = $n;
			}
			
			if($showtypes && !in_array(substr($file, strpos($file, '.')+1, strlen($file)), $showtypes)) unset($file);
			if($file) $files[$key] = $file;
			
			if($displayindex) {
				if(in_array(strtolower($file), $indexfiles)) {
					header("Location: $leadon$file");
					die();
				}
			}
		}
	}
	closedir($handle); 
}

//sort our files
if($_GET['sort']=="date") {
	@ksort($dirs, SORT_NUMERIC);
	@ksort($files, SORT_NUMERIC);
}
elseif($_GET['sort']=="size") {
	@natcasesort($dirs); 
	@ksort($files, SORT_NUMERIC);
}
else {
	@natcasesort($dirs); 
	@natcasesort($files);
}

//order correctly
if($_GET['order']=="desc" && $_GET['sort']!="size") {$dirs = @array_reverse($dirs);}
if($_GET['order']=="desc") {$files = @array_reverse($files);}
$dirs = @array_values($dirs); $files = @array_values($files);


?>
<link rel="stylesheet" type="text/css" href="<?php echo $includeurl; ?>components/com_jsecure/css/styles.css" />
<?php
if($showthumbnails) {
?>
<script language="javascript" type="text/javascript">
<!--
function o(n, i) {
	document.images['thumb'+n].src = '<?php echo $includeurl; ?>jsecure_lib.php?f='+i<?php if($memorylimit!==false) echo "+'&ml=".$memorylimit."'"; ?>;

}

function f(n) {
	document.images['thumb'+n].src = 'components/com_jsecure/images/trans.gif';
}
//-->
</script>
<?php
}
?>

<div id="container">
<?php /*?><h1>Directory Listing of <?php echo str_replace('\\', '', dirname(strip_tags($_SERVER['PHP_SELF']))).'/'.$leadon;?></h1><?php */?>
<fieldset id="filter-bar">
<h1>Directory Listing of Files and Folders From this site</h1>
  <!--<hi>Directory Listing For All folders From This Site</h1>-->
 
  </fieldset>
 <div id="breadcrumbs"><a href="<?php echo strip_tags($_SERVER['PHP_SELF']);?>?option=com_jsecure&task=directory">home</a> 
  
  <?php
 	 $breadcrumbs = split('/', str_replace($startdir, '', $leadon));
  	if(($bsize = sizeof($breadcrumbs))>0) {
  		$sofar = '';
  		for($bi=0;$bi<($bsize-1);$bi++) {
			$sofar = $sofar . $breadcrumbs[$bi] . '/';
			echo ' <a href="'.strip_tags($_SERVER['PHP_SELF']).'?option=com_jsecure&task=directory&dir='.urlencode($sofar).'">'.$breadcrumbs[$bi].'</a>';
		}
  	}
  
	$baseurl = strip_tags($_SERVER['PHP_SELF']) . '?option=com_jsecure&task=directory&dir='.strip_tags($_GET['dir']) . '&amp;';
	$fileurl = 'sort=name&amp;order=asc';
	$sizeurl = 'sort=size&amp;order=asc';
	$dateurl = 'sort=date&amp;order=asc';
	
	switch ($_GET['sort']) {
		case 'name':
			if($_GET['order']=='asc') $fileurl = 'sort=name&amp;order=desc';
			break;
		case 'size':
			if($_GET['order']=='asc') $sizeurl = 'sort=size&amp;order=desc';
			break;
			
		case 'date':
			if($_GET['order']=='asc') $dateurl = 'sort=date&amp;order=desc';
			break;  
		default:
			$fileurl = '';
			break;
	}
  ?>
  </div>  
  <table class="table table-striped" cellspacing="1">
  
     <thead>
    <tr>
		<th class="title">
			<div id="headerfile"><a href="<?php echo $baseurl . $fileurl;?>">File</a></div>
	</th>
		<th class="title">
			<div id="headersize"><a href="<?php echo $baseurl . $sizeurl;?>">Size</a></div>
	</th>
		<th class="title">
	<div id="headermodified"><a href="<?php echo $baseurl . $dateurl;?>">Last Modified</a></div>
	</th>
		<th class="title">
    <div id="headerpermissions"><a href="<?php echo $baseurl . $dateurl;?>">Permissions</a></div>
    </th>
    </tr>
    </thead>
	<tbody>
    
   
	<?php
	$class = 'b';
	if($dirok) {
	?>
	<tr >
    <td colspan="4">
	<div>
    	<a href="<?php echo strip_tags($_SERVER['PHP_SELF']).'?option=com_jsecure&task=directory&dir='.urlencode($dotdotdir);?>" class="<?php echo $class;?>">
        <img src="<?php echo $includeurl; ?>components/com_jsecure/images/dirup.png" alt="Folder" />
        <strong>..</strong> <em>&nbsp;</em>&nbsp;</a>
     </div>
	</td>
    </tr>
	<?php
		if($class=='b') $class='w';
		else $class = 'b';
	}
	$arsize = sizeof($dirs);
	$k = 0;
	for($i=0;$i<$arsize;$i++) {
		$perms = substr(sprintf('%o', fileperms($includeurl.$leadon.$dirs[$i])), -3);
		if($perms > 775){
			$perm_classfolder = red;
		}
		else
		{
			$perm_classfolder = blue;
		}
		
	?>
	
<tr class="<?php echo"row$k";?>">
<td>
<a href="<?php echo strip_tags($_SERVER['PHP_SELF']).'?option=com_jsecure&task=directory&dir='.urlencode(str_replace($startdir,'',$leadon).$dirs[$i]);?>" class="<?php echo $class;?>"><img src="<?php echo $includeurl; ?>components/com_jsecure/images/folder.png" alt="<?php echo $dirs[$i];?>" /><?php echo $dirs[$i];?></a>
</td>
<td>
<em>-</em>
</td>
<td><span class="lastModify"><?php echo date ("M d Y h:i:s A", filemtime($includeurl.$leadon.$dirs[$i]));?></span></td>
<td><span class="permission <?php echo $perm_classfolder;?>"><?php echo substr(sprintf('%o', fileperms($includeurl.$leadon.$dirs[$i])), -3); ?></span></a></td>
</tr>

	<?php
		if($class=='b') $class='w';
		else $class = 'b';	
		$k = 1 - $k;
	}
	
	$arsize = sizeof($files);
	$k = 0;
	for($i=0;$i<$arsize;$i++) {
		$icon = 'unknown.png';
		$ext = strtolower(substr($files[$i], strrpos($files[$i], '.')+1));
		$supportedimages = array('gif', 'png', 'jpeg', 'jpg');
		$thumb = '';
		
		if($filetypes[$ext]) {
			$icon = $filetypes[$ext];
		}
		
		$filename = $files[$i];
		if(strlen($filename)>43) {
			$filename = substr($files[$i], 0, 40) . '...';
		}
		
	$perms = substr(sprintf('%o', fileperms($includeurl.$leadon.$files[$i])), -3);
		if($perms > 644){
			$perm_classfile = red;
		}
		else
		{
			$perm_classfile = blue;
		}

	?>
	
<tr class="<?php echo"row$k";?>">
<td>
<a class="<?php echo $class;?>"><img src="<?php echo $includeurl; ?>components/com_jsecure/images/<?php echo $icon;?>" alt="<?php echo $files[$i];?>" /><?php echo $filename;?></a>
</td>
<td>
<em><?php echo round(filesize($includeurl.$leadon.$files[$i])/1024);?>KB</em>
</td>
<td>
<span class="lastModify"><?php echo date ("M d Y h:i:s A", filemtime($includeurl.$leadon.$files[$i]));?></span>
</td>
<td>
<span class="permission <?php echo $perm_classfile; ?>"><?php echo substr(sprintf('%o', fileperms($includeurl.$leadon.$files[$i])), -3); ?><?php echo $thumb;?></span></a>
</td>
</tr>

	<?php
		if($class=='b') $class='w';
		else $class = 'b';	
		$k = 1 - $k;
	}	
	?>
	</tbody>
 
	
	</table>
    
	<input type="hidden" name="option" value="com_jsecure"/>
	<input type="hidden" name="task" value=""/>
	</div>
	</form>
	<?php
}
	?>
