<?php
defined('_JEXEC') or die;

if (count($this->children[$this->category->id]) > 0) {
	return;
}
?>
<ul class="menu categories"><?php
foreach($this->children[$this->category->id] as $id => $child)
{
if ($this->params->get('show_empty_categories') || $child->getNumItems(true) || count($child->getChildren())) {
	if (!isset($this->children[$this->category->id][$id + 1])) {
		$class = ' class="last"';
	}
?><li class="mi"><span class="mi item-title"><a href="<?php
	echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id))
	?>"><?php echo $this->escape($child->title); ?></a></span>
<?php
	if ($this->params->get('show_subcat_desc') == 1) {
		if ($child->description && $this->params->get('show_description')!=0 ) { ?>
	<div class="category-desc"><?php echo JHtml::_('content.prepare', $child->description); ?></div>
<?php	}
	}
	if ($child->getNumItems()==true) { ?>
	<dl class="article-info">
	<dt><?php echo JText::_('COM_CONTENT_NUM_ITEMS') ; ?></dt>
	<dd><?php echo $child->getNumItems(true); ?></dd>
	</dl><?php
	}

	if (count($child->getChildren()) > 0 ) {
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
