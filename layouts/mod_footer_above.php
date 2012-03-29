<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $footerAboveCount > 0
 *
 * @package     Construc2
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
$modcount = $templateHelper->numModules('footer-above');

$style	= null; // null is essentially the same as 'raw'

/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */
if ($modcount) : ?><footer id="footer-above" class="line below" data-modules="<?php echo $footerAboveCount[0] ?>"><div class="above-inner"><?php endif;

if ($footerAboveCount[1]) {
	$templateHelper->renderModules('footer-above-1', $style, array());
}
if ($footerAboveCount[2]) {
	$templateHelper->renderModules('footer-above-2', $style, array());
}
if ($footerAboveCount[3]) {
	$templateHelper->renderModules('footer-above-3', $style, array());
}
if ($footerAboveCount[4]) {
	$templateHelper->renderModules('footer-above-4', $style, array());
}

if ($modcount) : ?></div></footer><?php endif;

//cleanup
unset($style, $modcount);

