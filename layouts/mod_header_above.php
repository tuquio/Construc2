<?php
/**
 * Subtemplate loaded if $headerAboveCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($headerAboveCount);

$style	= null; // null is essentially the same as 'raw'
$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
		);
$templateHelper->setChunks($chunks, true);

if ($modcount) : ?><div class="line above"><?php endif;

if ($modcount > 1 && $headerAboveCount[1]) :
	$templateHelper->renderModules('header-above-1', $style, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 2 && $headerAboveCount[2]) :
	$templateHelper->renderModules('header-above-2', $style, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 3 && $headerAboveCount[3]) :
	$templateHelper->renderModules('header-above-3', $style, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 4 && $headerAboveCount[4]) :
	$templateHelper->renderModules('header-above-4', $style, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 5 && $headerAboveCount[5]) :
	$templateHelper->renderModules('header-above-5', $style, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 6 && $headerAboveCount[6]) :
	$templateHelper->renderModules('header-above-6', $style, array('autocols'=>$mod_oocss));
endif;

if ($modcount) : ?></div><?php endif; ?>
