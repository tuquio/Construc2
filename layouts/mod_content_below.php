<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $contentBelowCount > 0
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
$modcount = count($contentBelowCount);

$style	= null; // null is essentially the same as 'raw'
$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
		);
$templateHelper->theme->setChunks($chunks, true);

if ($modcount) : ?><div id="content-below" class="content-below"><div class="line below-inner"><?php endif;

if ($modcount > 1 && $contentBelowCount[1]) :
	$templateHelper->renderModules('content-below-1', $style, array());
endif;
if ($modcount > 2 && $contentBelowCount[2]) :
	$templateHelper->renderModules('content-below-2', $style, array());
endif;
if ($modcount > 3 && $contentBelowCount[3]) :
	$templateHelper->renderModules('content-below-3', $style, array());
endif;
if ($modcount > 4 && $contentBelowCount[4]) :
	$templateHelper->renderModules('content-below-4', $style, array());
endif;
if ($modcount > 5 && $contentBelowCount[5]) :
	$templateHelper->renderModules('content-below-5', $style, array());
endif;
if ($modcount > 6 && $contentBelowCount[6]) :
	$templateHelper->renderModules('content-below-6', $style, array());
endif;

if ($modcount) : ?></div></div><?php endif; ?>
