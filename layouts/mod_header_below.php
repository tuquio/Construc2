<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $headerBelowCount > 0
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
$modcount = count($headerBelowCount);

$style	= null; // null is essentially the same as 'raw'
$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
		);
$templateHelper->getTheme()->setChunks($chunks, true);

if ($modcount) : ?><div id="header-below" class="below"><div class="line below-inner"><?php endif;

if ($modcount > 1 && $headerBelowCount[1]) :
	$templateHelper->renderModules('header-below-1', $style, array());
endif;
if ($modcount > 2 && $headerBelowCount[2]) :
	$templateHelper->renderModules('header-below-2', $style, array());
endif;
if ($modcount > 3 && $headerBelowCount[3]) :
	$templateHelper->renderModules('header-below-3', $style, array());
endif;
if ($modcount > 4 && $headerBelowCount[4]) :
	$templateHelper->renderModules('header-below-4', $style, array());
endif;
if ($modcount > 5 && $headerBelowCount[5]) :
	$templateHelper->renderModules('header-below-5', $style, array());
endif;
if ($modcount > 6 && $headerBelowCount[6]) :
	$templateHelper->renderModules('header-below-6', $style, array());
endif;

if ($modcount) : ?></div></div><?php endif; ?>
