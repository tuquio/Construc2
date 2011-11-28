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

$fields = array(
	'name'      => $this->form->getField('name'),
	'email1'    => $this->form->getField('email1'),
	'password1' => $this->form->getField('password1'),
	'password2' => $this->form->getField('password2'),
);

// Menüitem ID des "AGB" Artikels wie er im Plugin hinterlegt ist
// und freundlicherweise vom TOS feld durchgereicht wird ;)
$tosLink = $this->form->getField('tos', 'profile')->link;

//#FIXME die fixe Größe des IFrame ist ärgerlich und kann leider
//	nicht in % angegeben werden, wodurch es das Popup auf Small-Screens
//	aus dem Bildschirm haut. Angeblich kann die SqueezeBox auch mit
//	Event-Handler erweitert werden, über den die finale Größe ggf.
//	relativ zum verfügbaren Fensterbereich justiert werden kann.
$tosLink = ($tosLink)
		? '<a class="tos-link modal" href="'. JRoute::_($tosLink . '&tmpl=modal', false) .'" rel="{handler:\'iframe\'}">'
			. JText::_('Read our terms of service')
			. '</a>'
		: JText::_('TOS Link unavailable');
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
		<p><?php echo JText::_('Marked fields are required') ?></p>
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
