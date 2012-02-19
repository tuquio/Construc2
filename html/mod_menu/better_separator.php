<?php
/**
 * A better mod_menu override for menu item separators.
 *
 * @package     Template
 * @subpackage  HTML
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   Copyright (C)2011-2012 WebMechanic. All rights reserved.
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

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
