<?php
/**
 * Subtemplate loaded if $navBelowCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($navBelowCount);

$style	= 'mod';
$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
);
$templateHelper->setChunks($chunks, true);

if ($modcount) : ?><div id="nav-below" class="nav-below"><?php endif;

if ($modcount > 1 && $navBelowCount[1]) :
	$templateHelper->renderModules('nav-below-1', $style);
endif;
if ($modcount > 2 && $navBelowCount[2]) :
	$templateHelper->renderModules('nav-below-2', $style);
endif;
if ($modcount > 3 && $navBelowCount[3]) :
	$templateHelper->renderModules('nav-below-3', $style);
endif;
if ($modcount > 4 && $navBelowCount[4]) :
	$templateHelper->renderModules('nav-below-4', $style);
endif;
if ($modcount > 5 && $navBelowCount[5]) :
	$templateHelper->renderModules('nav-below-5', $style);
endif;
if ($modcount > 6 && $navBelowCount[6]) :
	$templateHelper->renderModules('nav-below-6', $style);
endif;

if ($modcount) : ?></div><?php endif; ?>
