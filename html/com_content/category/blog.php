<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

JLoader::register('ContentLayoutHelper', JPATH_THEMES . '/construc2/html/com_content/_shared/helper.php');

$show_page_heading   = $this->params->get('show_page_heading');
$show_category_title = $this->params->get('show_category_title');
$page_subheading     = $this->params->get('page_subheading');
$toggle_headings     = ($show_category_title || $page_subheading);

$desc     = ($this->category->description && $this->params->get('show_description'));
$desc_img = $this->params->def('show_description_image') && $this->category->getParams()->get('image');

?>

<section class="blog">
<?php if ($show_page_heading || $show_category_title) { ?>
	<header class="category">
<?php
	if ($toggle_headings) { ?><hgroup><?php } ?>
	<h1 class="H1 page-title"><span><?php echo $this->escape($this->params->get('page_heading')) ?></span></h1>
<?php

	if ($page_subheading) { ?>
	<h2 class="H2 title"><span><?php echo $this->escape($page_subheading) ?></span></h2><?php
	}

	if ($toggle_headings) { ?></hgroup><?php } ?>
	</header>
<?php
}

if ($desc || $desc_img) { ?>
	<article class="line category-desc">
		<div class="introtext"><?php
		if ($desc_img) { ?>
		<figure class="category"><img src="<?php echo $this->category->getParams()->get('image') ?>" class="catimg" alt="" /></figure>
<?php	}
		if ($desc) {
			echo JHtml::_('content.prepare', $this->category->description);
		}
?>
		</div>
	</article>
<?php
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

if (is_array($this->children[$this->category->id])
	&& count($this->children[$this->category->id]) > 0
	&& $this->params->get('maxLevel') !=0
) { ?>
	<section class="line categories-list">
<?php
	if (count($this->children[$this->category->id]) > 0) {
		echo ($toggle_headings) ? '<h3><span>' : '<h2><span>' ;
		echo JTEXT::_('JGLOBAL_SUBCATEGORIES');
		echo ($toggle_headings) ? '</span></h3>' : '</span></h2>' ;
		echo $this->loadTemplate('children');
	}
?>
	</section>
<?php }

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

</section><!-- .blog -->
