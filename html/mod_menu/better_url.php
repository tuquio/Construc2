<?php defined('_JEXEC') or die;
/**
 * A better mod_menu override for external URLs and "unknown" types.
 *
 * @package     Template
 * @subpackage  HTML
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C)2011-2012 WebMechanic. All right reserved.
 * @license     GNU GPL v2 or later
 */

$class = $item->anchor_css   ? 'class="mi '.$item->anchor_css.'"' : 'class="mi"';
$title = $item->anchor_title ? 'title="'.$item->anchor_title.'"'  : '';
if ($item->menu_image) {
	$item->params->get('menu_text', 1 )
		? $linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="mi image-title">'.$item->title.'</span> '
		: $linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
}
else {
	$linktype = '<span class="mi">'. $item->title . '</span>';
}

switch ($item->browserNav) :
	default:
	case 0:
?><a <?php echo $class; ?> href="<?php echo $item->flink ?>" <?php echo $title ?>><?php echo $linktype ?></a><?php
		break;
	case 1:
		// _blank
?><a <?php echo $class; ?> href="<?php echo $item->flink ?>" target="_blank" <?php echo $title ?>><?php echo $linktype ?></a><?php
		break;
	case 2:
		// window.open
		$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$params->get('window_open');
?><a <?php echo $class ?> href="<?php echo $item->flink ?>" onclick="window.open(this.href,'targetWindow','<?php echo $attribs ?>');return false;" <?php echo $title ?>><?php echo $linktype ?></a><?php
		break;
endswitch;
