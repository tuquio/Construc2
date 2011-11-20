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
	<legend><?php echo JText::sprintf('COM_USERS_PROFILE_EDIT_LABEL', $this->form->getValue('username')) ?></legend>

	<!-- registration - preparation -->
	<dl class="account">
		<dt class="name"><?php echo $this->form->getLabel('name'); ?></dt>
		<dd class="name"><?php echo $this->form->getInput('name'); ?></dd>
		<dt class="username hidden"><?php echo $this->form->getLabel('username'); ?></dt>
		<dd class="username hidden"><?php echo $this->form->getInput('username'); ?></dd>

		<dt class="email"><?php echo $this->form->getLabel('email1'); ?></dt>
		<dd class="email"><?php echo $this->form->getInput('email1'); ?></dd>
		<dt class="email"><?php echo $this->form->getLabel('email2'); ?></dt>
		<dd class="email"><?php echo $this->form->getInput('email2'); ?></dd>

		<dt class="password"><?php echo $this->form->getLabel('password1'); ?></dt>
		<dd class="password"><?php echo $this->form->getInput('password1'); ?></dd>
		<dt class="password"><?php echo $this->form->getLabel('password2'); ?></dt>
		<dd class="password"><?php echo $this->form->getInput('password2'); ?></dd>
	</dl>
	</fieldset>

	<!-- registration - communication  -->
	<fieldset class="registration comm">
	<legend><?php echo JText::_('COM_USERS_PROFILE_COMM'); ?></legend>
	<dl class="comm">
		<dt class="phone"><?php echo $this->form->getLabel('phone', 'profile'); ?></dt>
		<dd class="phone"><?php echo $this->form->getInput('phone', 'profile'); ?></dd>
		<dt class="website"><?php echo $this->form->getLabel('website', 'profile'); ?></dt>
		<dd class="website"><?php echo $this->form->getInput('website', 'profile'); ?></dd>
	</dl>
	</fieldset>

	<!-- registration - address  -->
	<fieldset class="registration address">
	<legend><?php echo JText::_('COM_USERS_PROFILE_ADDRESS'); ?></legend>
<?php /* @todo: address field ordering.
	For ultimate and accurate l10n the order of these fields should match the user's postal rules.
	Needs a "super-smart template helper" to be asked then, and to take care of this US-centric crap.
*/ ?>
	<dl class="address">
		<dt class="address"><?php echo $this->form->getLabel('address1', 'profile'); ?></dt>
		<dd class="address"><?php echo $this->form->getInput('address1', 'profile'); ?></dd>
		<dt class="address"><?php echo $this->form->getLabel('address2', 'profile'); ?></dt>
		<dd class="address"><?php echo $this->form->getInput('address2', 'profile'); ?></dd>

		<dt class="pcode"><?php echo $this->form->getLabel('postal_code', 'profile'); ?></dt>
		<dd class="pcode"><?php echo $this->form->getInput('postal_code', 'profile'); ?></dd>
		<dt class="city"><?php echo $this->form->getLabel('city', 'profile'); ?></dt>
		<dd class="city"><?php echo $this->form->getInput('city', 'profile'); ?></dd>

		<dt class="region"><?php echo $this->form->getLabel('region', 'profile'); ?></dt>
		<dd class="region"><?php echo $this->form->getInput('region', 'profile'); ?></dd>
		<dt class="country"><?php echo $this->form->getLabel('country', 'profile'); ?></dt>
		<dd class="country"><?php echo $this->form->getInput('country', 'profile'); ?></dd>

	</dl>
	</fieldset>
        <fieldset class="profile-edit tos" >
        	<dl class="about-me">
<? /* @todo: User Profilfelder
	"Lieblingsbuch" ist erstmal weg.
	Hier kommen dann ggf. weitere Profilfelder hinein, wenn geklärt ist
	wie man diese "anlegt" und "welche" überhaupt Sinn ergeben ...
*/ ?>
		<dt class="dob"><?php echo $this->form->getLabel('dob','profile'); ?></dt>
		<dd class="dob"><?php echo $this->form->getInput('dob','profile'); ?></dd>
		<dt class="aboutme"><?php echo $this->form->getLabel('aboutme', 'profile') ?></dt>
		<dd class="aboutme"><?php echo $this->form->getInput('aboutme', 'profile');; ?></dd>
	</dl>
	</fieldset>

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
