<?php
/**
 * Subtemplate loaded if $contentAboveCount > 0
 */
defined('_JEXEC') or die;
?>
<?php if ($contentAboveCount[1]) : ?>
	<div id="content-above-1" class="<?php echo $contentAboveClass ?>">
	<?php $templateHelper->renderModules('content-above-1', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-above-1 -->
<?php endif; ?>

<?php if ($contentAboveCount[2]) : ?>
	<div id="content-above-2" class="<?php echo $contentAboveClass ?>">
	<?php $templateHelper->renderModules('content-above-2', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-above-2 -->
<?php endif; ?>

<?php if ($contentAboveCount[3]) : ?>
	<div id="content-above-3" class="<?php echo $contentAboveClass ?>">
	<?php $templateHelper->renderModules('content-above-3', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-above-3 -->
<?php endif; ?>

<?php if ($contentAboveCount[4]) : ?>
	<div id="content-above-4" class="<?php echo $contentAboveClass ?>">
	<?php $templateHelper->renderModules('content-above-4', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-above-4 -->
<?php endif; ?>

<?php if ($contentAboveCount[5]) : ?>
	<div id="content-above-5" class="<?php echo $contentAboveClass ?>">
	<?php $templateHelper->renderModules('content-above-5', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-above-5 -->
<?php endif; ?>

<?php if ($contentAboveCount[6]) : ?>
	<div id="content-above-6" class="<?php echo $contentAboveClass ?>">
	<?php $templateHelper->renderModules('content-above-6', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-above-6 -->
<?php endif; ?>
