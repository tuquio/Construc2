<?php
/**
 * Subtemplate loaded if $navBelowCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($headerBelowCount);

$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
);
$templateHelper->setChunks($chunks, true);

if ($modcount > 1 && $navBelowCount[1]) :
	$templateHelper->renderModules('nav-below-1', 'mod');
endif;
if ($modcount > 2 && $navBelowCount[2]) :
	$templateHelper->renderModules('nav-below-2', 'mod');
endif;
if ($modcount > 3 && $navBelowCount[3]) :
	$templateHelper->renderModules('nav-below-3', 'mod');
endif;
if ($modcount > 4 && $navBelowCount[4]) :
	$templateHelper->renderModules('nav-below-4', 'mod');
endif;
if ($modcount > 5 && $navBelowCount[5]) :
	$templateHelper->renderModules('nav-below-5', 'mod');
endif;
if ($modcount > 6 && $navBelowCount[6]) :
	$templateHelper->renderModules('nav-below-6', 'mod');
endif;
