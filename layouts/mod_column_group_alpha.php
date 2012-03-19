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

$style	= 'mod'; // null is essentially the same as 'raw'

?><div id="group-alpha" class="column-group group-alpha colcount-<?php echo $columnGroupAlphaCount ?>"><?php

if ($columnGroupCount[1]) {
	$templateHelper->renderModules('column-1', $style, array('toggle'=>'odd,even', 'autocols'=>false));
}
if ($columnGroupCount[2]) {
	$templateHelper->renderModules('column-2', $style, array('toggle'=>'odd,even', 'autocols'=>false));
}

?></div><?php
/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */

//cleanup
unset($style);
