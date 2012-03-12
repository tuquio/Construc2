<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $columnGroupAlphaCount > 0
 *
 * @package     Construc2
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
$modcount = $templateHelper->numModules('group-alpha');

$style	= 'mod'; // null is essentially the same as 'raw'

?><div class="column-group group-alpha colcount-<?php echo $columnGroupAlphaCount ?>""><?php

if ($modcount > 1 && $columnGroupCount[1]) :
	$templateHelper->renderModules('column-1', $style, array('capture'=>'group-alpha', 'toggle'=>'odd,even', 'autocols'=>false));
endif;
if ($modcount > 1 && $columnGroupCount[2]) :
	$templateHelper->renderModules('column-2', $style, array('capture'=>'group-alpha', 'toggle'=>'odd,even', 'autocols'=>false));
endif;

?></div><?php
/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */