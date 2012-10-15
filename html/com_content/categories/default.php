<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

// JHtml::addIncludePath(JPATH_COMPONENT_SITE .'/helpers');
JLoader::register('ContentLayoutHelper', JPATH_THEMES . '/construc2/html/com_content/_shared/helper.php');

?>
	<section class="line categories-list">
<?php if ($this->params->get('show_page_heading')) { ?>
	<h1 class="H1 page-title"><span><?php echo $this->escape($this->params->get('page_heading')) ?></span></h1>
<?php }

	if ($this->params->get('show_base_description')) { ?>
	<article class="line category-desc">
<?php
		// If there is a description in the menu parameters use that;
		if ($this->params->get('categories_description')) {
			echo  JHtml::_('content.prepare', $this->params->get('categories_description'));
		}
		// otherwise get one from the database if it exists.
		elseif ($this->parent->description) {
			echo JHtml::_('content.prepare', $this->parent->description);
		} ?>
	</article>
<?php
	}

	if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0)
	{
		$this->cat_level = 0;
		echo $this->loadTemplate('items');
	}
	unset($level);
?>
	</section>
