<?php
/**
 * Custom Override for com_users.reset.complete
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
<div class="account complete">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>

	<form class="form-validate" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.complete'); ?>" method="post">
<?php foreach ($this->form->getFieldsets() as $fieldset): ?>
		<p><?php echo JText::_($fieldset->label); ?></p>
		<fieldset class="complete">
			<dl class="complete">
	<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field): ?>
				<dt class="<?php echo $field->name; ?>"><?php echo $field->label; ?></dt>
				<dd class="<?php echo $field->name; ?>"><?php echo $field->input; ?></dd>
	<?php endforeach; ?>
			</dl>
		</fieldset>
<?php endforeach; ?>

		<div class="button">
		<button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
		</div>
	<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
