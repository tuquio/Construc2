<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $headerAboveCount > 0
 * Demonstrates the use of the 'capture' attribute to save the result in a buffer
 * and only output the container element if ANY module has generated ANY output.
 *
 * @package     Construc2
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/* All module output is captured in a single buffer, 'header-above'.
 * This will concatenate ALL active modules as if they were placed in
 * a SINGLE position, but maintains the ordering wthin their position.
 * The 'mod' module chrome is applied to each module output, if any.
 */

$group = 'header-above';

if ($headerAboveCount[1]) {
	$templateHelper->renderModules('header-above-1', 'raw', array('capture'=>true));
}
if ($headerAboveCount[2]) {
	$templateHelper->renderModules('header-above-2', 'raw', array('capture'=>true));
}
if ($headerAboveCount[3]) {
	$templateHelper->renderModules('header-above-3', 'raw', array('capture'=>true));
}
if ($headerAboveCount[4]) {
	$templateHelper->renderModules('header-above-4', 'raw', array('capture'=>true));
}

// AFTER module positions where rendered and captured
// check if the groups buffer contains data.
// NOTE! An "empty" module might still render its title
//       which getCapture() can't tell apart!!
$modsize = $templateHelper->getCapture($group, true);

if ($modsize) { ?><div id="header-above" class="line above" data-modules="<?php echo $headerAboveCount[0] ?>"><div class="above-inner"><?php }

	// get the big blob of all the modules in header-above-1 to header-above-6
	echo $templateHelper->getCapture($group);

if ($modsize) { ?></div></div><?php }
/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */

//cleanup
unset($style, $modsize);

