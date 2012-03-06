<?php
/**
 * Subtemplate loaded if $columnGroupBetaCount > 0
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

?><div class="column-group group-beta"><?php

if ($modcount > 1 && $columnGroupCount[3]) :
	$templateHelper->renderModules('column-3', $style, array('toggle'=>'odd,even', 'autocols'=>false));
endif;
if ($modcount > 1 && $columnGroupCount[4]) :
	$templateHelper->renderModules('column-4', $style, array('toggle'=>'odd,even', 'autocols'=>false));
endif;

?></div>
