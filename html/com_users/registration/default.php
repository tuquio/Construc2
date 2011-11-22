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
	$this->form->getField('email1'),
	$this->form->getField('password1'),
	$this->form->getField('password2'),
	$this->form->getField('tos', 'profile')
);

// Menüitem ID des "AGB" Artikels wie er in der Komponente hinterlegt ist
// @todo sollte besser in den Params des Plugins gesetzt werden, ist aber trickier
$tosLink = (int) JComponenthelper::getParams('com_deal')->get('tos_link', 0);

$cp = JComponenthelper::getParams('com_deal');

$tosLink = ($tosLink > 0)
		? '<a class="tos-link modal" href="'. JRoute::_('index.php?option=com_content&tmpl=modal&Itemid='.$tosLink) .'" rel="{handler: \'iframe\', size: {x:800, y:600}}">'.JText::_('Read the terms of service').'</a>'
		: JText::_('Please set up a page that points to your TOS');
?>
<div class="registration">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')) ?></h1>
	<?php endif; ?>
	<form class="form-validate" id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register') ?>" method="post">
		<?php if (count($fields)) : ?>
		<fieldset>
			<legend><?php echo JText::_('Registration') ?></legend>
			<dl>
			<?php foreach ($fields as $field) : ?>
				<dt><?php echo $field->label; ?></dt>
				<dd><?php echo $field->input; ?></dd>
			<?php endforeach; ?>
			</dl>
			<p class="tos-link"><?php echo $tosLink; ?></p>
		</fieldset>
		<?php endif; ?>
		<div>
		<button type="submit" class="validate"><?php echo JText::_('JREGISTER') ?></button>
		<?php echo JText::_('COM_USERS_OR'); ?>
		<!-- cancel registration - go back to the homepage -->
		<a href="<?php echo JRoute::_(JUri::base(false)) ?>" title="<?php echo JText::_('JCANCEL') ?>"><?php echo JText::_('JCANCEL') ?></a>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="registration.register" />
		<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
