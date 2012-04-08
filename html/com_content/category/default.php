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
$desc_img = ($this->params->def('show_description_image') && $this->category->getParams()->get('image'));

?>
<section class="category-list">
<?php if ($show_page_heading) { ?>
	<header class="category">
<?php
	if ($toggle_headings) { ?><hgroup><?php } ?>
	<h1 class="H1 page-title"><span><?php echo $this->escape($this->params->get('page_heading')) ?></span></h1>
<?php

	if ($show_category_title || $page_subheading) { ?>
	<h2 class="H2 title"><?php
		echo $this->escape($page_subheading);
		if ($show_category_title) {
			echo '<span>', $this->category->title, '</span>';
		}
		?></h2>
<?php
	}
	if ($toggle_headings) { ?></hgroup><?php } ?>
	</header>
<?php
}

if ($desc || $desc_img) { ?>
	<article class="line category-desc">
		<div class="introtext"><?php
		if ($desc_img) {
		?><img class="cat-image" src="<?php echo $this->category->getParams()->get('image') ?>" /><?php
		}
		if ($desc) {
			echo JHtml::_('content.prepare', $this->category->description);
		}
?>
		</div>
	</article>
<?php
}

if (is_array($this->children[$this->category->id])
	&& count($this->children[$this->category->id]) > 0
	&& $this->params->get('maxLevel') !=0
) {
	if (count($this->children[$this->category->id]) > 0) {
?>
	<section class="cat-children">
<?php
		echo ($toggle_headings) ? '<h3 class="H3 subtitle">' : '<h2 class="H3 subtitle">' ;
		echo '<span class="cat-title">', JText::_('JGLOBAL_SUBCATEGORIES'), '</span>';
		echo ($toggle_headings) ? '</h3>' : '</h2>' ;

		echo $this->loadTemplate('children');
?>
	</section>
<?php
	}
}
?>

	<section class="cat-items">
<?php echo $this->loadTemplate('articles'); ?>
	</section>

</section>
