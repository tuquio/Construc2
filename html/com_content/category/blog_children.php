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
$class = ' class="mi first"';
?>
<ul class="menu categories"><?php
foreach($this->children[$this->category->id] as $id => $child)
{
	if ($this->params->get('show_empty_categories') || $child->getNumItems(true) || count($child->getChildren()))
	{
		if (!isset($this->children[$this->category->id][$id + 1])) {
			$class = ' class="last"';
		}
?><li class="mi">
	<article class="line category-desc">
	<span class="mi H4"><a  class="mi" href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id))	?>"><?php
		echo $this->escape($child->title) ?></a></span>
<?php
		if ($this->params->get('show_subcat_desc') == 1)
		{
			if ($child->description && $this->params->get('show_description')!=0 ) { ?>
		<div class="introtext"><?php echo JHtml::_('content.prepare', $child->description) ?></div>
<?php		}
		}

		if ($child->getNumItems()) { ?>
	<p class="article-info">
	<strong><?php echo JText::_('COM_CONTENT_NUM_ITEMS') ?></strong>
	<span><?php echo $child->getNumItems(true) ?></span>
	</p>
	</article>
<?php
		}

		if (count($child->getChildren()) > 0 )
		{
			$this->children[$child->id] = $child->getChildren();
			$this->category = $child;
			$this->maxLevel--;

			if ($this->maxLevel != 0) {
				echo $this->loadTemplate('children');
			}

			$this->category = $child->getParent();
			$this->maxLevel++;
		} ?></li><?php
	} // if
} // foreach
?></ul>
