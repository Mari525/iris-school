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


// init $tpl helper
require dirname(__FILE__) . '/php/init.php';

?><?php echo $tpl->renderHTML(); ?>
<head>
    <jdoc:include type="head"/>
</head>
<body class="<?php echo $tpl->getBodyClasses(); ?>" id="page-print">

    <div class="component-wrapper">
        <jdoc:include type="message" />
        <jdoc:include type="component" />
    </div>

    <?php if ($tpl->request('print')): ?>
        <script type="text/javascript">window.print();</script>
    <?php endif; ?>

<style>
 h1, div.jg-header  {     font-size:29px; line-height:35px;   margin:10px 0; font-weight:400;  border-bottom:none;  }
 h2                 {     font-size:28px; line-height:39px;   margin:10px 0; font-weight:400;  }
 div.item-page h3                 {     font-size:26px; line-height:32px;   margin:10px 0; font-weight:400;    }
 h4                 {     font-size:26px; line-height:32px;   margin:10px 0; font-weight:400;    }
 h5                 {     font-size:23px; line-height:29px;   margin:10px 0; font-weight:400;    }
 h6                 {     font-size:20px; line-height:26px;   margin:10px 0; font-weight:800;  }
  div.item-page p, div.item-page ul li,   div.item-page div
 {   padding:0; font-size:15px; line-height:22px; text-align:justify;  }
      div.item-page div.item-image  {float:left;   width:360px;   margin:10px 20px 10px 0}
      div.item-page div.item-image img  { width:100%; }

 div.item-page table td, div.item-page table th {padding:10px; vertical-align:top;  }

 @media screen  and (orientation:portrait)
 {
       div.item-page div.item-image {float: none;  clear:both; width: 100%; margin:auto}
       div.item-page div.item-image img {float: none;  clear:both; width: 100%; margin:auto} 
  }

</style>

</body></html>
