<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $contentBelowCount > 0
 *
 * @package     Construc2
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
$modcount = $templateHelper->numModules('content-below');

$style	= null; // null is essentially the same as 'raw'

/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */
if ($modcount) : ?><div id="content-below" class="line below count-<?php echo $contentBelowCount[0] ?>"><div class="below-inner"><?php endif;

if ($contentBelowCount[1]) {
	$templateHelper->renderModules('content-below-1', $style, array());
}
if ($contentBelowCount[2]) {
	$templateHelper->renderModules('content-below-2', $style, array());
}
if ($contentBelowCount[3]) {
	$templateHelper->renderModules('content-below-3', $style, array());
}
if ($contentBelowCount[4]) {
	$templateHelper->renderModules('content-below-4', $style, array());
}

if ($modcount) : ?></div></div><?php endif;

//cleanup
unset($style, $modcount, $chunks);
