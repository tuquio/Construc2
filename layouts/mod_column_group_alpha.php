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

/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */
?><div id="group-alpha" class="column-group group-alpha" data-modules="<?php echo $columnGroupAlphaCount ?>"><?php

if ($columnGroupCount[1]) {
	$templateHelper->renderModules('column-1', 'mod', array('toggle'=>'odd,even', 'autocols'=>0));
}
if ($columnGroupCount[2]) {
	$templateHelper->renderModules('column-2', 'mod', array('toggle'=>'odd,even', 'autocols'=>0));
}

?></div><?php
