<?php
/**
 * Subtemplate loaded if $columnGroupAlphaCount > 0
 */
defined('_JEXEC') or die;
?>
<?php if ($columnGroupCount[1]) : ?>
	<div id="column-1" class="<?php echo $columnGroupAlphaClass ?>">
	<?php $templateHelper->renderModules('column-1', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end column-1 -->
<?php endif; ?>
<?php if ($columnGroupCount[2]) : ?>
	<div id="column-2" class="<?php echo $columnGroupAlphaClass ?>">
	<?php $templateHelper->renderModules('column-2', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end column-2 -->
<?php endif; ?>
