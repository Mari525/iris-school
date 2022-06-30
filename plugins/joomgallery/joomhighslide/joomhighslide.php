<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-3/Plugins/JoomHighslide/trunk/joomhighslide.php $
// $Id: joomhighslide.php 4189 2013-04-08 16:45:02Z chraneco $
/****************************************************************************************\
**   Plugin 'JoomHighslide' 2.0                                                         **
**   By: JoomGallery::ProjectTeam                                                       **
**   Copyright (C) 2010 - 2012 Patrick Alt                                              **
**   Released under GNU GPL Public License                                              **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look                       **
**   at administrator/components/com_joomgallery/LICENSE.TXT                            **
\****************************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once JPATH_ADMINISTRATOR.'/components/com_joomgallery/helpers/openimageplugin.php';

/**
 * JoomGallery Highslide JS Plugin
 *
 * With this plugin JoomGallery is able to use
 * Highslide JS (http://www.highslide.com/) for displaying images.
 *
 * NOTE: Please remember that Highslide JS is licensed under the terms
 * of the 'Creative Commons Attribution-NonCommercial 2.5 License':
 *
 * http://highslide.com/#licence
 * http://creativecommons.org/licenses/by-nc/2.5/
 *
 * @package     Joomla
 * @subpackage  JoomGallery
 * @since       1.5
 */
class plgJoomGalleryJoomHighslide extends JoomOpenImagePlugin
{
  /**
   * Name of this popup box
   *
   * @var   string
   * @since 3.0
   */
  protected $title = 'Highslide JS';

  /**
   * Initializes the box by adding all necessary JavaScript and CSS files.
   * This is done only once per page load.
   *
   * Please use the document object of Joomla! to add JavaScript and CSS files, e.g.:
   * <code>
   * $doc = JFactory::getDocument();
   * $doc->addStyleSheet(JUri::root().'media/plg_exampleopenimage/css/exampleopenimage.css');
   * $doc->addScript(JUri::root().'media/plg_exampleopenimage/js/exampleopenimage.js');
   * $doc->addScriptDeclaration("    jQuery(document).ready(function(){ExampleOpenImage.init()}");
   * </code>
   *
   * or if using Mootools or jQuery the respective JHtml method:
   * <code>
   * JHtml::_('jquery.framework');
   * JHtml::_('behavior.framework');
   * </code>
   *
   * @return  void
   * @since   3.0
   */
  protected function init()
  {
    $doc = JFactory::getDocument();

    $doc->addStyleSheet(JURI::root().'media/plg_joomhighslide/highslide.css');

    $doc->addScript(JURI::root().'media/plg_joomhighslide/highslide.js');
    $doc->addScriptDeclaration("  hs.graphicsDir = '".JURI::root()."media/plg_joomhighslide/graphics/';
  hs.wrapperClassName = 'wide-border';");
  }

  /**
   * This method should set an associative array of attributes for the 'a'-Tag (key/value pairs) which opens the image.
   *
   * <code>
   * $attribs['data-rel']   = 'examplebox';
   * $attribs['data-group'] = $group;
   * </code>
   *
   * The example above will create a link tag like that: <a href="<image URL>"  data-rel="examplebox" group="<image group>" ... >
   *
   * ($attribs is passed by references and should only be filled)
   *
   * By default the attribute 'href' is filled with the URL to the image which shall be opened. You only have to set that
   * attribute if you want to change that (the image URL is passed in the third argument of this method).
   *
   * NOTE!!!: You are not allowed to set the attributes 'title' and 'class' because these are handled internally by JoomGallery.
   *
   * @param   array   $attribs  Associative array of HTML attributes which you have to fill
   * @param   object  $image    An object holding all the relevant data about the image to open
   * @param   string  $img_url  The URL to the image which shall be openend
   * @param   string  $group    The name of an image group, most popup boxes are able to group the images with that
   * @param   string  $type     'orig' for original image, 'img' for detail image or 'thumb' for thumbnail
   * @return  void
   * @since   3.0
   */
  protected function getLinkAttributes(&$attribs, $image, $img_url, $group, $type)
  {
    $attribs['rel'] = 'highslide['.$group.']';
    $attribs['onclick'] ='return hs.expand(this);';
  }
}