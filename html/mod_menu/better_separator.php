<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

$title = $item->anchor_title ? 'title="'.$item->anchor_title.'"' : '';
if ($item->menu_image) {
	$item->params->get('menu_text', 1 )
		? $linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="mi image-title">'.$item->title.'</span> '
		: $linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
}
else {
	$linktype = '<span class="mi">'. $item->title . '</span>';
}
	// no additional "mi" class  mi here.
	// separator should be fine in the context of .menu and .submenu
?><span class="sep"><?php echo $title ?><?php echo $linktype ?></span>
