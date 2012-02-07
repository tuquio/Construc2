<?php
 /**
 * $Id$
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$cparams = JComponentHelper::getParams ('com_media');
$pstyle  = $this->params->get('presentation_style');

?>
<article class="line contact-page"><?php
if ($this->params->get('show_page_heading')) { ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')) ?></h1>
<?php
}

if ($this->contact->name && $this->params->get('show_name')) { ?>
	<h2><span class="contact-name"><?php echo $this->contact->name ?></span></h2>
<?php
}

if ($this->params->get('show_contact_category') == 'show_no_link') { ?>
	<h3><span class="contact-category"><?php echo $this->contact->category_title ?></span></h3>
<?php
}

if ($this->params->get('show_contact_category') == 'show_with_link') {
	$contactLink = ContactHelperRoute::getCategoryRoute($this->contact->catid); ?>
	<h3><span class="contact-category"><a href="<?php echo $contactLink; ?>"><?php
	echo $this->escape($this->contact->category_title);
	?></a></span></h3>
<?php
}

if ($this->params->get('show_contact_list') && count($this->contacts) > 1) { ?>
	<form action="#" method="get" name="selectForm" id="selectForm">
	<?php
	echo JText::_('COM_CONTACT_SELECT_CONTACT');
	echo JHtml::_('select.genericlist',  $this->contacts, 'id', 'class="inputbox" onchange="document.location.href = this.value"', 'link', 'name', $this->contact->link);
	?>
	</form>
<?php
}

// start presentation style elements
if ($pstyle != 'plain') {
	echo JHtml::_($pstyle.'.start', 'contact-slider');
	echo JHtml::_($pstyle.'.panel',JText::_('COM_CONTACT_DETAILS'), 'basic-details');
}
else {
	echo '<h3>'. JText::_('COM_CONTACT_DETAILS').'</h3>';
}

if ($this->contact->image && $this->params->get('show_image')) { ?>
	<figure class="contact-image"><?php echo JHtml::_('image',$this->contact->image, JText::_('COM_CONTACT_IMAGE_DETAILS'), array('align' => 'middle')); ?></figure>
<?php
}

if ($this->contact->con_position && $this->params->get('show_position')) { ?>
	<p class="contact-position"><?php echo $this->contact->con_position; ?></p>
<?php
}

echo $this->loadTemplate('address');

if ($this->params->get('allow_vcard')) { ?>
	<p class="contact-vcard"><?php echo JText::_('COM_CONTACT_DOWNLOAD_INFORMATION_AS'); ?>
	<a href="<?php echo JRoute::_('index.php?option=com_contact&amp;view=contact&amp;id='.$this->contact->id . '&amp;format=vcf'); ?>"><?php
		echo JText::_('COM_CONTACT_VCARD');?></a></p>
<?php
}

if ($this->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id))
{
	if ($pstyle != 'plain') {
		echo JHtml::_($pstyle.'.panel', JText::_('COM_CONTACT_EMAIL_FORM'), 'display-form');
	}
	else {
		echo '<h3>'. JText::_('COM_CONTACT_EMAIL_FORM').'</h3>';
	}

	echo $this->loadTemplate('form');
}

if ($this->params->get('show_links'))
{
	echo $this->loadTemplate('links');
}

if ($this->params->get('show_articles') && $this->contact->user_id && $this->contact->articles)
{
	if ($pstyle != 'plain') {
		echo JHtml::_($pstyle.'.panel', JText::_('JGLOBAL_ARTICLES'), 'display-articles');
	}
	else {
		echo '<h3>'. JText::_('JGLOBAL_ARTICLES').'</h3>';
	}

	echo $this->loadTemplate('articles');
}

if ($this->params->get('show_profile') && $this->contact->user_id && JPluginHelper::isEnabled('user', 'profile'))
{
	if ($pstyle != 'plain') {
		echo JHtml::_($pstyle.'.panel', JText::_('COM_CONTACT_PROFILE'), 'display-profile');
	}
	else {
		echo '<h3>'. JText::_('COM_CONTACT_PROFILE').'</h3>';
	}

	echo $this->loadTemplate('profile');
}

if ($this->contact->misc && $this->params->get('show_misc'))
{
	if ($pstyle != 'plain') {
		echo JHtml::_($pstyle.'.panel', JText::_('COM_CONTACT_OTHER_INFORMATION'), 'display-misc');
	}
	else {
		echo '<h3>'. JText::_('COM_CONTACT_OTHER_INFORMATION').'</h3>';
	}
?>
	<div class="contact-miscinfo"><div class="<?php echo $this->params->get('marker_class') ?>"><?php echo $this->params->get('marker_misc') ?></div>
	<div class="contact-misc"><?php echo $this->contact->misc ?></div>

</article><?php
}

// close presentation style elements
if ($pstyle != 'plain') { echo JHtml::_($pstyle.'.end'); } ?>

</div>
<xmp><? print_r($this->contact) ?></xmp>
<xmp><? print_r($this->params) ?></xmp>
