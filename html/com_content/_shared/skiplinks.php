<?php defined('_JEXEC') or die;
/**
 * Page-level "skiplinks" for Blog layouts and such, uses the titles of
 * - lead_items
 * - intro_items
 * - link_items
 * and renders an ordered list with page anchors
 *
 * @package     Template
 * @subpackage  Overrides
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2012 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

?>
	<header id="skiplinks">
	<h1 class="H3 title"><span><?php echo JText::_('Skiplinks') ?></span></h1>
	<ol class="skiplinks">
<?php
$rqry = JURI::getInstance()->getQuery();
if (!empty($rqry)) {
	$rqry = '?'.$rqry;
}
if (isset($this->lead_items) && !empty($this->lead_items)) {
	foreach ($this->lead_items as $item) { ?>
		<li class="mi"><a href="<?php echo $rqry, '#', $item->alias ?>" class="mi"><?php echo $this->escape($item->title) ?></a><?php if ($this->params->get('show_category_title')) { echo ' <em class="cat-title">', trim($this->escape($item->category_title)) ,'</em>'; } ?></li>
	<?php
	}
}
if (isset($this->intro_items) && !empty($this->intro_items)) {
	foreach ($this->intro_items as $key => $item) { ?>
		<li class="mi"><a href="<?php echo $rqry, '#', $item->alias ?>" class="mi"><?php echo $this->escape($item->title) ?></a><?php if ($this->params->get('show_category_title')) { echo ' <em class="cat-title">', trim($this->escape($item->category_title)) ,'</em>'; } ?></li>
	<?php
	}
}
if (isset($this->link_items) && !empty($this->link_items)) { ?>
		<li class="mi"><a href="#more" class="mi more"><?php echo JText::_('COM_CONTENT_MORE_ARTICLES') ?></a></li>
	<?php
}
?>
	</ol>
	</header>
