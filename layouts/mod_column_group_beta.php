<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $columnGroupBetaCount > 0
 *
 * @package     Construc2
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

$style	= 'mod'; // null is essentially the same as 'raw'

?><div id="group-beta" class="column-group group-beta colcount-<?php echo $columnGroupBetaCount ?>"><?php

if ($columnGroupCount[3]) {
	$templateHelper->renderModules('column-3', $style, array('toggle'=>'odd,even', 'autocols'=>false));
}
if ($columnGroupCount[4]) {
	$templateHelper->renderModules('column-4', $style, array('toggle'=>'odd,even', 'autocols'=>false));
}

?></div><?php
/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */

//cleanup
unset($style);
