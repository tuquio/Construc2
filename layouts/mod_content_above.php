<?php
/**
 * Subtemplate loaded if $contentAboveCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($contentAboveCount);

$style	= null; // null is essentially the same as 'raw'
$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
);
$templateHelper->setChunks($chunks, true);

if ($modcount) : ?><div class="line content-above"><?php endif;

if ($modcount > 1 && $contentAboveCount[1]) :
	$templateHelper->renderModules('content-above-1', $style, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 2 && $contentAboveCount[2]) :
	$templateHelper->renderModules('content-above-2', $style, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 3 && $contentAboveCount[3]) :
	$templateHelper->renderModules('content-above-3', $style, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 4 && $contentAboveCount[4]) :
	$templateHelper->renderModules('content-above-4', $style, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 5 && $contentAboveCount[5]) :
	$templateHelper->renderModules('content-above-5', $style, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 6 && $contentAboveCount[6]) :
	$templateHelper->renderModules('content-above-6', $style, array('autocols'=>$mod_oocss));
endif;

if ($modcount) : ?></div><?php endif; ?>
