<?php defined('_JEXEC') or die;
/**
 * "Imports" set in better.php
 *
 * $_ccid			cat_id from request
 * $_alang			application language
 * $item_heading	parameter
 * $show_children	parameter
 * $max_level		parameter
 */

foreach ($list as $item)
{
	$levelup = $item->level - $startLevel - 1;

	$css     = array('mi');
	$css[]   = $item->level < 1 ? 'parent' : 'level-'. ($item->level - 1);

	$route   = ContentHelperRoute::getCategoryRoute($item->id);
	$href    = JRoute::_($route);
	if ($_SERVER['REQUEST_URI'] == $href) {
		parse_str(parse_url($route, PHP_URL_QUERY), $query);
		$css[] = 'active';
	}

	// is this node a parent of the active category?
	if ( isset($tree['pids'][$item->id][$_ccid]) ) {
		$css[] = 'current';
	}

	// can we use extended aliases?
	if (($xtdalias = class_exists('ContentLayoutHelper', false))) {
		$css[] = $xtdalias = ContentLayoutHelper::getCssAlias($item);
	}

	// lang switch, as usual we have a web-unfriendly format
	$lang  = explode('-', $item->language);
	$lattr = ($lang[0] != '*' && ($lang[0] != $_alang))
			? array(' hreflang="'. $lang[0] .'"', ' lang="'. $lang[0] .'"')
			: array('', '');

	$kids = count($item->getChildren());

	if ($kids) {
		$css[] = 'parent';
	}

?><li id="catid-<?php echo $item->id ?>" class="<?php echo implode(' ', $css) ?>">
<?php if ($outerspan) { ?>
<span class="mi <?php echo 'H'. ($item_heading + $levelup) ?>"<?php echo $lattr[1] ?>><a class="mi" href="<?php echo $href ?>"<?php echo $lattr[0] ?>><?php echo $item->title ?></a></span>
<?php } else { ?>
<a class="mi" href="<?php echo $href ?>"<?php echo $lattr[0] ?>><span class="mi <?php echo 'H'. ($item_heading + $levelup) ?>"<?php echo $lattr[1] ?>><?php echo $item->title ?></span></a>
<?php }

	if ($params->get('show_description', 0)) {
		echo JHtml::_('content.prepare', $item->description, $item->getParams());
	}

	if ($show_children && ($max_level == 0 || $max_level >= ($item->level - $startLevel)) && $kids)
	{
		$list = $item->getChildren();
		// submenu classes
		$css   = array('submenu', $xtdalias);
		$css[] = $item->level < 1 ? 'leaf' : 'level-'. ($item->level - 1);
		echo '<menu class="', trim(implode(' ', $css)) ,'">';
		require __FILE__;
		echo '</menu>';
	}?>
</li>
<?php
}
