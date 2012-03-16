<?php
defined('_JEXEC') or die;
/* REQUIRES the 'ContentLayoutHelper' class to be registered in the main blog.php Layout */
?>
	<aside id="more" class="items-more">
		<header><h3><?php echo JText::_('COM_CONTENT_MORE_ARTICLES') ?></h3></header>
		<ol class="menu links"><?php
foreach ($this->link_items as $item)
{
	// create some fancy aliases
	$alias = ContentLayoutHelper::getCssAlias($item);

?>		<li class="mi <?php echo $alias ?>"><a href="<?php
	echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid))
	?>" class="mi"><span class="mi"><?php echo $item->title ?></span></a></li>
<?php
}
?>
		</ol>
	</aside>
