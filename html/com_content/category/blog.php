<?php
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT_SITE .'/helpers');

// used to sanitize item aliases in blog_links and menus
JLoader::register('SearchHelper', JPATH_ADMINISTRATOR .'/components/com_search/helpers/search.php');
JLoader::register('ContentLayoutHelper', JPATH_THEMES . '/construc2/html/com_content/_shared/helper.php');

$show_page_heading   = $this->params->get('show_page_heading');
$show_category_title = $this->params->get('show_category_title');
$page_subheading     = $this->params->get('page_subheading');
$toggle_headings     = ($show_category_title || $page_subheading);

$desc     = ($this->category->description && $this->params->get('show_description'));
$desc_img = $this->params->def('show_description_image');

?>
	<section class="blog">
<?php if ($show_page_heading) { ?>
	<header class="category">
<?php
	if ($toggle_headings) { ?><hgroup><?php } ?>
	<h1 class="H1 page-title"><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php

	if ($show_category_title || $page_subheading) { ?>
	<h2 class="H2 title"><?php
		echo $this->escape($page_subheading);
		if ($show_category_title) {
			echo '<span class="subheading-category">'.$this->category->title.'</span>';
		}
		?></h2><?php
	}

	if ($toggle_headings) { ?></hgroup><?php } ?>
	</header>
<?php
}

if ($desc) { ?>
	<article class="line category-desc">
		<div class="introtext"><?php
		if ($desc_img && $this->category->getParams()->get('image')) {
		?><p><img class="catimg" src="<?php echo $this->category->getParams()->get('image') ?>" /></p><?php
		}
		if ($desc) {
			echo JHtml::_('content.prepare', $this->category->description);
		}
?>
		</div>
	</article>
<?php
}

$leadingcount = 0;

if (!empty($this->lead_items))
{ ?>
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

	$unitCss = (count($this->intro_items) > 1) ? 'unit size1of'.$this->columns : '';

	foreach ($this->intro_items as $key => $item)
	{
		$key = (int)($key - $leadingcount) + 1;
		$col = (($key - 1) % $this->columns) + 1;
		$row = ceil($key / $this->columns);

		$this->item = $item;

		if ($col == 1) { ?>
	<section class="line items-row cols-<?php echo $this->columns ?>">
<?php 	} ?>
		<div class="<?php echo $unitCss, ' row-', $row, ' column-', $col, ($unitCss && $col == $this->columns ? ' lastUnit' : '') ?>">
		<?php echo $this->loadTemplate('item') ?>
		</div>
<?php 	if ($col & $this->columns) { ?>
	</section><!-- .items-row -->
<?php	}
	}
}

if (!empty($this->link_items))
{
	echo $this->loadTemplate('links');
}

if (is_array($this->children[$this->category->id])
	&& count($this->children[$this->category->id]) > 0
	&& $this->params->get('maxLevel') !=0
) { ?>
	<section class="cat-children">
<?php
	if (count($this->children[$this->category->id]) > 0) {
		echo ($toggle_headings) ? '<h3>' : '<h2>' ;
		echo JTEXT::_('JGLOBAL_SUBCATEGORIES');
		echo ($toggle_headings) ? '</h3>' : '</h2>' ;
		echo $this->loadTemplate('children');
	}
?>
	</section>
<?php }

if ($this->params->get('show_pagination') == 1 || ($this->params->get('show_pagination') == 2
	&& $this->pagination->get('pages.total') > 1))
{ ?>
	<nav id="pages" class="line pagination">
<?php if ($this->params->def('show_pagination_results', 1)) { ?>
	<p class="counter"><?php echo $this->pagination->getPagesCounter(); ?></p>
<?php }
	echo $this->pagination->getPagesLinks();
?>
	</nav>
<?php } ?>

</section>
