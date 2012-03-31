<?php defined('_JEXEC') or die;
/**
 * Local variables:
 * $type	: 'login|logout'
 * $return	: redirect URL (or empty = stay on same page after login)
 * $user	: JUser object
 *
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

?>
<?php if ($type == 'logout') { ?>
<form class="form-validate form-login" id="form-login" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')) ?>" method="post">
<?php if ($params->get('greeting')) { ?>
	<div class="line description pre-text">
	<p><?php
	if ($params->get('name') == 0) {
		JText::printf('MOD_LOGIN_HINAME', $user->get('name'));
	} else {
		JText::printf('MOD_LOGIN_HINAME', $user->get('username'));
	} ?></p
	</div>
<?php } ?>

	<div class="line button">
	<button type="submit" class="button"><span><?php echo JText::_('JLOGOUT') ?></span></button>
	</div>

	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.logout" />
	<input type="hidden" name="return" value="<?php echo $return ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<?php
} else {
?>
<form class="form-validate form-login" id="form-login" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')) ?>" method="post">
<?php if ( ($ptext = $params->get('pretext')) ) { ?><div class="line description pre-text"><?php echo $ptext ?></div><?php } ?>
	<fieldset class="login credentials">
		<dl class="credentials">
			<dt class="username"><label for="mod-username" class="required"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label></dt>
			<dd class="username"><input id="mod-username" type="text" name="username" class="inputbox validate-username required" required="required" aria-required="true" /></dd>
			<dt class="password"><label for="mod-password" class="required"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label></dt>
			<dd class="password"><input id="mod-password" type="password" name="password" class="inputbox validate-password required" required="required" aria-required="true" /></dd>
	<?php if (JPluginHelper::isEnabled('system', 'remember')) { ?>
			<dt></dt><dd class="remember">
				<label for="mod-remember">
				<input id="mod-remember" type="checkbox" name="remember" value="yes" />
				<span class="lbl"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></span>
				</label>
			</dd>
	<?php } ?>
		</dl>
	</fieldset>

	<div class="line button">
	<button type="submit" class="validate"><span><?php echo JText::_('JLOGIN') ?></span></button>
	</div>

	<menu class="menu loginmenu">
	<li class="mi reset"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=reset') ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD') ?></span></a></li>
	<li class="mi remind"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=remind') ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME') ?></span></a></li>
	<?php if (JComponentHelper::getParams('com_users')->get('allowUserRegistration')) {
	?><li class="mi register"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=registration') ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_REGISTER') ?></span></a></li>
	<?php } ?>
	</menu>

<?php if ( ($ptext = $params->get('posttext')) ) { ?><div class="line description post-text"><?php echo $ptext ?></div><? } ?>

	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="<?php echo $return ?>" />
	<?php echo JHtml::_('form.token') ?>

</form>
<?php
}
unset($ptext);
