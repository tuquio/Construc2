<?php
/**
 * @version		$Id: default.php 17187 2010-05-19 11:18:22Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	Templates.beez5
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT_SITE .DS. 'helpers');

$show_page_heading   = $this->params->get('show_page_heading');
$show_category_title = $this->params->get('show_category_title');
$page_subheading     = $this->params->get('page_subheading');
?>
<section class="category-list">
<?php if ($show_page_heading) : ?>
	<?php if ($show_page_heading && ($show_category_title || $page_subheading)) : ?>
	<hgroup>
	<?php endif; ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>

<?php if ($show_category_title || $page_subheading) : ?>
	<h2><?php
	echo $this->escape($page_subheading);
	if ($show_category_title) {
		echo '<span class="subheading-category">'.$this->category->title.'</span>';
	}
	?></h2>
<?php if ($show_page_heading && ($this->params->get('show_category_title', 1) || $page_subheading)) : ?>
</hgroup>
<?php endif;
endif;

$desc     = $this->params->get('show_description');
$desc_img = $this->params->def('show_description_image');
if ($desc || $desc_img ) : ?>
<div class="category-desc">
	<?php if ($desc_img && ($img_src = $this->category->getParams()->get('image')) ) : ?>
	<img src="<?php echo $img_src; ?>"/>
	<?php endif; ?>
	<?php if ($desc && $this->category->description) : ?>
	<?php echo JHtml::_('content.prepare', $this->category->description); ?>
	<?php endif; ?>
<div class="clr"></div>
</div>
<?php endif; ?>


<?php if (is_array($this->children[$this->category->id]) && count($this->children[$this->category->id]) > 0 && $this->params->get('maxLevel') !=0) : ?>
<div class="cat-children">
<?php
if ($show_category_title || $page_subheading) {
	echo '<h3>';
} else {
	echo '<h2>';
}
?>
<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
<?php
if ($show_category_title || $page_subheading) {
	echo '</h3>';
} else {
	echo '</h2>';
}
?>
<?php echo $this->loadTemplate('children'); ?>
</div>
<?php endif; ?>

<div class="cat-items">
<?php echo $this->loadTemplate('articles'); ?>
</div>

</section>
