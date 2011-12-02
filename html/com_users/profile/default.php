<?php
/**
 * Custom Override for com_users.profile
 *
 * This Layout uses a handcrafted layout of fieldsets and definition lists
 * using semantic classes to allow very granular styling.
 * Any ordering in the "profile.xml" oder "user.xml" field definitions are ignored!
 *
 * @package		Templates
 * @subpackage  Construc2.Overrides
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @todo        Allow reordering of address fields based on country
 */
defined('_JEXEC') or die;

$canEdit = (JFactory::getUser()->id == $this->data->id);
?>
<div class="line account profile">
<?php if ($this->params->get('show_page_heading')) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')) ?></h1>
<?php endif ?>

<?php if ($canEdit) { ?>
	<p class="line"><a class="edit-profile" href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id='.(int) $this->data->id);?>">
	<?php echo JText::_('COM_USERS_EDIT_PROFILE') ?></a></p>
<?php } ?>

	<fieldset class="profile account">
	<legend><?php echo JText::_('COM_USERS_PROFILE_ACCOUNT') ?></legend>
	<!-- names -->
	<dl class="account">
		<dt class="name"><?php echo JText::_('COM_USERS_PROFILE_NAME_LABEL') ?></dt>
		<dd class="name"><?php echo $this->form->getValue('name'); ?></dd>
<?php
/* sooo hackish...
		<dt class="username"><?php echo JText::_('COM_USERS_PROFILE_USERNAME_LABEL') ?></dt>
		<dd class="username"><?php echo $this->form->getValue('username'); ?></dd>
		<dt class="email1"><?php echo JText::_('COM_USERS_PROFILE_EMAIL1_LABEL') ?></dt>
		<dd class="email1"><?php echo $this->form->getValue('email1'); ?></dd>
*/ ?>
		<dt class="username"><?php echo JText::_('COM_USERS_PROFILE_USERNAME_LABEL') ?></dt>
		<dd class="username"><?php echo $this->form->getValue('email1'); ?></dd>
	</dl>
	<!-- dates -->
	<dl class="register">
		<dt class="regdate"><?php echo JText::_('COM_USERS_PROFILE_REGISTERED_DATE_LABEL'); ?></dt>
		<dd class="regdate"><?php echo JHtml::_('date', $this->data->registerDate) ?></dd>
		<dt class="accdate"><?php echo JText::_('COM_USERS_PROFILE_LAST_VISITED_DATE_LABEL'); ?></dt>
		<dd class="accdate"><?php
	// simple check for "empty" SQL date '0000-00-00 ...'
	if ( (int) $this->data->lastvisitDate == 0) {
		echo JText::_('COM_USERS_PROFILE_NEVER_VISITED');
	} else {
		echo JHtml::_('date', $this->data->lastvisitDate);
	}
?>		</dd>
	</dl>
	</fieldset>
<?php
	/* here's a good place to load the subtemplates based on profile fields */
?>
<?php if ($canEdit) { ?>
	<p class="line"><a class="edit-profile" href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id='.(int) $this->data->id);?>">
	<?php echo JText::_('COM_USERS_EDIT_PROFILE') ?></a></p>
<?php } ?>

</div>
