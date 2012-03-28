<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $contentAboveCount > 0
 *
 * @package     Construc2
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
$modcount = $templateHelper->numModules('content-above');

$style	= null; // null is essentially the same as 'raw'

/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */
if ($modcount) : ?><div id="content-above" class="line above count-<?php echo $contentAboveCount[0] ?>"><div class="above-inner"><?php endif;

if ($contentAboveCount[1]) {
	$templateHelper->renderModules('content-above-1', $style, array());
}
if ($contentAboveCount[2]) {
	$templateHelper->renderModules('content-above-2', $style, array());
}
if ($contentAboveCount[3]) {
	$templateHelper->renderModules('content-above-3', $style, array());
}
if ($contentAboveCount[4]) {
	$templateHelper->renderModules('content-above-4', $style, array());
}

if ($modcount) : ?></div></div><?php endif;

//cleanup
unset($style, $modcount, $chunks);
