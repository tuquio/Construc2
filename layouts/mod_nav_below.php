<?php
/**
 * Subtemplate loaded if $navBelowCount > 0
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author		Joomla Engineering http://joomlaengineering.com
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;
$modcount = count($navBelowCount);

$style	= 'mod';
$chunks = array(
		'module_before' => '<div id="{position}" class="{class}">',
		'module_after'  => '</div>'
);
$templateHelper->setChunks($chunks, true);

if ($modcount) : ?><div id="nav-below" class="nav-below"><div class="line below-inner"><?php endif;

if ($modcount > 1 && $navBelowCount[1]) :
	$templateHelper->renderModules('nav-below-1', $style);
endif;
if ($modcount > 2 && $navBelowCount[2]) :
	$templateHelper->renderModules('nav-below-2', $style);
endif;
if ($modcount > 3 && $navBelowCount[3]) :
	$templateHelper->renderModules('nav-below-3', $style);
endif;
if ($modcount > 4 && $navBelowCount[4]) :
	$templateHelper->renderModules('nav-below-4', $style);
endif;
if ($modcount > 5 && $navBelowCount[5]) :
	$templateHelper->renderModules('nav-below-5', $style);
endif;
if ($modcount > 6 && $navBelowCount[6]) :
	$templateHelper->renderModules('nav-below-6', $style);
endif;

if ($modcount) : ?></div><?php endif; ?>
