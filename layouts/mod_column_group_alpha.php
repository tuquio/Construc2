<?php
/**
 * Subtemplate loaded if $columnGroupAlphaCount > 0
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2011 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
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
