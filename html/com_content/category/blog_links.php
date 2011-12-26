<?php defined('_JEXEC') or die; ?>
<div class="items-more">
<h3><?php echo JText::_('COM_CONTENT_MORE_ARTICLES'); ?></h3>

<ol class="links">
<?php foreach ($this->link_items as $li => $item) : ?>
	<li><a href="<?php
	echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid))
	?>"><?php echo $item->title ." ($li)" ?></a></li>
<?php endforeach; ?>
</ol>
</div>
