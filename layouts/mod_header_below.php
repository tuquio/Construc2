<?php
/**
 * Subtemplate loaded if $headerBelowCount > 0
 */
defined('_JEXEC') or die;
?>
<?php if ($headerBelowCount[1]) : ?>
	<div id="header-below-1" class="<?php echo $headerBelowClass ?>">
	<?php $templateHelper->renderModules('header-below-1', 'mod'); ?>
	</div><!-- end header-below-1 -->
<?php endif; ?>

<?php if ($headerBelowCount[2]) : ?>
	<div id="header-below-2" class="<?php echo $headerBelowClass ?>">
	<?php $templateHelper->renderModules('header-below-2', 'mod'); ?>
	</div><!-- end header-below-2 -->
<?php endif; ?>

<?php if ($headerBelowCount[3]) : ?>
	<div id="header-below-3" class="<?php echo $headerBelowClass ?>">
	<?php $templateHelper->renderModules('header-below-3', 'mod'); ?>
	</div><!-- end header-below-3 -->
<?php endif; ?>

<?php if ($headerBelowCount[4]) : ?>
	<div id="header-below-4" class="<?php echo $headerBelowClass ?>">
	<?php $templateHelper->renderModules('header-below-4', 'mod'); ?>
	</div><!-- end header-below-4 -->
<?php endif; ?>

<?php if ($headerBelowCount[5]) : ?>
	<div id="header-below-5" class="<?php echo $headerBelowClass ?>">
	<?php $templateHelper->renderModules('header-below-5', 'mod'); ?>
	</div><!-- end header-below-5 -->
<?php endif; ?>

<?php if ($headerBelowCount[6]) : ?>
	<div id="header-below-6" class="<?php echo $headerBelowClass ?>">
	<?php $templateHelper->renderModules('header-below-6', 'mod'); ?>
	</div><!-- end header-below-6 -->
<?php endif; ?>
