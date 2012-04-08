<?php defined('_JEXEC') or die;
/**
 * Sub-template loaded if $columnGroupBetaCount > 0
 *
 * @package     Construc2
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @var ConstructTemplateHelper $templateHelper
 * @var integer $columnGroupBetaCount
 * @var array $columnGroupCount
 */

/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */
?><aside id="group-beta" class="column-group group-beta" data-modules="<?php echo $columnGroupBetaCount ?>"><?php
	if ($columnGroupCount[3]) { 
?><div id="column-3"><?php $templateHelper->renderModules('column-3', 'mod', array('toggle'=>'odd,even', 'autocols'=>0));?></div><?php 
	}

	if ($columnGroupCount[4]) { 
?><div id="column-4"><?php $templateHelper->renderModules('column-4', 'mod', array('toggle'=>'odd,even', 'autocols'=>0));?></div><?php 
	}

?></aside><?php
