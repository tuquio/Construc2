<?php
/**
 */
defined('_JEXEC') or die;

$temp = $list;
foreach ($list as $item) :

	$levelup = $item->level - $startLevel - 1;

	$css     = array('mi');
	$css[]   = $item->level < 1 ? 'parent' : 'level-'. ($item->level - 1);

	$active  = 0;
	$current = 0;
	$route   = ContentHelperRoute::getCategoryRoute($item);

	if ($_SERVER['REQUEST_URI'] == JRoute::_($route)) {
		parse_str(parse_url($route, PHP_URL_QUERY), $query);
		$css[]  = 'active';
		$active = $query['id'];
	}

	// is this node a prent of the active category?
	if ( isset($tree['pids'][$item->id][$acid]) ) {
		$css[]   = 'current';
		$current = $item->id;
	}
	// all classes for the list item
	$licss = implode(' ', $css);

	// submenu classes
	$css   = array('submenu');
	$css[] = $item->level < 1 ? 'nope' : 'level-'. ($item->level - 1);
	$css[] = $current ? ($active ? 'active-sub' : 'current-sub') : '';

	// all classes for the submenu
	$subcss = implode(' ', $css);

?><li id="catid-<?php echo $item->id ?>" class="<?php echo $licss ?>">
<a href="<?php echo JRoute::_($route) ?>"><span class="li <?php echo 'H'. ($item_heading + $levelup) ?>"><?php echo $item->title ?></span></a>
<?php
if ($params->get('show_description', 0)) {
	echo JHtml::_('content.prepare',$item->description, $item->getParams());
}

if ($params->get('show_children', 0) && (($params->get('maxlevel', 0) == 0) || ($params->get('maxlevel') >= ($item->level - $startLevel))) && count($item->getChildren()))
{
	echo '<menu class="', $subcss, '">';
	$list = $item->getChildren();
	require __FILE__;
	echo '</menu>';
}?>
</li>
<?php
endforeach;
$list = $temp;
