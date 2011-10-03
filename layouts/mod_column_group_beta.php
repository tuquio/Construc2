<?php
/**
 * Subtemplate loaded if $columnGroupBetaCount > 0
 */
defined('_JEXEC') or die;

if (@$columnGroupCount[3]) :
	$templateHelper->renderModules('column-3', 'withevent');
endif;
if (@$columnGroupCount[4]) :
	$templateHelper->renderModules('column-4', 'withevent');
endif;
