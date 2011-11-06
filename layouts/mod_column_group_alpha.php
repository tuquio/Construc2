<?php
/**
 * Subtemplate loaded if $columnGroupAlphaCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($contentAboveCount);

$style	= 'mod'; // null is essentially the same as 'raw'

?><div class="column-group group-alpha"><?php

if ($modcount > 1 && @$columnGroupCount[1]) :
	$templateHelper->renderModules('column-1', $style);
endif;
if ($modcount > 1 && @$columnGroupCount[2]) :
	$templateHelper->renderModules('column-2', $style);
endif;

?></div>
