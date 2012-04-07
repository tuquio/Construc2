<?php defined('_JEXEC') or die;
/**
 * Sub-template loaded if $columnGroupAlphaCount > 0
 *
 * @package     Construc2
 * @subpackage  Layouts
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @var ConstructTemplateHelper $templateHelper
 * @var integer $columnGroupAlphaCount
 * @var array $columnGroupCount
 */

/*
 * if you want to make use of the CSS3 :empty() selector,
 * keep these PHP tags tight close to the HTML markup or a
 * single white space may render your styles useless.
 */
?><div id="group-alpha" class="column-group group-alpha" data-modules="<?php echo $columnGroupAlphaCount ?>"><?php

	if ($columnGroupCount[1]) { ?>
<div id="column-1">
<?php $templateHelper->renderModules('column-1', 'mod', array('toggle'=>'odd,even', 'autocols'=>0)); ?>
</div>
<?php }

	if ($columnGroupCount[2]) { ?>
<div id="column-2">
<?php $templateHelper->renderModules('column-2', 'mod', array('toggle'=>'odd,even', 'autocols'=>0)); ?>
</div>
<?php }

?></div><?php
