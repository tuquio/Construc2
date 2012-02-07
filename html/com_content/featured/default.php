<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// If the page class is defined, add to class as suffix.
// It will be a separate class if the user starts it with a space
?>
<section class="blog featured">
<?php if ( $this->params->get('show_page_heading') ) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif;

$leadingcount = $rowcount = $row = 0;

if (!empty($this->lead_items)) { ?>
<div class="line items-leading"><?php
	foreach ($this->lead_items as $item)
	{
		$leadingcount++;

		$this->item = $item;
?>
<article class="leading-<?php echo $leadingcount .' '. $item->category_alias . ($item->state == 0 ? ' system-unpublished' : ''); ?>">
<?php echo $this->loadTemplate('item'); ?>
</article>
<?php } ?>
</div>
<?php
}

$introcount = (count($this->intro_items));
$counter    = 0;

if (!empty($this->intro_items))
{
	foreach ($this->intro_items as $key => $item)
	{
		$key		= ($key - $leadingcount) + 1;
		$rowcount	= (($key - 1) % (int)$this->columns) + 1;
		$row 		= $counter / $this->columns ;
		if ($rowcount == 1) { ?> <div class="line items-row <?php echo 'row-'.$row ?>"><?php }

		$this->item = $item;
?>
<article class="unit size1of<?php echo (int) $this->columns .' column-' . $rowcount .' '. $item->category_alias . ($item->state == 0 ? ' system-unpublished"' :''); ?>">
<?php echo $this->loadTemplate('item'); ?>
</article>
<?php
		$counter++;
		if (($rowcount == $this->columns) or ($counter == $introcount)) { ?> </div><?php }
	}
}

if (!empty($this->link_items)) : ?>
	<div class="line items-more"><?php echo $this->loadTemplate('links'); ?></div>
<?php endif;

if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) :
?>
	<div class="pagination">
<?php if ($this->params->def('show_pagination_results', 1)) : ?>
	<p class="counter"><?php echo $this->pagination->getPagesCounter(); ?></p>
<?php  endif; ?>
<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>
</section>
