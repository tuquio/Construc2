<?php
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT_SITE .'/helpers');

?>
<div class="categories-list">
<?php if ($this->params->get('show_page_heading', 1)) { ?>
<h1 class="H2"><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php }

	if ($this->params->get('show_base_description')) {
		//If there is a description in the menu parameters use that;
		if($this->params->get('categories_description')) {
			echo  JHtml::_('content.prepare',$this->params->get('categories_description'));
		} else {
		//Otherwise get one from the database if it exists.
			if ($this->parent->description) { ?>
	<div class="category-desc"><?php echo JHtml::_('content.prepare', $this->parent->description); ?></div>
<?php  		}
		}
	}

echo $this->loadTemplate('items');?>
</div>
