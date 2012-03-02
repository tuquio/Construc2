<?php
/**
 */
defined('_JEXEC') or die;

$temp = $list;
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

	// is this node a prent of the active category?
	if ( isset($tree['pids'][$item->id][$acid]) ) {
		$css[] = 'current';
	}

	// can we use extended aliases?
	if ($xtdalias) {
		$css[] = $xtdalias = ContentLayoutHelper::getCssAlias($item);
	}

	// all classes for the list item
	$licss = implode(' ', $css);

?><li id="catid-<?php echo $item->id ?>" class="<?php echo $licss ?>">
<a class="mi" href="<?php echo $href ?>"><span class="mi <?php echo 'H'. ($item_heading + $levelup) ?>"><?php echo $item->title ?></span></a>
<?php
if ($params->get('show_description', 0)) {
	echo JHtml::_('content.prepare', $item->description, $item->getParams());
}

if ($show_children && ($max_level == 0 || $max_level >= ($item->level - $startLevel)) && count($item->getChildren()))
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

$list = $temp;
