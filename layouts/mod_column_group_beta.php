<?php
/**
 * Subtemplate loaded if $columnGroupBetaCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($columnGroupCount);

$style	= 'mod'; // null is essentially the same as 'raw'

?><div class="column-group group-beta"><?php

if ($modcount > 1 && $columnGroupCount[3]) :
	$templateHelper->renderModules('column-3', $style, array('toggle'=>'odd,even'));
endif;
if ($modcount > 1 && $columnGroupCount[4]) :
	$templateHelper->renderModules('column-4', $style, array('toggle'=>'odd,even'));
endif;

?></div>
