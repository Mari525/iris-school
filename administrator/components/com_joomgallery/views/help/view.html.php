<?php
/****************************************************************************************\
**   JoomGallery 3                                                                      **
**   By: JoomGallery::ProjectTeam                                                       **
**   Copyright (C) 2008 - 2021  JoomGallery::ProjectTeam                                **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                            **
**   Released under GNU GPL Public License                                              **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look                       **
**   at administrator/components/com_joomgallery/LICENSE.TXT                            **
\****************************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * HTML View class for the help view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewHelp extends JoomGalleryView
{
  /**
   * HTML view display method
   *
   * @param   string  $tpl  The name of the template file to parse
   * @return  void
   * @since   1.5.5
   */
  public function display($tpl = null)
  {
    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_HLPIFO_HELP_MANAGER'), 'info');

    $languages = array( 'de-DE-formal'    => array( 'translator'    => 'JoomGallery::ProjectTeam de-DE (formal)',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/223-the-german-formal-language-files.html',
                                                    'flag'          => 'de.png',
                                                    'type'          => 'formal'),
                        'de-DE-informal'  => array( 'translator'    => 'JoomGallery::ProjectTeam de-DE (informal)',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/222-the-german-informal-language-files.html',
                                                    'flag'          => 'de.png'),
                        'ar-AA'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Arabic ar-AA',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/234-the-arabic-unitag-language-files.html',
                                                    'flag'          => 'sy.png'),
                        'bs-BA'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Bosnian (Bosnia and Herzegovina) bs-BA',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/233-the-bosnian-language-files.html',
                                                    'flag'          => 'ba.png'),
                        'bg-BG'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Bulgarian (Bulgaria) bg-BG',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/232-the-bulgarian-language-files.html',
                                                    'flag'          => 'bg.png'),
                        'ca-ES'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Catalan ca-ES',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/246-the-catalan-language-files.html',
                                                    'flag'          => 'ct.png'),
                        'zh-CN'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Chinese (China) zh-CN',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/231-the-chinese-simplified-language-files.html',
                                                    'flag'          => 'cn.png'),
                        'zh-TW'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Chinese (Taiwan) zh-TW',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/230-the-chinese-traditional-language-files.html',
                                                    'flag'          => 'cn.png'),
                        'hr-HR'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Croatian (Croatia) hr-HR',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/229-the-croatian-language-files.html',
                                                    'flag'          => 'hr.png'),
                        'cs-CZ'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Czech (Czech Republic) cs-CZ',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/228-the-czech-language-files.html',
                                                    'flag'          => 'cz.png'),
                        'da-DK'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Danish (Denmark) da-DK',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/227-the-danish-language-files.html',
                                                    'flag'          => 'dk.png'),
                        'nl-NL'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Dutch (Netherlands) nl-NL',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/226-the-dutch-language-files.html',
                                                    'flag'          => 'nl.png'),
                        'et-EE'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Estonian (Estonia) et-EE',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/247-the-estonian-language-files.html',
                                                    'flag'          => 'ee.png'),
                        'fi-FI'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Finnish (Finland) fi-FI',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/225-the-finnish-language-files.html',
                                                    'flag'          => 'fi.png'),
                        'fr-FR'           => array( 'translator'    => 'JoomGallery::TranslationTeam::French (France) fr-FR',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/224-the-french-language-files.html',
                                                    'flag'          => 'fr.png'),
                        'el-GR'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Greek (Greece) el-GR',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/221-the-greek-language-files.html',
                                                    'flag'          => 'gr.png'),
                        'hu-HU-formal'    => array( 'translator'    => 'JoomGallery::TranslationTeam::Hungarian (Hungary) hu-HU (formal)',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/220-the-hungarian-formal-language-files.html',
                                                    'flag'          => 'hu.png'),
                        'hu-HU-informal'  => array( 'translator'    => 'JoomGallery::TranslationTeam::Hungarian (Hungary) hu-HU (informal)',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/219-the-hungarian-informal-language-files.html',
                                                    'flag'          => 'hu.png'),
                        'id-ID'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Indonesian (Indonesia) id-ID',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/248-the-indonesian-language-files.html',
                                                    'flag'          => 'id.png'),
                        'it-IT'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Italian (Italy) it-IT',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/218-the-italian-language-files.html',
                                                    'flag'          => 'it.png'),
                        'ja-JP'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Japanese (Japan) ja-JP',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/217-the-japanese-language-files.html',
                                                    'flag'          => 'jp.png'),
                        'lt-LT'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Lithuanian (Lithuania) lt-LT',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/215-the-lithuanian-language-files.html',
                                                    'flag'          => 'lt.png'),
                        'lv-LV'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Latvian (Latvia) lv-LV',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/216-the-latvian-language-files.html',
                                                    'flag'          => 'lv.png'),
                        'nb-NO'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Norwegian Bokm??l (Norway) nb-NO',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/214-the-norwegian-language-files.html',
                                                    'flag'          => 'no.png'),
                        'fa-IR'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Persian (Iran) fa-IR',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/213-the-persian-language-files.html',
                                                    'flag'          => 'ir.png'),
                        'pl-PL'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Polish (Poland) pl-PL',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/212-the-polish-language-files.html',
                                                    'flag'          => 'pl.png'),
                        'pt-BR'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Portuguese (Brazil) pt-BR',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/211-the-portuguese-brazilian-language-files.html',
                                                    'flag'          => 'br.png'),
                        'pt-PT'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Portuguese (Portugal) pt-PT',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/210-the-portuguese-language-files.html',
                                                    'flag'          => 'pt.png'),
                        'ru-RU'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Russian (Russia) ru-RU',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/209-the-russian-language-files.html',
                                                    'flag'          => 'ru.png'),
                        'sr-RS'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Serbian (Serbia) sr-RS',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/208-the-serbian-cyrillic-language-files.html',
                                                    'flag'          => 'rs.png'),
                        'sr-YU'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Serbian sr-YU',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/207-the-serbian-language-files.html',
                                                    'flag'          => 'rs.png'),
                        'sk-SK'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Slovak (Slovakia) sk-SK',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/206-the-slovak-language-files.html',
                                                    'flag'          => 'sk.png'),
                        'sl-SI'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Slovenian (Slovenia) sl-SI',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/205-the-slovenian-language-files.html',
                                                    'flag'          => 'si.png'),
                        'es-ES'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Spanish (Spain) es-ES',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/204-the-spanish-language-files.html',
                                                    'flag'          => 'es.png'),
                        'sv-SE'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Swedish (Sweden) sv-SE',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/203-the-swedish-language-files.html',
                                                    'flag'          => 'se.png'),
                        'tr-TR'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Turkish (Turkey) tr-TR',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/202-the-turkish-language-files.html',
                                                    'flag'          => 'tr.png'),
                        'uk-UA'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Ukrainian (Ukraine) uk-UA',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/201-the-ukrainian-language-files.html',
                                                    'flag'          => 'ua.png'),
                        'vi-VN'           => array( 'translator'    => 'JoomGallery::TranslationTeam::Vietnamese (Viet Nam) vi-VN',
                                                    'downloadlink'  => 'https://www.en.joomgalleryfriends.net/downloads/download/39-languages/200-the-vietnamese-language-files.html',
                                                    'flag'          => 'vn.png')
                      );

    $credits  = array(array('title'   => 'Joomla!',
                            'author'  => '',
                            'link'    => 'https://www.joomla.org'),
                      array('title'   => 'jQuery Thumbnail Scroller - Detail view',
                            'author'  => 'Manos Malihutsakis',
                            'link'    => 'http://manos.malihu.gr/jquery-thumbnail-scroller'),
                      array('title'   => 'Slimbox (modified) - Detail and Category view',
                            'author'  => 'Christophe Beyls',
                            'link'    => 'http://www.digitalia.be/software/slimbox'),
                      array('title'   => 'Thickbox3.1 (modified) - Detail and Category view',
                            'author'  => 'Cody Lindley',
                            'link'    => 'http://www.codylindley.com'),
                      array('title'   => 'ImageMagick',
                            'author'  => 'ImageMagick Studio LLC',
                            'link'    => 'http://www.imagemagick.org/script/index.php'),
                      array('title'   => 'Jupload - Java Applet for uploading',
                            'author'  => 'Etienne Gauthier',
                            'link'    => 'http://jupload.sourceforge.net/'),
                      array('title'   => 'Watermark (modified)',
                            'author'  => 'Michael Mueller',
                            'link'    => ''),
                      array('title'   => 'fastimagecopyresampled (fast conversion of pictures in GD)',
                            'author'  => 'Tim Eckel',
                            'link'    => 'http://de.php.net/manual/en/function.imagecopyresampled.php#77679'),
                      array('title'   => 'Wonderful Icons',
                            'author'  => 'Mark James',
                            'link'    => 'http://www.famfamfam.com'),
                      array('title'   => 'Smoothgallery (modified) slideshow in detail view',
                            'author'  => 'Jonathan Schemoul',
                            'link'    => ''),
                      array('title'   => 'Resize Image with Different Aspect Ratio - resizing thumbnails',
                            'author'  => 'Nash',
                            'link'    => ''),
                      array('title'   => 'Weighted rating according to Thomas Bayes',
                            'author'  => 'Michael Ja??ek',
                            'link'    => 'http://www.buntesuppe.de/blog/123/bayessche-bewertung'),
                      array('title'   => 'Fine Uploader',
                            'author'  => 'Ray Nicholus, Andrew Valums',
                            'link'    => 'http://fineuploader.com'),
                      array('title'   => 'GifCreator and GifFrameExtractor',
                            'author'  => 'Cl??ment Guillemain',
                            'link'    => 'https://github.com/Sybio'),
                      array('title'   => 'Copy IPTC and EXIF data of a JPG from source to destination image',
                            'author'  => 'ebashkoff',
                            'link'    => 'https://www.php.net/manual/de/function.iptcembed.php'),
                      array('title'   => 'Decode/Encode IFD fields',
                            'author'  => 'Evan Hunter',
                            'link'    => 'https://www.ozhiker.com/electronics/pjmt/index.html')
                     );

    $params = JComponentHelper::getParams('com_joomgallery');
    if($this->_config->get('jg_checkupdate') && extension_loaded('curl'))
    {
      $params->set('autoinstall_possible', 1);
    }

    $this->languages  = $languages;
    $this->credits    = $credits;
    $this->params     = $params;

    $this->sidebar = JHtmlSidebar::render();

    parent::display($tpl);
  }
}
