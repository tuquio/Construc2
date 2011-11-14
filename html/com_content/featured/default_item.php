<?php
/**
 * @version		$Id: default_item.php 17816 2010-06-21 13:03:17Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	Templates.beez5
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$templateparams = JFactory::getApplication()->getTemplate(true)->params;

// Create a shortcut for params.
$canEdit	= $this->item->params->get('access-edit');
$params		= $this->item->params;
?>
<?php if ($params->get('show_title')) : ?>
	<h2><?php
	if ($params->get('link_titles') && $params->get('access-view')) :
	?><a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>"><?php
	echo $this->escape($this->item->title);
	?></a><?php
	else :
		echo $this->escape($this->item->title);
	endif; ?></h2>
<?php endif; ?>

<?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
	<ul class="actions">
		<?php if ($params->get('show_print_icon')) : ?>
		<li class="print-icon"><?php echo JHtml::_('icon.print_popup', $this->item, $params); ?></li>
		<?php endif; ?>
		<?php if ($params->get('show_email_icon')) : ?>
		<li class="email-icon"><?php echo JHtml::_('icon.email', $this->item, $params); ?></li>
		<?php endif; ?>
		<?php if ($canEdit) : ?>
		<li class="edit-icon"><?php echo JHtml::_('icon.edit', $this->item, $params); ?></li>
		<?php endif; ?>
	</ul>
<?php endif; ?>

<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
<?php endif;

echo $this->item->event->beforeDisplayContent;

echo $this->item->introtext;

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
<?php endif; /* $useDefList */

if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
	else :
		$menu = JSite::getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		$link = new JURI($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif;
?>
<p class="line readmore"><a href="<?php echo $link; ?>"><?php
	if (!$params->get('access-view')) :
		echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
	elseif ($readmore = $this->item->alternative_readmore) :
		echo $readmore;
		if ($params->get('show_readmore_title', 0) != 0) :
			echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
		endif;
	elseif ($params->get('show_readmore_title', 0) == 0) :
		echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
	else :
		echo JText::_('COM_CONTENT_READ_MORE');
		echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
	endif; ?></a>
</p>
<?php
endif;

echo $this->item->event->afterDisplayContent;
