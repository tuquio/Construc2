<?php
/**
 * Subtemplate loaded if $navBelowCount > 0
 */
defined('_JEXEC') or die;
?>
<?php if ($navBelowCount[1]) : ?>
	<div id="nav-below-1" class="<?php echo $navBelowClass ?>">
	<?php $templateHelper->renderModules('nav-below-1', 'mod'); ?>
	</div><!-- end nav-below-1 -->
<?php endif; ?>

<?php if ($navBelowCount[2]) : ?>
	<div id="nav-below-2" class="<?php echo $navBelowClass ?>">
	<?php $templateHelper->renderModules('nav-below-2', 'mod'); ?>
	</div><!-- end nav-below-2 -->
<?php endif; ?>

<?php if ($navBelowCount[3]) : ?>
	<div id="nav-below-3" class="<?php echo $navBelowClass ?>">
	<?php $templateHelper->renderModules('nav-below-3', 'mod'); ?>
	</div><!-- end nav-below-3 -->
<?php endif; ?>

<?php if ($navBelowCount[4]) : ?>
	<div id="nav-below-4" class="<?php echo $navBelowClass ?>">
	<?php $templateHelper->renderModules('nav-below-4', 'mod'); ?>
	</div><!-- end nav-below-4 -->
<?php endif; ?>

<?php if ($navBelowCount[5]) : ?>
	<div id="nav-below-5" class="<?php echo $navBelowClass ?>">
	<?php $templateHelper->renderModules('nav-below-5', 'mod'); ?>
	</div><!-- end nav-below-5 -->
<?php endif; ?>

<?php if ($navBelowCount[6]) : ?>
	<div id="nav-below-6" class="<?php echo $navBelowClass ?>">
	<?php $templateHelper->renderModules('nav-below-6', 'mod'); ?>
	</div><!-- end nav-below-6 -->
<?php endif; ?>
