<?php defined('_JEXEC') or die;
/**
 * Subtemplate Communication Details for com_users.profile.edit
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
?>	<!-- registration - communication  -->
	<fieldset class="registration comm">
	<legend><?php echo JText::_('COM_USERS_PROFILE_COMM'); ?></legend>
	<dl class="comm">
		<dt class="phone"><?php echo $this->form->getLabel('phone', 'profile'); ?></dt>
		<dd class="phone"><?php echo $this->form->getInput('phone', 'profile'); ?></dd>
		<dt class="website"><?php echo $this->form->getLabel('website', 'profile'); ?></dt>
		<dd class="website"><?php echo $this->form->getInput('website', 'profile'); ?></dd>
	</dl>
	</fieldset>
