<?php
/**
 * Custom Override for com_users.registration
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');

$this->form->addFieldPath('components/com_deal/models/forms/fields');

$fields = array(
	'email1'    => $this->form->getField('email1'),
	'password1' => $this->form->getField('password1'),
	'password2' => $this->form->getField('password2'),
//	'tos'       => $this->form->getField('tos', 'profile')
);

// Menüitem ID des "AGB" Artikels wie er in der Komponente hinterlegt ist
// @todo sollte besser in den Params des Plugins gesetzt werden, ist aber trickier
$tosid   = (int) JComponenthelper::getParams('com_deal')->get('tos_link', 0);
$mitem   = JFactory::getApplication('site')->getMenu()->getItem($tosid);
$tosLink = $mitem ? $mitem->link . '&tmpl=modal' : null;

$cp = JComponenthelper::getParams('com_deal');

$tosLink = ($tosLink)
		? '<a class="tos-link modal" href="'. JRoute::_($tosLink) .'" rel="{handler: \'iframe\'}">'
			. JText::_('Read our terms of service')
			. '</a>'
		: JText::_('Please set up a page that points to your TOS');
?>
<div class="line account registration">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')) ?></h1>
	<?php endif; ?>
	<form class="form-validate" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register') ?>" method="post">
		<fieldset class="registration account" >
		<legend><?php echo JText::_('COM_USERS_REGISTRATION_DEFAULT_LABEL') ?></legend>
		<!-- registration - preparation -->
		<dl class="account">
		<?php foreach ($fields as $field) : ?>
			<dt class="<?php echo $field->name ?>"><?php echo $field->label ?></dt>
			<dd class="<?php echo $field->name ?>"><?php echo $field->input ?></dd>
		<?php endforeach; ?>
		</dl>
		<p class="tos-link"><?php echo $tosLink; ?></p>
		<p class="tos"><?php echo $this->form->getField('tos', 'profile')->input ?></p>
		</fieldset>
		<div>

		<div class="line button">
		<button type="submit" class="validate"><?php echo JText::_('JREGISTER') ?></button>
		<?php echo JText::_('COM_USERS_OR'); ?>
		<!-- cancel registration - go back to the homepage -->
		<a class="button" href="<?php echo JRoute::_(JUri::base(false)) ?>" title="<?php echo JText::_('JCANCEL') ?>"><?php echo JText::_('JCANCEL') ?></a>
		</div>

		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="registration.register" />
		<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
