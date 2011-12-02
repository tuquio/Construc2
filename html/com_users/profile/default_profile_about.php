<?php defined('_JEXEC') or die;
/**
 * Subtemplate Personal Info for com_users.profile.default
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @todo add remaining and configurable profile fields beyond 'doc' and 'aboutme'
 */
?>

	<fieldset class="profile about-me">
	<legend><?php echo JText::_('COM_USERS_PROFILE_ABOUT_ME') ?></legend>
	<dl class="about-me">
<?php /* add remaining profile fields */ ?>
		<dt class="dob"><?php echo JText::_('PLG_USER_PROFILE_FIELD_DOB_LABEL') ?></dt>
		<dd class="dob"><?php echo JHtml::_('date', $this->form->getValue('dob', 'profile')) ?></dd>
		<dt class="me"><?php echo JText::_('PLG_USER_PROFILE_FIELD_ABOUT_ME_LABEL') ?></dt>
		<dd class="me"><?php echo $this->form->getValue('aboutme', 'profile'); ?></dd>
	</dl>
	</fieldset>
