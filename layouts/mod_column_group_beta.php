<?php
/**
 * Subtemplate loaded if $columnGroupBetaCount > 0
 */
defined('_JEXEC') or die;
?>
<?php if ($columnGroupCount[3]) : ?>
	<div id="column-3" class="<?php echo $columnGroupBetaClass ?>">
	<?php $templateHelper->renderModules('column-3', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end column-3 -->
<?php endif; ?>
<?php if ($columnGroupCount[4]) : ?>
	<div id="column-4" class="<?php echo $columnGroupBetaClass ?>">
	<?php $templateHelper->renderModules('column-4', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end column-4 -->
<?php endif; ?>
