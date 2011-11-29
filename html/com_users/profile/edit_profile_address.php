<?php defined('_JEXEC') or die;
/**
 * Subtemplate Address Details for com_users.profile.edit
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @todo: address field ordering.
 * For ultimate and accurate l10n the order of these fields should match
 * the user's postal rules. Needs a "super-smart template helper" to be asked
 * then, and to take care of this US-centric crap.
 */
?>
	<!-- registration - address  -->
	<fieldset class="registration address">
	<legend><?php echo JText::_('COM_USERS_PROFILE_ADDRESS'); ?></legend>
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
