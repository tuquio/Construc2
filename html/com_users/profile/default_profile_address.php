<?php defined('_JEXEC') or die;
/**
 * Subtemplate Address Details for com_users.profile.default
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
?>
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

		<dt class="pcode"><?php echo JText::_('PLG_USER_PROFILE_FIELD_POSTAL_CODE_LABEL') ?></dt>
		<dd class="pcode"><?php echo $this->form->getValue('postal_code', 'profile'); ?></dd>
		<dt class="city"><?php echo JText::_('PLG_USER_PROFILE_FIELD_CITY_LABEL') ?></dt>
		<dd class="city"><?php echo $this->form->getValue('city', 'profile'); ?></dd>
<?php
/*	this combines POSTAL_CODE and CITY into a more common one-liner for Europe
	requries custom language key PLG_USER_PROFILE_FIELD_CITY_ZIP_DESC="City"

		<dt class="city"><?php echo JText::_('PLG_USER_PROFILE_FIELD_CITY_ZIP_DESC') ?></dt>
		<dd class="city"><?php echo $this->form->getValue('postal_code', 'profile') ,' ', $this->form->getValue('city', 'profile'); ?></dd>
*/ ?>
		<dt class="region"><?php echo JText::_('PLG_USER_PROFILE_FIELD_REGION_LABEL') ?></dt>
		<dd class="region"><?php echo $this->form->getValue('region', 'profile'); ?></dd>
		<dt class="country"><?php echo JText::_('PLG_USER_PROFILE_FIELD_COUNTRY_LABEL') ?></dt>
		<dd class="country"><?php echo $this->form->getValue('country', 'profile'); ?></dd>
	</dl>
	</fieldset>
