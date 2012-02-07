<?php
/**
 * Custom Override for com_contact.form
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
JHtml::_('behavior.tooltip');

$fnames = array('name', 'email', 'subject', 'message');
?>
<?php if (isset($this->error)) { ?><div class="contact-error"><?php echo $this->error ?></div><?php } ?>

<article class="line contact-form">
	<form class="form-validate" id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post">
		<fieldset class="contact">
			<legend class="contact"><?php echo JText::_('COM_CONTACT_FORM_LABEL'); ?></legend>
			<dl class="contact">
<?php foreach ($fnames as $fieldname) {
			$field = $this->form->getField('contact_'.$fieldname); ?>
				<dt class="<?php echo $fieldname ?>"><?php echo $field->label ?></dt>
				<dd class="<?php echo $fieldname ?>"><?php echo $field->input ?></dd>
<?php } ?>
				<dt class="email"><?php echo $this->form->getLabel('contact_email_copy'); ?></dt>
				<dd class="email"><?php echo $this->form->getInput('contact_email_copy'); ?></dd>
<?php

//Dynamically load any additional fields from plugins.
foreach ($this->form->getFieldsets() as $fieldset)
{
	if ($fieldset->name != 'contact')
	{
		foreach ($this->form->getFieldset($fieldset->name) as $field)
		{
			$optional = (!$field->required && $field->type != "Spacer");
			if ($field->hidden) {
				echo $field->input;
			} else { ?>
				<dt class="<?php echo $field->name ?>"><?php
					echo $field->label;
					if ($optional) : ?><span class="optional"><?php echo JText::_('COM_CONTACT_OPTIONAL');?></span><?php endif; ?></dt>
				<dd class="<?php echo $field->name ?>"><?php echo $field->input;?></dd>
<?php		}
		}
	}
}
?>
		</fieldset>

		<div class="line button">
		<button class="validate" type="submit"><span><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></span></button>
		</div>

		<input type="hidden" name="option" value="com_contact" />
		<input type="hidden" name="task" value="contact.submit" />
		<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
		<input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
		<?php echo JHtml::_( 'form.token' ); ?>

	</form>

</article>
