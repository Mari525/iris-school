<?php
/**
 * J!Blank Template for Joomla by JBlank.pro (JBZoo.com)
 *
 * @package    JBlank
 * @author     SmetDenis <admin@jbzoo.com>
 * @copyright  Copyright (c) JBlank.pro
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link       http://jblank.pro/ JBlank project page
 */

defined('_JEXEC') or die;


// load libs
!version_compare(PHP_VERSION, '5.3.10', '=>') or die('Your host needs to use PHP 5.3.10 or higher to run JBlank Template');
require_once dirname(__FILE__) . '/libs/template.php';

/************************* runtime configurations *********************************************************************/
$tpl = JBlankTemplate::getInstance();
$tpl
    // enable or disable debug mode. Default in Joomla configuration.php
    //->debug(true)

    // include CSS files if it's not empty

    ->css(array(
        // 'template.css', // from jblank/css folder

        // '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', // any external lib (you can use http:// or https:// urls)
    ))

    // include JavaScript files
    ->js(array(
       // 'template.js',
        '../../media/system/js/core.js',
    ))

    // exclude css files from system or components (experimental!)
    ->excludeCSS(array(
        // 'regex pattern or filename',
        // 'jbzoo\.css',
    ))

    // exclude JS files from system or components (experimental!)
    ->excludeJS(array(
        // 'regex pattern or filename',
         'mootools',             // remove Mootools lib
         'media\/jui\/js',       // remove jQuery lib
        // 'media\/system\/js',    // remove system libs
    ))

    // set custom generator
    ->generator('CMS')// null for disable

    // set HTML5 mode (for <head> tag)
    ->html5(true)

    // add custom meta tags
    ->meta(array(
        // template customization
        '<meta http-equiv="X-UA-Compatible" content="IE=edge">',
        '<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />',
        '<meta content="telephone=no" name="format-detection">',

'<link rel="apple-touch-icon" sizes="180x180" href="/templates/jblank/setting/apple-touch-icon.png"> ',
'<link rel="icon" type="image/png" sizes="32x32" href="/templates/jblank/setting/favicon-32x32.png">',
'<link rel="icon" type="image/png" sizes="16x16" href="/templates/jblank/setting/favicon-16x16.png"> ',
'<link rel="manifest" href="/templates/jblank/setting/site.webmanifest"> ',
'<link rel="mask-icon" href="/templates/jblank/setting/safari-pinned-tab.svg" color="#5bbad5">',
'<link rel="shortcut icon" href="/templates/jblank/setting/favicon.ico"> ',
'<meta name="msapplication-TileColor" content="#da532c"> ',
'<meta name="msapplication-config" content="/templates/jblank/setting/browserconfig.xml">',
'<meta name="theme-color" content="#ffffff"> ',

     // '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">',
     //  ' <meta name="viewport" content="width=device-width, initial-scale=0.8">',
        // site verification examples
       // '<meta name="google-site-verification" content="... google verification hash ..." />',
       // '<meta name="yandex-verification" content="... yandex verification hash ..." />',
    ));





