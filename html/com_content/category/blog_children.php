<?php
// no direct access
defined('_JEXEC') or die;

$class = ' class="mi first"';

if (count($this->children[$this->category->id]) > 0) : ?>
<ul class="menu categories">
<?php foreach($this->children[$this->category->id] as $id => $child) :
	if ($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) :
		if (!isset($this->children[$this->category->id][$id + 1])) :
			$class = ' class="mi last"';
		endif; ?>
	<li<?php echo $class; ?>><?php $class = '';
	?><span class="mi item-title"><a href="<?php
			echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id))
			?>"><?php echo $this->escape($child->title); ?></a></span>

<?php
if ($this->params->get('show_subcat_desc') == 1) :
	if ($child->description) : ?><div class="category-desc"><?php echo JHtml::_('content.prepare', $child->description); ?></div><?php endif;
endif;

if ( $this->params->get('show_cat_num_articles',1)) : ?>
	<dl class="article-info">
		<dt><?php echo JText::_('COM_CONTENT_NUM_ITEMS') ; ?></dt>
		<dd><?php echo $child->getNumItems(true); ?></dd>
	</dl>
<?php endif ;

if (count($child->getChildren()) > 0):
	$this->children[$child->id] = $child->getChildren();
	$this->category = $child;
	$this->maxLevel--;

	if ($this->maxLevel != 0) :
		echo $this->loadTemplate('children');
	endif;

	$this->category = $child->getParent();
	$this->maxLevel++;
endif; ?>

	</li><?php
	endif;
endforeach; ?>
</ul>
<?php
endif;
