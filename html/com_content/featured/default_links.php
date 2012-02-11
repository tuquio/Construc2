<?php
defined('_JEXEC') or die;
/* REQUIRES the 'SearchHelper' class to be registered in the main blog.php Layout */
?>
<aside class="line items-more">
	<header><h3><?php echo JText::_('COM_CONTENT_MORE_ARTICLES'); ?></h3></header>
	<ol class="menu links"><?php
foreach ($this->link_items as $item)
{
	// create some fancy aliases
	$alias1 = str_replace('-', ' ', $item->category_alias);
	SearchHelper::santiseSearchWord($alias1, $item->category_alias);
	$alias1 = str_replace(' ', '-', $alias1);

	$alias2 = str_replace('-', ' ', $item->alias);
	SearchHelper::santiseSearchWord($alias2, $item->alias);
	$alias2 = str_replace(' ', '-', $alias2);
?>
	<li class="mi <?php echo $alias1, ' ', $alias2 ?>"><a href="<?php
	echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid))
	?>" class="mi"><span class="mi"><?php echo $item->title ?></span></a></li>
<?php
}
?>
	</ol>
</aside>
