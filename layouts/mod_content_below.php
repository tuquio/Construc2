<?php
/**
 * Subtemplate loaded if $contentBelowCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($contentBelowCount);

$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
);
$templateHelper->setChunks($chunks, true);

if ($modcount > 1 && $contentBelowCount[1]) :
	$templateHelper->renderModules('content-below-1', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 2 && $contentBelowCount[2]) :
	$templateHelper->renderModules('content-below-2', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 3 && $contentBelowCount[3]) :
	$templateHelper->renderModules('content-below-3', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 4 && $contentBelowCount[4]) :
	$templateHelper->renderModules('content-below-4', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 5 && $contentBelowCount[5]) :
	$templateHelper->renderModules('content-below-5', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 6 && $contentBelowCount[6]) :
	$templateHelper->renderModules('content-below-6', null, array('autocols'=>$mod_oocss));
endif;
