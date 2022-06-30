<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php if($type == 'logout') : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login">
<?php if ($params->get('greeting')) : ?>
    <br/>
        <div style="padding:0 30px"  >
        <?php if ($params->get('name')) : {
                echo sprintf( _JSHOP_HINAME, $user->get('name') );
        } else : {
                echo sprintf( _JSHOP_HINAME, $user->get('username') );
        } endif; ?>

         <input   style="margin:0 0px"  type="submit" name="Submit" class="button" value="<?php print JText::_('LOGOUT') ?>" />
                <a  href="/index.php?option=com_jshopping&view=user&task=orders&Itemid=186">Личный кабинет</a>
        </div>
<?php endif; ?>





        <input type="hidden" name="option" value="com_users" />
        <input type="hidden" name="task" value="user.logout" />
        <input type="hidden" name="return" value="<?php echo $return; ?>" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php else : ?>
<?php if(JPluginHelper::isEnabled('authentication', 'openid')) :
                $lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
                $langScript =         'var JLanguage = {};'.
                                                ' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
                                                ' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
                                                ' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
                                                ' var modlogin = 1;';
                $document = JFactory::getDocument();
                $document->addScriptDeclaration( $langScript );
                JHTML::_('script', 'openid.js');
endif; ?>
<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" class="form-inline"  >
        <?php echo $params->get('pretext'); ?>

  <!-- 1  -->
         <table style="width:100%; border:0px solid green"  >
        <tr>
          <td>
            <p id="form-login-username">
                <label for="modlgn_username"><?php echo JText::_('USERNAME') ?></label>
                <input id="modlgn_username" type="text" name="username" class="inputbox"  size="18" />
            </p>
          </td>

          <td>
              <p id="form-login-password">
                <label for="modlgn_passwd"><?php echo JText::_('PASSWORD') ?></label>
                <input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18"  />
             </p>
          </td>
        </tr>
        </table   >


 <!-- 2  -->
        <table style="width:100%; border:0px solid green"  >

         <tr>
          <td  style="width:80%;border:0px solid green"   >
            <input type="submit" name="Submit" class="button" value="Войти" />
          </td>

           <td  style="width:20%; display:-none" >
                       <?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
                   <div id="form-login-remember" class="control-group checkbox">
                   <label class="control-label" for="modlgn-remember"><?php echo JText::_('REMEMBER_ME') ?></label>
                   <input id="modlgn-remember" class="inputbox" type="checkbox" value="yes" name="remember">
                   </div>
                   <?php endif; ?>
          </td>
        </tr>
        </table>


        <!-- 3  -->
<table style="width:100%; border:0px solid green"   >
        <tr>
          <td style="width:50%;  border:0px solid green  "  >
              <a href="<?php echo JRoute::_( 'index.php?option=com_users&view=reset'); ?>"><?php print JText::_('LOST_PASSWORD') ?></a>

          </td>
          <td style="width:50%;  border:0px solid green  "  >

           <?php
        $usersConfig = JComponentHelper::getParams( 'com_users' );
        if ($usersConfig->get('allowUserRegistration')) : ?>
        <div>
                <a href="<?php echo SEFLink('index.php?option=com_jshopping&controller=user&task=register', 1); ?>"><?php print JText::_('REGISTRATION') ?></a>
        </div>
        <?php endif; ?>

          </td>


          <td>


          </td>
  </tr>
</table>

        <?php echo $params->get('posttext'); ?>



    <?php /*<div>
            <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>"><?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
    </div> */ ?>


        <input type="hidden" name="option" value="com_jshopping" />
    <input type="hidden" name="controller" value="user" />
        <input type="hidden" name="task" value="loginsave" />
        <input type="hidden" name="return" value="<?php echo $return; ?>" />
        <?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php endif; ?>
