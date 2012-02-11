<?php
/**
 * Article and publishing properties.
 *
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
?>
	<details class="meta" title="<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>">
	<summary><?php echo $this->item->title ?></summary>
	<dl class="article-info">
<?php
/* the $parent_slug property is not available in all layouts */
if (isset($this->item->parent_slug)) {
	if ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') {
?>
	<dt class="parent-category"><?php JText::printf('COM_CONTENT_PARENT', ''); ?></dt>
	<dd class="parent-category"><?php
		$title = $this->escape($this->item->parent_title);
		if ($params->get('link_parent_category') && $this->item->parent_slug) {
		echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';
		} else {
		echo $title;
		} ?>
	</dd>
<?php
	}
}

if ($params->get('show_category')) {
?>
	<dt class="category"><?php JText::printf('COM_CONTENT_CATEGORY', ''); ?></dt>
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

if ($params->get('show_create_date')) {
?>
	<dt class="create"><?php JText::printf('COM_CONTENT_CREATED_DATE_ON', '') ?></dt>
	<dd class="create"><?php echo JHtml::_('date', $this->item->created, 'DATE_FORMAT_LC1'); ?></dd>
<?php
}

if ($params->get('show_publish_date')) {
?>
	<dt class="published"><?php JText::printf('COM_CONTENT_PUBLISHED_DATE', '') ?></dt>
	<dd class="published"><?php echo JHtml::_('date', $this->item->publish_up, 'DATE_FORMAT_LC1'); ?></dd>
<?php
}

if ($params->get('show_modify_date')) {
?>
	<dt class="modified"><?php JText::printf('COM_CONTENT_LAST_UPDATED', '') ?></dt>
	<dd class="modified"><?php echo JHtml::_('date', $this->item->modified, 'DATE_FORMAT_LC1'); ?></dd>
<?php
}

if ($params->get('show_hits')) {
?>
	<dt class="hits"><?php JText::printf('COM_CONTENT_ARTICLE_HITS', '') ?></dt>
	<dd class="hits"><?php echo $this->item->hits; ?></dd>
<?php
}
?>
	</dl>
	</details>
