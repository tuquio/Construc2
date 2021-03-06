<?php
/**
 * Custom Override for com_users.login
 *
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

JHtml::_('behavior.framework');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$usersConfig = JComponentHelper::getParams('com_users');

// understanding booleans helps...
$_desc  = ($this->params->get('logindescription_show') && trim($this->params->get('login_description')));
$_image = (bool)$this->params->get('login_image');

?>
<div class="line account login">
	<?php if ($this->params->get('show_page_heading')) { ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')) ?></h1>
	<?php } ?>

	<?php if ($_desc || $_image) { ?>
	<div class="line description">
	<?php if ($_desc) { echo '<p>', $this->params->get('login_description'), '</p>'; } ?>
	<?php if ($_image) { ?><img class="login-image" src="<?php echo $this->escape($this->params->get('login_image')) ?>" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT') ?>"/><?php } ?>
	</div>
	<?php } ?>

	<form class="form-validate" action="<?php echo JRoute::_('index.php?option=com_users&task=user.login') ?>" method="post">
	<fieldset class="login credentials">
		<dl class="credentials">
	<?php foreach ($this->form->getFieldset('credentials') as $field) { ?>
		<?php if (!$field->hidden): ?>
			<dt class="<?php echo $field->name ?>"><?php echo $field->label ?></dt>
			<dd class="<?php echo $field->name ?>"><?php echo $field->input ?></dd>
		<?php endif; ?>
	<?php } ?>
		</dl>
		<div class="line button">
		<button type="submit" class="button"><?php echo JText::_('JLOGIN') ?></button>
		</div>
	</fieldset>
	<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url',$this->form->getValue('return'))) ?>" />
	<?php echo JHtml::_('form.token') ?>
	</form>

	<menu class="menu accountmenu">
	<li class="mi reset"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=reset') ?>"><span class="mi"><?php echo JText::_('COM_USERS_LOGIN_RESET') ?></span></a></li>
	<li class="mi remind"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=remind') ?>"><span class="mi"><?php echo JText::_('COM_USERS_LOGIN_REMIND') ?></span></a></li>
	<?php if ($usersConfig->get('allowUserRegistration')) {
	?><li class="mi register"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=registration') ?>"><span class="mi"><?php echo JText::_('COM_USERS_LOGIN_REGISTER') ?></span></a></li>
	<?php } ?></menu>

</div>
