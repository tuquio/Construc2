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
		<dt class="name"><?php echo JText::_('COM_USERS_PROFILE_name_LABEL') ?></dt>
		<dd class="name"><?php echo $this->form->getValue('name'); ?></dd>
		<dt class="username"><?php echo JText::_('COM_USERS_PROFILE_username_LABEL') ?></dt>
		<dd class="username"><?php echo $this->form->getValue('username'); ?></dd>
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

	<fieldset class="profile comm">
	<legend><?php echo JText::_('COM_USERS_PROFILE_COMM') ?></legend>
	<dl class="comm">
		<dt class="email1"><?php echo JText::_('COM_USERS_PROFILE_EMAIL1_LABEL') ?></dt>
		<dd class="email1"><?php echo $this->form->getValue('email1'); ?></dd>
		<dt class="phone"><?php echo JText::_('PLG_USER_PROFILE_FIELD_PHONE_LABEL') ?></dt>
		<dd class="phone"><?php echo $this->form->getValue('phone', 'profile'); ?></dd>
		<dt class="website"><?php echo JText::_('PLG_USER_PROFILE_FIELD_WEB_SITE_LABEL') ?></dt>
		<dd class="website"><?php echo $this->form->getValue('website', 'profile'); ?></dd>
	</dl>
	</fieldset>

	<fieldset class="profile address">
	<legend><?php echo JText::_('COM_USERS_PROFILE_ADDRESS') ?></legend>
<? /* @todo: address field ordering.
	For ultimate and accurate l10n the order of these fields should match the user's postal rules.
	Needs a "super-smart template helper" to be asked then, and to take care of this US-centric crap.
*/ ?>
	<dl class="address">
		<dt class="addr1"><?php echo JText::_('PLG_USER_PROFILE_FIELD_ADDRESS1_LABEL') ?></dt>
		<dd class="addr1"><?php echo $this->form->getValue('address1', 'profile'); ?></dd>
		<dt class="addr2"><?php echo JText::_('PLG_USER_PROFILE_FIELD_ADDRESS2_LABEL')  ?></dt>
		<dd class="addr2"><?php echo $this->form->getValue('address2', 'profile'); ?></dd>
<? /*
		<dt class="pcode"><?php echo JText::_('PLG_USER_PROFILE_FIELD_POSTAL_CODE_LABEL') ?></dt>
		<dd class="pcode"><?php echo $this->form->getValue('postal_code', 'profile'); ?></dd>
		<dt class="city"><?php echo JText::_('PLG_USER_PROFILE_FIELD_CITY_LABEL') ?></dt>
		<dd class="city"><?php echo $this->form->getValue('city', 'profile'); ?></dd>
*/ ?>
		<dt class="city"><?php echo JText::_('PLG_USER_PROFILE_FIELD_CITY_ZIP_DESC') ?></dt>
		<dd class="city"><?php echo $this->form->getValue('postal_code', 'profile') ,' ', $this->form->getValue('city', 'profile'); ?></dd>

		<dt class="region"><?php echo JText::_('PLG_USER_PROFILE_FIELD_REGION_LABEL') ?></dt>
		<dd class="region"><?php echo $this->form->getValue('region', 'profile'); ?></dd>
		<dt class="country"><?php echo JText::_('PLG_USER_PROFILE_FIELD_COUNTRY_LABEL') ?></dt>
		<dd class="country"><?php echo $this->form->getValue('country', 'profile'); ?></dd>
	</dl>
	</fieldset>

	<fieldset class="profile about-me">
	<legend><?php echo JText::_('COM_USERS_PROFILE_ABOUT_ME') ?></legend>
	<dl class="about-me">
<? /* @todo: User Profilfelder
	"Lieblingsbuch" ist erstmal weg.
	Hier kommen dann ggf. weitere Profilfelder hinein, wenn geklärt ist
	wie man diese "anlegt" und "welche" überhaupt Sinn ergeben ...
*/ ?>
		<dt class="dob"><?php echo JText::_('PLG_USER_PROFILE_FIELD_DOB_LABEL') ?></dt>
		<dd class="dob"><?php echo JHtml::_('date', $this->form->getValue('dob', 'profile')) ?></dd>
		<dt class="me"><?php echo JText::_('PLG_USER_PROFILE_FIELD_ABOUT_ME_LABEL') ?></dt>
		<dd class="me"><?php echo $this->form->getValue('aboutme', 'profile'); ?></dd>
	</dl>
	</fieldset>

<?php if ($canEdit) { ?>
	<p class="line"><a class="edit-profile" href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id='.(int) $this->data->id);?>">
	<?php echo JText::_('COM_USERS_EDIT_PROFILE') ?></a></p>
<?php } ?>

</div>
