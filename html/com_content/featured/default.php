<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

?>
<section class="blog featured"><?php
if ($this->params->get('show_page_heading')) {
	echo '<header><h1 class="page_heading">', $this->escape($this->params->get('page_heading')), '</h1></header>';
}

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
