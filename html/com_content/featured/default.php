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

if (!empty($this->lead_items))
{ ?>

	<section class="line items-leading"><?php
		foreach ($this->lead_items as $i => $item)
		{
			$this->item = $item;
			?>

			<div class="leading-<?php echo $i ?>">
				<?php echo $this->loadTemplate('item') ?>
			</div>
			<?php } ?>

	</section><!-- .items-leading -->
	<?php
}

if (!empty($this->intro_items))
{
	echo $this->loadTemplate('intro');
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
