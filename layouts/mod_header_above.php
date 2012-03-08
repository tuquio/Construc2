<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $headerAboveCount > 0
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
$modcount = count($headerAboveCount);

$style	= null; // null is essentially the same as 'raw'
$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
		);
$templateHelper->theme->setChunks($chunks, true);

if ($modcount) : ?><div id="header-above" class="above"><div class="line above-inner"><?php endif;

if ($modcount > 1 && $headerAboveCount[1]) :
	$templateHelper->renderModules('header-above-1', $style, array());
endif;
if ($modcount > 2 && $headerAboveCount[2]) :
	$templateHelper->renderModules('header-above-2', $style, array());
endif;
if ($modcount > 3 && $headerAboveCount[3]) :
	$templateHelper->renderModules('header-above-3', $style, array());
endif;
if ($modcount > 4 && $headerAboveCount[4]) :
	$templateHelper->renderModules('header-above-4', $style, array());
endif;
if ($modcount > 5 && $headerAboveCount[5]) :
	$templateHelper->renderModules('header-above-5', $style, array());
endif;
if ($modcount > 6 && $headerAboveCount[6]) :
	$templateHelper->renderModules('header-above-6', $style, array());
endif;

if ($modcount) : ?></div></div><?php endif; ?>
