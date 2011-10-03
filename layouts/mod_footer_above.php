<?php
/**
 * Subtemplate loaded if $footerAboveCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($footerAboveCount);

$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
);
$templateHelper->setChunks($chunks, true);

if ($modcount > 1 && $footerAboveCount[1]) :
	$templateHelper->renderModules('footer-above-1', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 2 && $footerAboveCount[2]) :
	$templateHelper->renderModules('footer-above-2', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 3 && $footerAboveCount[3]) :
	$templateHelper->renderModules('footer-above-3', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 4 && $footerAboveCount[4]) :
	$templateHelper->renderModules('footer-above-4', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 5 && $footerAboveCount[5]) :
	$templateHelper->renderModules('footer-above-5', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 6 && $footerAboveCount[6]) :
	$templateHelper->renderModules('footer-above-6', null, array('autocols'=>$mod_oocss));
endif;
