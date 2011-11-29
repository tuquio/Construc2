<?php defined('_JEXEC') or die;
/**
 * Subtemplate Personal Info for com_users.profile.edit
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
?>
	<!-- profile - about me -->
	<fieldset class="profile-edit about-me" >
	<dl class="about-me">
<? /* @todo: mehr sinnlose Profilfelder? */ ?>
		<dt class="dob"><?php echo $this->form->getLabel('dob','profile'); ?></dt>
		<dd class="dob"><?php echo $this->form->getInput('dob','profile'); ?></dd>
		<dt class="aboutme"><?php echo $this->form->getLabel('aboutme', 'profile') ?></dt>
		<dd class="aboutme"><?php echo $this->form->getInput('aboutme', 'profile');; ?></dd>
	</dl>
	</fieldset>
