<?php defined('_JEXEC') or die; ?>
<ul class="menu categories"><?php
foreach ($this->items[$this->parent->id] as $id => $item)
{
	if ($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren()))
	{
	?><li class="mi"><a href="<?php
		echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id))
		?>" class="mi"><span class="mi item-title"><?php echo $this->escape($item->title) ?></span></a>
<?php
		if ($this->params->get('show_subcat_desc_cat') == 1)
		{
			if ($item->description) { ?>
			<div class="line category-desc"><?php echo JHtml::_('content.prepare', $item->description) ?></div>
<?php 		}
		}

		if ($this->params->get('show_cat_num_articles_cat') == 1) { ?>
	<p class="article-info">
	<strong><?php echo JText::_('COM_CONTENT_NUM_ITEMS') ?></strong>
	<span><?php echo $child->getNumItems(true) ?></span>
	</p><?php
		}

		if (count($item->getChildren()) > 0)
		{
			$this->items[$item->id] = $item->getChildren();
			$this->parent = $item;
			$this->maxLevelcat -= 1;

			if ($this->maxLevelcat) {
				echo $this->loadTemplate('items');
			}

			$this->parent = $item->getParent();
			$this->maxLevelcat += 1;
		}
	?></li><?php
	}
}
?>
</ul>
