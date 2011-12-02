<?php
/**
 * Custom Override for com_users.profile.edit
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<div class="line account profile-edit">
<?php if ($this->params->get('show_page_heading')) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>

<form class="form-validate" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>" method="post" enctype="multipart/form-data">

	<fieldset class="profile-edit account">
	<legend><?php echo JText::_('COM_USERS_PROFILE_ACCOUNT') ?></legend>

	<!-- account details -->
	<dl class="account">
		<dt class="name"><?php echo $this->form->getLabel('name'); ?></dt>
		<dd class="name"><?php echo $this->form->getInput('name'); ?></dd>
		<dt class="username"><?php echo $this->form->getLabel('username'); ?></dt>
		<dd class="username"><?php echo $this->form->getInput('username'); ?></dd>
<?php
/* sooo hackish...  REQUIRES "PLG_SYSTEM_CUPONDO"
		<dt class="email"><?php echo $this->form->getLabel('email1'); ?></dt>
		<dd class="email"><?php echo $this->form->getInput('email1'); ?></dd>
*/ ?>
		<dt class="email"><?php echo $this->form->getLabel('email2'); ?></dt>
		<dd class="email"><?php echo $this->form->getInput('email2'); ?></dd>

		<dt class="password"><?php echo $this->form->getLabel('password1'); ?></dt>
		<dd class="password"><?php echo $this->form->getInput('password1'); ?></dd>
		<dt class="password"><?php echo $this->form->getLabel('password2'); ?></dt>
		<dd class="password"><?php echo $this->form->getInput('password2'); ?></dd>
	</dl>
	</fieldset>
<?php
	/* here's a good place to load the subtemplates based on profile fields */
?>
	<div class="line button">
		<button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
		<?php echo JText::_('COM_USERS_OR'); ?>
		<a href="<?php echo JRoute::_('index.php?option=com_users&view=profile'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="profile.save" />
	<?php echo JHtml::_('form.token');?>
	</div>
</form>
</div>
