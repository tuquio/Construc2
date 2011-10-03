<?php
/**
 * Subtemplate loaded if $columnGroupAlphaCount > 0
 */
defined('_JEXEC') or die;

if (@$columnGroupCount[1]) :
	$templateHelper->renderModules('column-1', 'mod');
endif;
if (@$columnGroupCount[2]) :
	$templateHelper->renderModules('column-2', 'mod');
endif;
