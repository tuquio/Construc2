<?php
// no direct access
defined('_JEXEC') or die;

?>
<section class="blog featured">
	<header><?php
if ($this->params->get('show_page_heading')) {
	echo '<h1 class="page_heading">', $this->escape($this->params->get('page_heading')), '</h1>';
}
?>	</header>
<?php

$leadingcount = $rowcount = $row = 0;

if (!empty($this->lead_items)) { ?>
<div class="line items-leading">
<?php foreach ($this->lead_items as $item) : ?>
	<div class="leading item-<?php echo $leadingcount, ' ', $item->category_alias, ($item->state == 0 ? ' system-unpublished' : '') ?>">
<?php
	$this->item = &$item;
	echo $this->loadTemplate('item');
?>
	</div>
<?php $leadingcount += 1; ?>
<?php endforeach; ?>
</div>
<?php
}

$introcount = (count($this->intro_items));
$counter    = 0;

if (!empty($this->intro_items))
{
	settype($this->columns, 'int');
	?>
	<div class="line items-row cols-<?php echo (int) $this->columns ?>">
	<?php
	foreach ($this->intro_items as $key => $item)
	{
		$key		= (int)($key - $leadingcount) + 1;
		$rowcount	= ($key - 1) % ($this->columns + 1);
		$row 		= 1 + ceil($counter / $this->columns);
?>
	<div class="<?php echo 'row-', $row, (($rowcount & 1) ? ' even' : ' odd'), ' ', $item->category_alias, ($item->state == 0 ? ' system-unpublished':'') ?>">
<?php
	$this->item = &$item;
	echo $this->loadTemplate('item');
?>
	</div>
<?php
	$counter += 1;
	}
?>
	</div>
<?php
}

if (!empty($this->link_items)) {
	echo '<div class="line items-more">';
	echo $this->loadTemplate('links');
	echo '</div>';
}

if ($this->params->def('show_pagination', 2) == 1 || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) { ?>
	<div class="line pagination">
	<?php if ($this->params->def('show_pagination_results', 1)) { ?>
	<p class="counter"><?php echo $this->pagination->getPagesCounter(); ?></p>
	<?php  } ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
	</div><?php
}
?>
</section>
