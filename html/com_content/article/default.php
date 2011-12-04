<?php
/**
 * @version		$Id: default.php 17137 2010-05-17 07:00:07Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	Templates.beez5
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT_SITE .DS. 'helpers');

// Create shortcut to parameters.
$params = $this->item->params;

?>
<article class="line item-page">
<?php if ($this->params->get('show_page_heading')) : ?>
	<?php if ($params->get('show_title')) :?><hgroup><?php endif; ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
<?php if ($params->get('show_title')) : ?>
	<h2><?php echo $this->escape($this->item->title); ?></h2>
<?php endif; ?>
<?php if ($params->get('show_title')) :?></hgroup><?php endif; ?>

<?php if ($params->get('access-edit') ||  $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<ul class="menu actions">
	<?php if (!$this->print) : ?>
		<?php if ($params->get('show_print_icon')) : ?>
		<li class="mi print-icon"><?php echo JHtml::_('icon.print_popup',  $this->item, $params); ?></li>
		<?php endif; ?>
		<?php if ($params->get('show_email_icon')) : ?>
		<li class="mi email-icon"><?php echo JHtml::_('icon.email',  $this->item, $params); ?></li>
		<?php endif; ?>
		<?php if ($this->user->authorise('core.edit', 'com_content.article.'.$this->item->id)) : ?>
		<li class="mi edit-icon"><?php echo JHtml::_('icon.edit', $this->item, $params); ?></li>
		<?php endif; ?>
	<?php else : ?>
		<li class="mi"><?php echo JHtml::_('icon.print_screen',  $this->item, $params); ?></li>
	<?php endif; ?>
	</ul>
<?php endif;

if (!$params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif;

echo $this->item->event->beforeDisplayContent;

// splitting introtext and fulltext also allows us to build our own pagenavigation
?><div id="introtext" class="introtext"><?php

if (isset ($this->item->toc)) :
	echo $this->item->toc;
endif;

echo $this->item->introtext;

?></div>
<?php if (!empty($this->item->fulltext)) : ?>
<div id="fulltext" class="fulltext"><?php echo $this->item->fulltext; ?></div>
<?php endif; ?>

<?php

if ( $this->item->prev || $this->item->next ) { ?>
<ul class="hmenu pagenav">
<?php if ($this->item->prev): ?><li class="mi prev"><a class="mi" href="<?= $this->item->prev ?>"><span class="mi"><?= JText::_('Previous Article')?></span></a></li><?php endif; ?>
<?php if ($this->item->next): ?><li class="mi next"><a class="mi" href="<?= $this->item->next ?>"><span class="mi"><?= JText::_('Next Article')?></span></a></li><?php endif; ?>
</ul><?php
}

$useDefList  = ($params->get('show_author') || $params->get('show_category' ) || ($params->get('show_parent_category'))
			|| ($params->get('show_create_date')) || ($params->get('show_modify_date')) || ($params->get('show_publish_date'))
			|| ($params->get('show_hits'))); ?>

<?php if ($useDefList) : ?>
<details class="meta">
	<summary><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></summary>
	<dl class="article-info">
 <?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
	<dt class="createdby"><?php JText::printf('COM_CONTENT_WRITTEN_BY', ''); /* just the label */ ?></dt>
		<dd class="createdby"><?php
	$author =  $this->item->author;
	$author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);
	if (!empty($this->item->contactid ) &&  $params->get('link_author') == true):
		echo JHtml::_('link',JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid), $author);
	else :
		echo $author;
	endif;?></dd>
<?php endif;
 	if ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') : ?>
 	<dt class="parent-category-name"><?php JText::printf('COM_CONTENT_PARENT', ''); ?></dt>
		<dd class="parent-category-name"><?php
	$title = $this->escape($this->item->parent_title);
	if ($params->get('link_parent_category') && $this->item->parent_slug) :
		echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';
	else :
		echo $title;
	endif;
		?></dd>
<?php endif;
	if ($params->get('show_category')) : ?>
	<dt class="category-name"><?php JText::printf('COM_CONTENT_CATEGORY', ''); ?></dt>
		<dd class="category-name"><?php
	$title = $this->escape($this->item->category_title);
	if ($params->get('link_category') ) :
		echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catid)).'">'.$title.'</a>';
	else :
		echo $title;
	endif;
		?></dd>
<?php endif;
	if ($params->get('show_create_date')) : ?>
	<dt class="create"><?php JText::printf('COM_CONTENT_CREATED_DATE_ON', '') ?></dt>
		<dd class="create"><?php echo JHtml::_('date',$this->item->created, JText::_('DATE_FORMAT_LC4')); ?></dd>
<?php endif;
	if ($params->get('show_modify_date')) : ?>
	<dt class="modified"><?php JText::printf('COM_CONTENT_LAST_UPDATED', '') ?></dt>
		<dd class="modified"><?php echo JHtml::_('date',$this->item->modified, JText::_('DATE_FORMAT_LC4')); ?></dd>
<?php endif;
	if ($params->get('show_publish_date')) : ?>
	<dt class="published"><?php JText::printf('COM_CONTENT_PUBLISHED_DATE', '') ?></dt>
		<dd class="published"><?php echo JHtml::_('date',$this->item->publish_up, JText::_('DATE_FORMAT_LC4')); ?></dd>
<?php endif;
	if ($params->get('show_hits')) : ?>
	<dt class="hits"><?php JText::printf('COM_CONTENT_ARTICLE_HITS', '') ?></dt>
		<dd class="hits"><?php echo $this->item->hits; ?></dd>
<?php endif; ?>
	</dl>
</details>
<?php endif; /* $useDefList */ ?>

<?php echo $this->item->event->afterDisplayContent; ?>

</article>
