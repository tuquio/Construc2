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
$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
		);
$templateHelper->getTheme()->setChunks($chunks, true);

if ($modcount) : ?><footer id="footer-above" class="above"><div class="line above-inner"><?php endif;

if ($modcount > 1 && $footerAboveCount[1]) :
	$templateHelper->renderModules('footer-above-1', $style, array());
endif;
if ($modcount > 2 && $footerAboveCount[2]) :
	$templateHelper->renderModules('footer-above-2', $style, array());
endif;
if ($modcount > 3 && $footerAboveCount[3]) :
	$templateHelper->renderModules('footer-above-3', $style, array());
endif;
if ($modcount > 4 && $footerAboveCount[4]) :
	$templateHelper->renderModules('footer-above-4', $style, array());
endif;
if ($modcount > 5 && $footerAboveCount[5]) :
	$templateHelper->renderModules('footer-above-5', $style, array());
endif;
if ($modcount > 6 && $footerAboveCount[6]) :
	$templateHelper->renderModules('footer-above-6', $style, array());
endif;

if ($modcount) : ?></div></footer><?php endif;
/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */

