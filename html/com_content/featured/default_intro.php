<?php
/**
 * blog_intro.php
 * @package
 * @author   WebMechanic.biz
 * @version  0.1.0
 * @license  CC-BY-NC 3.0/de
 */
settype($this->columns, 'int');

$num_intro = count($this->intro_items);
$num_lead  = count($this->lead_items);
// grid class
$unitCss = ($num_intro > 1 && $this->columns > 1) ? 'unit size1of'.$this->columns : '';
// whether items can evenly spread
$spread  = (int) (($num_intro % $this->columns) == 0);

foreach ($this->intro_items as $key => $item)
{
	$this->item = $item;

	$key = ($key - $num_lead) + 1;
	$col = (($key - 1) % $this->columns) + 1;
	$row = ceil($key / $this->columns);

	$split = ($col % $this->columns) == 1;
	$cols  = ($unitCss) ? ' cols-'. $this->columns : '';

	// reset cols and units for last item, allowing it to "stretch" across
	if (!$spread && $key == $num_intro) {
		$unitCss = '';
	}

if ($split) { ?>

	<section class="line items-row<?php echo $cols ?>">
<?php 	} ?>
	<div class="<?php echo $unitCss, ' row-', $row, ' column-', $col, ($col == $this->columns && $unitCss ? ' lastUnit' : '') ?>">
<?php echo $this->loadTemplate('item') ?>
	</div>
<?php
	if ($col == $this->columns) { ?>
	</section>
<?php   }
}
