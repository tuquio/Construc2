<?php defined('_JEXEC') or die;
/**
 * Article and publishing properties.
 * This shared layout is optionally included by
 * - /article/default.php
 * - /featured/default_item.php
 * - /category/blog_item.php
 * any of which also set the variables $showLabels, $showMeta, and $showDates
 * depending on the associated params.
 *
 * @package     Template
 * @subpackage  Overrides
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2012 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
?>

<details class="meta" title="<?php echo JText::_('COM_CONTENT_ARTICLE_INFO') ?>">
	<summary tabindex="-1"><?php echo $this->item->title ?></summary>
	<dl class="article-info">
<?php
if ($showLabels) {
	if ($params->get('show_author') && !empty($this->item->author )) {
?>
	<dt class="createdby"><?php JText::printf('COM_CONTENT_WRITTEN_BY', ''); /* just the label */ ?></dt>
	<dd class="createdby"><?php
	$author =  $this->item->author;
	$author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);
	if (!empty($this->item->contactid ) &&  $params->get('link_author') == true) {
		echo JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid), $author);
	} else {
		echo $author;
	}
	?></dd>
<?php
	}

/* the $parent_slug property is not available in all layouts */
	if ($params->get('show_parent_category')) {
		if (isset($this->item->parent_id) && $this->item->parent_id > 1) {
?>
	<dt class="parent-category"><?php JText::printf('COM_CONTENT_PARENT', '') /* just the label */ ?></dt>
	<dd class="parent-category"><?php
	$title = $this->escape($this->item->parent_title);
	if ($params->get('link_parent_category')) {
		echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_id)).'">'.$title.'</a>';
	} else {
		echo $title;
	} ?>
	</dd>
<?php
		}
	}

	if ($params->get('show_category')) {
?>
	<dt class="category" title="<?php echo JText::printf('COM_CONTENT_CATEGORY', '') ?>"><?php echo JText::plural('COM_CONTENT_CATEGORY', (int)$params->get('show_parent_category')) /* use _0 and _1 alternate labels */ ?></dt>
	<dd class="category"><?php
	$title = $this->escape($this->item->category_title);
	if ($params->get('link_category') ) {
		echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catid)).'">'.$title.'</a>';
	} else {
		echo $title;
	} ?>
	</dd>
<?php
	}
}

if ($showMeta) {
	if ($params->get('show_hits')) {
?>
	<dt class="hits"><?php JText::printf('COM_CONTENT_ARTICLE_HITS', '') /* just the label */ ?></dt>
	<dd class="hits"><?php echo $this->item->hits ?></dd>
<?php
	}
}

/* The W3C doesn't event mention the `pubdate` attribute whereas the WHATWG
 * explains in great details its proper use and scope:
 *
 * The pubdate attribute is a boolean attribute. If specified, it indicates that
 * the date and time given by the element is the publication date and time of the
 * nearest ancestor article element, or, if the element has no ancestor article
 * element, of the document as a whole. If the element has a pubdate attribute s
 * pecified, then the element needs a date. For each article element, there must
 * no more than one time element with a pubdate attribute whose nearest ancestor
 * is that article element. Furthermore, for each Document, there must be no more
 * than one time element with a pubdate attribute that does not have an ancestor
 * article element.
 * @link http://developers.whatwg.org/text-level-semantics.html#the-time-element
 *
 * You should not expect a complete IDL interface for the DOM model of this element
 * in some browsers.
 */

if ($showDates) {
	if ($params->get('show_create_date')) {
?>
	<dt class="created"><?php JText::printf('COM_CONTENT_CREATED_DATE_ON', '') /* just the label */ ?></dt>
	<dd class="created"><time datetime="<?php echo JHtml::_('date', $this->item->created, 'W3C_DATE') ?>"><?php echo JHtml::_('date', $this->item->created, 'DATE_FORMAT_LC1') ?></time></dd>
<?php
	}

	if ($params->get('show_publish_date')) {
?>
	<dt class="published"><?php JText::printf('COM_CONTENT_PUBLISHED_DATE', '') /* just the label */ ?></dt>
	<dd class="published"><time datetime="<?php echo JHtml::_('date', $this->item->publish_up, 'W3C_DATE') ?>" pubdate><?php echo JHtml::_('date', $this->item->publish_up, 'DATE_FORMAT_LC1') ?></time></dd>
<?php
	}

	if ($params->get('show_modify_date')) {
?>
	<dt class="modified"><?php JText::printf('COM_CONTENT_LAST_UPDATED', '') /* just the label */ ?></dt>
	<dd class="modified"><time datetime="<?php echo JHtml::_('date', $this->item->modified, 'W3C_DATE') ?>"><?php echo JHtml::_('date', $this->item->modified, 'DATE_FORMAT_LC1') ?></time></dd>
<?php
	}
}
?>
	</dl>
</details>
