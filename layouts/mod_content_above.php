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

/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */
if ($modcount) : ?><div id="content-above" class="line above" data-modules="<?php echo $contentAboveCount[0] ?>"><div class="above-inner"><?php endif;

if ($contentAboveCount[1]) {
	$templateHelper->renderModules('content-above-1');
}
if ($contentAboveCount[2]) {
	$templateHelper->renderModules('content-above-2');
}
if ($contentAboveCount[3]) {
	$templateHelper->renderModules('content-above-3');
}
if ($contentAboveCount[4]) {
	$templateHelper->renderModules('content-above-4');
}

if ($modcount) : ?></div></div><?php endif;

//cleanup
unset($modcount);
