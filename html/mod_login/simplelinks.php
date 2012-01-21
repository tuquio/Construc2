<?php defined('_JEXEC') or die;
/**
 * Links for "Login", "Password reset" and "Registration".
 */
$usersConfig = JComponentHelper::getParams('com_users');

?>
<menu class="menu">
<li class="mi login"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=login'); ?>"><span class="mi"><?php echo JText::_('Login'); ?></span></a></li>
<?php if ($usersConfig->get('allowUserRegistration')) : ?>
<li class="mi register"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_REGISTER'); ?></span></a></li>
<?php endif; ?>
<li class="mi reset"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></span></a></li>
</menu>
