<?php defined('_JEXEC') or die;
/* REQUIRES the 'SearchHelper' class to be registered in the main default.php Layout */
?>
<div class="line items-more">
<h3><?php echo JText::_('COM_CONTENT_MORE_ARTICLES'); ?></h3>
<ol class="menu links">
<?php foreach ($this->link_items as $item) :
	// create some fancy aliases
	$alias1 = str_replace('-', ' ', $item->category_alias);
	SearchHelper::santiseSearchWord($alias1, $item->category_alias);
	$alias1 = str_replace(' ', '-', $alias1);

	$alias2 = str_replace('-', ' ', $item->alias);
	SearchHelper::santiseSearchWord($alias2, $item->alias);
	$alias2 = str_replace(' ', '-', $alias2);
?>
	<li class="li <?php echo $item->category_alias, ' ', $item->alias ?>"><a href="<?php
	echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid))
	?>" class="li"><span class="li"><?php echo $item->title ?></span></a></li>
<?php endforeach; ?>
</ol>
</div>
