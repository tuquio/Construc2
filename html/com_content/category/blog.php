<?php
// no direct access
defined('_JEXEC') or die;

// used to sanitize item aliases in blog_links and menus
JLoader::register('SearchHelper', JPATH_ADMINISTRATOR .'/components/com_search/helpers/search.php');

?>
<section class="blog">
	<header><?php
if ($this->params->get('show_page_heading')) {
	echo '<h1 class="page_heading">', $this->escape($this->params->get('page_heading')), '</h1>';
}

if ($this->params->get('show_category_title')) {
	echo '<h2 class="subheading category">', $this->category->title, '</h2>';
}
?>	</header>
<?php
if (!empty($this->category->description) && ($this->params->get('show_description') || $this->params->def('show_description_image')) ) : ?>
	<div class="line category-desc">
	<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
		<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
	<?php endif; ?>
	<?php if ($this->params->get('show_description') && $this->category->description) : ?>
		<?php echo JHtml::_('content.prepare', $this->category->description); ?>
	<?php endif; ?>
	</div>
<?php
endif;

$leadingcount = 0;

if (!empty($this->lead_items)) { ?>
	<section class="line items-leading"><?php
	foreach ($this->lead_items as $item)
	{
		$leadingcount += 1;
		$this->item = $item;
?>
	<div class="leading-<?php echo $leadingcount ?>">
	<?php echo $this->loadTemplate('item') ?>
	</div>
<?php } ?>
	</section>
<?php
}

if (!empty($this->intro_items))
{
	settype($this->columns, 'int');
	?>
	<?php
	foreach ($this->intro_items as $key => $item)
	{
		$key = (int)($key - $leadingcount) + 1;
		$col = (($key - 1) % $this->columns) + 1;
		$row = ceil($key / $this->columns);

		$this->item = $item;

		if ($col == 1) { ?>
		<section class="line items-row cols-<?php echo $this->columns ?>">
<?php 	} ?>
		<div class="unit size1of<?php echo $this->columns, ' row-', $row, ' column-', $col, ($col == $this->columns ? ' lastUnit' : '') ?>">
<?php
		echo $this->loadTemplate('item');
?>
		</div>
<?php
		if ($col == $this->columns) { ?>
		</section>
<?php	}
	}
}

if (!empty($this->link_items)) { ?>
	<section class="line items-more"><?php echo $this->loadTemplate('links') ?></section>
<?php
}

if (is_array($this->children[$this->category->id]) && count($this->children[$this->category->id]) > 0 && $this->params->get('maxLevel') !=0) { ?>
	<section class="line cat-children">
	<h3><?php echo JText::_('JGLOBAL_SUBCATEGORIES'); ?></h3>
	<?php echo $this->loadTemplate('children'); ?>
	</section><?php
}

if ($this->params->def('show_pagination', 2) == 1
	|| ($this->params->get('show_pagination') == 2
	&& $this->pagination->get('pages.total') > 1))
{ ?>
	<nav id="nav-pages" class="line pagination">
<?php if ($this->params->def('show_pagination_results', 1)) { ?>
	<p class="counter"><?php echo $this->pagination->getPagesCounter(); ?></p>
<?php }
	echo $this->pagination->getPagesLinks();
?>
	</nav>
<?php } ?>

</section>
