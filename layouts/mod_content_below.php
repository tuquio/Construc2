<?php
/**
 * Subtemplate loaded if $contentBelowCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($headerBelowCount);
?>
<?php if ($modcount > 1 && $contentBelowCount[1]) : ?>
	<div id="content-below-1" class="<?php echo $contentBelowClass ?>">
	<?php $templateHelper->renderModules('content-below-1', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-below-1 -->
<?php endif; ?>
<?php if ($modcount > 2 && $contentBelowCount[2]) : ?>
	<div id="content-below-2" class="<?php echo $contentBelowClass ?>">
	<?php $templateHelper->renderModules('content-below-2', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-below-2 -->
<?php endif; ?>
<?php if ($modcount > 3 && $contentBelowCount[3]) : ?>
	<div id="content-below-3" class="<?php echo $contentBelowClass ?>">
	<?php $templateHelper->renderModules('content-below-3', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-below-3 -->
<?php endif; ?>
<?php if ($modcount > 4 && $contentBelowCount[4]) : ?>
	<div id="content-below-4" class="<?php echo $contentBelowClass ?>">
	<?php $templateHelper->renderModules('content-below-4', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-below-4 -->
<?php endif; ?>
<?php if ($modcount > 5 && $contentBelowCount[5]) : ?>
	<div id="content-below-5" class="<?php echo $contentAboveClass ?>">
	<?php $templateHelper->renderModules('content-below-5', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-below-5 -->
<?php endif; ?>
<?php if ($modcount > 6 && $contentBelowCount[6]) : ?>
	<div id="content-below-6" class="<?php echo $contentAboveClass ?>">
	<?php $templateHelper->renderModules('content-below-6', 'jexhtml', array('oocss'=>'mod')); ?>
	</div><!-- end content-below-6 -->
<?php endif; ?>
