<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

if (count($this->children[$this->category->id]) == 0) {
	return;
}

?>

<ul class="menu categories"><?php
foreach ($this->children[$this->category->id] as $id => $child)
{
	if ($this->params->get('show_empty_categories') || $child->getNumItems(true) || count($child->getChildren()))
	{
	?><li class="mi"><a href="<?php
		echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id))
		?>" class="mi"><span class="mi item-title"><?php echo $this->escape($child->title) ?></span></a>
	<?php
		if ($this->params->get('show_subcat_desc') == 1) {
			if ($child->description && $this->params->get('show_description')!=0 ) { ?>
	<div class="category-desc">
		<div class="introtext"><?php echo JHtml::_('content.prepare', $child->description) ?></div>
	</div>
	<?php	}
		}

		if ($child->getNumItems()) { ?>
	<p class="article-info">
	<strong><?php echo JText::_('COM_CONTENT_NUM_ITEMS') ?></strong>
	<span><?php echo $child->getNumItems(true) ?></span>
	</p><?php
		}

		if (count($child->getChildren()) > 0 ) {
			$this->children[$child->id] = $child->getChildren();
			$this->category = $child;
			$this->maxLevel -= 1;

			if ($this->maxLevel) {
				echo $this->loadTemplate('children');
			}

			$this->category = $child->getParent();
			$this->maxLevel += 1;
		}
		?></li><?php
	}
}
?></ul>
