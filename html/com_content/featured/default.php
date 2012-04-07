<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

JLoader::register('ContentLayoutHelper', JPATH_THEMES . '/construc2/html/com_content/_shared/helper.php');

?>
<section class="blog featured"><?php
if ($this->params->get('show_page_heading')) {
	echo '<h1 class="page-title">', $this->escape($this->params->get('page_heading')), '</h1>';
}

$leading_items = 0;

if (!empty($this->lead_items))
{ ?>
	<section class="line items-leading"><?php
	foreach ($this->lead_items as $item)
	{
		$leading_items += 1;
		$this->item = $item;
?>
	<div class="leading-<?php echo $leading_items ?>">
	<?php echo $this->loadTemplate('item') ?>
	</div>
<?php } ?>
	</section>
<?php
}

if (!empty($this->intro_items))
{
	settype($this->columns, 'int');

	$intro   = count($this->intro_items);
	// grid class
	$unitCss = ($intro > 1 && $this->columns > 1) ? 'unit size1of'.$this->columns : '';
	// whether items can be evenly spread
	$spread  = (int) (($intro % $this->columns) == 0);

	foreach ($this->intro_items as $key => $item)
	{
		$this->item = $item;

		$key = (int)($key - $leading_items) + 1;
		$col = (($key - 1) % $this->columns) + 1;
		$row = ceil($key / $this->columns);

		$split = ($col % $this->columns) == 1;
		$cols  = ' cols-'. $this->columns;

		// reset cols and units for last item, allowing it to "stretch" across
		if (!$spread && $key == $intro) {
			$unitCss = '';
		}

		if ($split) { ?>
	<section class="line items-row<?php echo $cols ?>">
<?php 	} ?>
		<div class="<?php echo $unitCss, ' row-', $row, ' column-', $col, ($unitCss && $col == $this->columns ? ' lastUnit' : '') ?>">
		<?php echo $this->loadTemplate('item') ?>
		</div>
<?php 	if ($col == $this->columns) { ?>
	</section>
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

/*#FIXME NOT READY YET until ./html/pagination.php pagination_list_footer() is ironed out. */
// $this->pagination->getListFooter()

?>

</section>
