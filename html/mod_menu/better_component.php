<?php
/**
 * A better mod_menu override for component links.
 *
 * @package     Template
 * @subpackage  HTML
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   Copyright (C)2011-2012 WebMechanic. All rights reserved.
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$aclass = $item->anchor_css ? array('mi',$item->anchor_css) : array('mi');
in_array('active', $liclass) && $aclass[] = 'active';

$title = $item->anchor_title && ($item->anchor_title != $item->title) ? 'title="'.$item->anchor_title.'"'  : '';
if ($item->menu_image) {
	$item->params->get('menu_text', 1 )
		? $linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="'. trim(implode(' ', $aclass)) .' image-title">'.$item->title.'</span> '
		: $linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
}
else {
	$linktype = '<span class="'. trim(implode(' ', $aclass)) .'">'. $item->title . '</span>';
}

switch ($item->browserNav) :
	default:
	case 0:
?><a class="<?php echo trim(implode(' ', $aclass)) ?>" href="<?php echo $item->flink ?>" <?php echo $title ?>><?php echo $linktype ?></a><?php
		break;
	case 1:
		// _blank
?><a class="<?php echo trim(implode(' ', $aclass)) ?>" href="<?php echo $item->flink ?>" target="_blank" <?php echo $title ?>><?php echo $linktype ?></a><?php
		break;
	case 2:
	// window.open
?><a class="<?php echo trim(implode(' ', $aclass)) ?>" href="<?php echo $item->flink ?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;" <?php echo $title ?>><?php echo $linktype ?></a>
<?php
		break;
endswitch;
