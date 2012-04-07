<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @var stdClass $item     the menu item
 * @var string   $liclass  set in better.php
 * @var string   $_alang   active language, set in better.php
 */

// lang switch, as usual we have a web-unfriendly format
$lang  = explode('-', $item->language);
$lattr = ($lang[0] != '*' && ($lang[0] != $_alang))
		? array(' hreflang="'. $lang[0] .'"', ' lang="'. $lang[0] .'"')
		: array('', '');

$aclass = $item->anchor_css ? array('mi',$item->anchor_css) : array('mi');
in_array('active', $liclass) && $aclass[] = 'active';

$title = $item->anchor_title && ($item->anchor_title != $item->title) ? 'title="'.$item->anchor_title.'"'  : '';
if ($item->menu_image) {
	$item->params->get('menu_text', 1 )
		? $linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="'. trim(implode(' ', $aclass)) .' image-title"'. $lattr[1] .'>'.$item->title.'</span> '
		: $linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
}
else {
	$linktype = '<span class="'. trim(implode(' ', $aclass)) .'"'. $lattr[1] .'>'. $item->title . '</span>';
}

switch ($item->browserNav) :
	default:
	case 0:
?><a class="<?php echo trim(implode(' ', $aclass)) ?>" href="<?php echo $item->flink ?>" <?php echo $lattr[0] . $title ?>><?php echo $linktype ?></a><?php
		break;
	case 1:
		// _blank
?><a class="<?php echo trim(implode(' ', $aclass)) ?>" href="<?php echo $item->flink ?>" target="_blank" <?php echo $lattr[0] . $title ?>><?php echo $linktype ?></a><?php
		break;
	case 2:
	// window.open
?><a class="<?php echo trim(implode(' ', $aclass)) ?>" href="<?php echo $item->flink ?>" <?php echo $lattr[0] . $title ?> onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;"><?php echo $linktype ?></a>
<?php
		break;
endswitch;
