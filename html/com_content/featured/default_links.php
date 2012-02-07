<?php
defined('_JEXEC') or die;
?>
<h3><?php echo JText::_('COM_CONTENT_MORE_ARTICLES'); ?></h3>

<ol class="links">
<?php foreach ($this->link_items as $li => $item) : ?>
	<li class="mi <?php echo $item->catslug, ' ', $item->slug ?>"><a href="<?php
	echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
	?>" class="mi"><span class="mi"><?php echo $item->title ." ($li)" ?></span></a></li>
<?php endforeach; ?>
</ol>
