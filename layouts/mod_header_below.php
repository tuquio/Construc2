<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $headerBelowCount > 0
 * Demonstrates the use of the 'capture' attribute to save the result in a buffer
 * and only output the container element if ANY module has generated ANY output.
 *
 * @package     Construc2
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
		);

$templateHelper->getTheme()->setChunks($chunks, true);

/* All module output is captured in a single buffer, 'header-above'.
 * This will concatenate ALL active modules as if they were placed in
 * a SINGLE position, but maintains the ordering wthin their position.
 * The 'mod' module chrome is applied to each module output, if any.
 */
if ($headerBelowCount[1]) {
	$templateHelper->renderModules('header-below-1', 'mod', array('capture'=>'header-below'));
}
if ($headerBelowCount[2]) {
	$templateHelper->renderModules('header-below-2', 'mod', array('capture'=>'header-below'));
}
if ($headerBelowCount[3]) {
	$templateHelper->renderModules('header-below-3', 'mod', array('capture'=>'header-below'));
}
if ($headerBelowCount[4]) {
	$templateHelper->renderModules('header-below-4', 'mod', array('capture'=>'header-below'));
}

// AFTER module positions where rendered and captured
// check if the 'header-above' buffer contains data.
// NOTE! An "empty" module might still render its title
//       which getCapture() can't tell apart!!
$modsize = $templateHelper->getCapture('header-above', true);

if ($modsize > 1) { ?><div id="header-below" class="below count-<?php echo $headerBelowCount[0] ?>"><div class="line below-inner"><?php }

	// get the big blob of all the modules in header-above-1 to header-above-6
	echo $templateHelper->getCapture('header-above');

if ($modsize > 1) { ?></div></div><?php }
/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */

//cleanup
unset($style, $modsize, $chunks);

