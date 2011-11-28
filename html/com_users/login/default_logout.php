<?php
/**
 * Custom Override for com_users.logout
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

// understanding booleans helps...
$_desc  = ($this->params->get('logoutdescription_show') && trim($this->params->get('logout_description')));
$_image = (bool)$this->params->get('logout_image');

?>
<div class="line account logout">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>

	<?php if ($_desc || $_image) : ?>
	<div class="line description">
	<?php if ($_desc) : echo $this->params->get('logout_description'); endif; ?>
	<?php if ($_image) :?><img src="<?php echo $this->escape($this->params->get('logout_image')); ?>" class="logout-image" alt="<?php echo JTEXT::_('COM_USER_LOGOUT_IMAGE_ALT')?>"/><?php endif; ?>
	</div>
	<?php endif ; ?>

	<form class="account" action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
		<div class="line button">
		<button type="submit" class="button"><?php echo JText::_('JLOGOUT'); ?></button>
		</div>
		<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('logout_redirect_url',$this->form->getValue('return'))); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
