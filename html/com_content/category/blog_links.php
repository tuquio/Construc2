<?php defined('_JEXEC') or die;
/**
 * REQUIRES the 'ContentLayoutHelper' class to be registered in the main blog.php Layout
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
?>
	<aside id="more" class="items-more">
		<header><h3><?php echo JText::_('COM_CONTENT_MORE_ARTICLES') ?></h3></header>
		<ol class="menu links"><?php
foreach ($this->link_items as $item)
{
	// create some fancy aliases
	$alias = ContentLayoutHelper::getCssAlias($item);

?>		<li class="mi <?php echo $alias ?>"><a href="<?php
	echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid))
	?>" class="mi"><span class="mi"><?php echo $item->title ?></span></a></li>
<?php
}
?>
		</ol>
	</aside>
