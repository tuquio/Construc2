<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

$item_params     = null;
$this->cat_level += 1;

?>

<ul class="menu categories <?php echo 'level-',$this->cat_level,' ', ContentLayoutHelper::getCssAlias($this->parent, false) ?>"><?php
foreach ($this->items[$this->parent->id] as $id => $item)
{
	// let's use it. for some reason this is always ''
	$item->childrennumitems = count($item->getChildren());

	if ($this->params->get('show_empty_categories_cat') || $item->numitems || $item->childrennumitems)
	{
		if (($descImage = $this->params->get('show_description_image')))
		{
			$item_params = json_decode($item->params);
			$descImage = (isset($item_params) && !empty($item_params->image)) ? $item_params->image : false;
		}

	?>

	<li class="mi <?php echo ($descImage ? 'has-image ' : '') , ($item->childrennumitems ? 'parent ' : '') , ContentLayoutHelper::getCssAlias($item, false) ?>">
	<a class="mi" href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id)) ?>
	"><span class="mi item-title"><?php echo $this->escape($item->title) ?></span></a>

<?php   if ($this->params->get('show_subcat_desc_cat') == 1) {
			if ($descImage) {
				echo '<figure class="category"><img src="', $descImage ,'" class="thumb" alt="" /></figure>';
			}

			if (trim($item->description)) { ?>
	<div class="category-desc">
		<div class="introtext"><?php echo JHtml::_('content.prepare', $item->description); ?>
	</div>
<?php       }
		}

		if ($this->params->get('show_cat_num_articles_cat') == 1) { ?>
	<p class="article-info">
	<strong><?php echo JText::_('COM_CONTENT_NUM_ITEMS') ?></strong>
	<span><?php echo $item->getNumItems(true) ?></span>
	</p>
<?php
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
?></ul>
