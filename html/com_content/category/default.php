<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT_SITE .'/helpers');

// used to sanitize item aliases in blog_links and menus
JLoader::register('SearchHelper', JPATH_ADMINISTRATOR .'/components/com_search/helpers/search.php');

$show_page_heading   = $this->params->get('show_page_heading');
$show_category_title = $this->params->get('show_category_title');
$page_subheading     = $this->params->get('page_subheading');
$toggle_headings     = ($show_category_title || $page_subheading);

$desc     = ($this->category->description && $this->params->get('show_description'));
$desc_img = $this->params->def('show_description_image');

?>
<section class="category-list">
<?php
if ($show_page_heading)
{
	if ($show_page_heading && $toggle_headings) { ?><hgroup><?php } ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php

	if ($show_category_title || $page_subheading) { ?>
	<h2><?php
		echo $this->escape($page_subheading);
		if ($show_category_title) {
			echo '<span class="subheading-category">'.$this->category->title.'</span>';
		}
		?></h2><?php
	}

	if ($show_page_heading && $toggle_headings) { ?></hgroup><?php }
}

if ($desc) { ?>
	<div class="line category-desc">
	<?php if ($desc_img && $this->category->getParams()->get('image')) { ?>
		<img src="<?php echo $this->category->getParams()->get('image') ?>"/>
	<?php }
		if ($desc) { ?>
		<?php echo JHtml::_('content.prepare', $this->category->description) ?>
	<?php } ?>
	</div>
<?php
}

if (is_array($this->children[$this->category->id])
	&& count($this->children[$this->category->id]) > 0
	&& $this->params->get('maxLevel') !=0
) { ?>
<section class="line cat-children">
<?php
if (count($this->children[$this->category->id]) > 0) {
	echo ($toggle_headings) ? '<h3>' : '<h2>' ;
	echo JTEXT::_('JGLOBAL_SUBCATEGORIES');
	echo ($toggle_headings) ? '</h3>' : '</h2>' ;
	echo $this->loadTemplate('children');
}

?>
</section>
<?php } ?>

<div class="cat-items">
<?php echo $this->loadTemplate('articles'); ?>
</div>

</section>
