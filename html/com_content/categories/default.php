<?php
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT_SITE .'/helpers');

?>
	<section class="categories-list">
<?php if ($this->params->get('show_page_heading')) { ?>
	<h1 class="H1 page-title"><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php }

	if ($this->params->get('show_base_description')) { ?>
	<article class="line category-desc">
<?php
		// If there is a description in the menu parameters use that;
		if ($this->params->get('categories_description')) {
			echo  JHtml::_('content.prepare',$this->params->get('categories_description'));
		}
		// otherwise get one from the database if it exists.
		else if ($this->parent->description) {
			echo JHtml::_('content.prepare', $this->parent->description);
		} ?>
	</article>
<?php
	}

echo $this->loadTemplate('items');

?>
	</section>
