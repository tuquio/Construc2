<?php
defined('_JEXEC') or die;

JLoader::register('ContentLayoutHelper', JPATH_THEMES . '/construc2/html/com_content/_shared/helper.php');

?>
<section class="blog featured"><?php
if ($this->params->get('show_page_heading')) {
	echo '<header><h1 class="page_heading">', $this->escape($this->params->get('page_heading')), '</h1></header>';
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
	</section><!-- .items-leading -->
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

if ($this->pagination->get('pages.total') > 1 && ($this->params->get('show_pagination') == 1 || $this->params->get('show_pagination') == 2))
{ ?>
	<nav id="pages" class="line pagination">
<?php if ($this->params->def('show_pagination_results', 1)) { ?>
	<p class="counter"><?php echo $this->pagination->getPagesCounter() ?></p>
<?php }
	echo $this->pagination->getPagesLinks();
?>
	</nav>
<?php
}

/* NOT READY YET until ./html/pagination.php pagination_list_footer() is ironed out. */
// $this->pagination->getListFooter()

?>

</section><!-- .blog.featured -->
