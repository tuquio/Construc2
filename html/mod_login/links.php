<?php
defined('_JEXEC') or die;
$usersConfig = JComponentHelper::getParams('com_users');

?>
<menu class="menu">
<li class="mi reset"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></span></a></li>
<li class="mi remind"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></span></a></li>
<?php if ($usersConfig->get('allowUserRegistration')) :
?><li class="mi register"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_REGISTER'); ?></span></a></li>
<?php endif; ?>
</menu>
