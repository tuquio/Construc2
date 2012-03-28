<?php defined('_JEXEC') or die;
/**
 * Subtemplate loaded if $navBelowCount > 0
 *
 * @package     Construc2
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
$modcount = $templateHelper->numModules('nav-below');

$style	= 'raw';

/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */
if ($modcount) : ?><div id="nav-below" class="line below count-<?php echo $navBelowCount[0] ?>"><div class="below-inner"><?php endif;

if ($modcount > 1 && $navBelowCount[1]) {
	$templateHelper->renderModules('nav-below-1', $style);
}
if ($modcount > 2 && $navBelowCount[2]) {
	$templateHelper->renderModules('nav-below-2', $style);
}
if ($modcount > 3 && $navBelowCount[3]) {
	$templateHelper->renderModules('nav-below-3', $style);
}
if ($modcount > 4 && $navBelowCount[4]) {
	$templateHelper->renderModules('nav-below-4', $style);
}

if ($modcount) : ?></div></div><?php endif;

//cleanup
unset($style, $modcount, $chunks);

