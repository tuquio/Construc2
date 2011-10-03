<?php
/**
 * Subtemplate loaded if $headerBelowCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($headerBelowCount);

$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
);
$templateHelper->setChunks($chunks, true);

if ($modcount > 1 && $headerBelowCount[1]) :
	$templateHelper->renderModules('header-below-1', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 2 && $headerBelowCount[2]) :
	$templateHelper->renderModules('header-below-2', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 3 && $headerBelowCount[3]) :
	$templateHelper->renderModules('header-below-3', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 4 && $headerBelowCount[4]) :
	$templateHelper->renderModules('header-below-4', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 5 && $headerBelowCount[5]) :
	$templateHelper->renderModules('header-below-5', null, array('autocols'=>$mod_oocss));
endif;
if ($modcount > 6 && $headerBelowCount[6]) :
	$templateHelper->renderModules('header-below-6', null, array('autocols'=>$mod_oocss));
endif;
