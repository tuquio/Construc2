<?php
/**
 * Subtemplate loaded if $columnGroupAlphaCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($columnGroupCount);

$style	= 'mod'; // null is essentially the same as 'raw'

?><div class="column-group group-alpha"><?php

if ($modcount > 1 && $columnGroupCount[1]) :
	$templateHelper->renderModules('column-1', $style, array('toggle'=>'odd,even'));
endif;
if ($modcount > 1 && $columnGroupCount[2]) :
	$templateHelper->renderModules('column-2', $style, array('toggle'=>'odd,even'));
endif;

?></div>
