<?php
/**
 * Subtemplate loaded if $headerAboveCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($headerBelowCount);
?>
<?php if ($modcount > 1 && $headerAboveCount[1]) : ?>
	<div id="header-above-1" class="<?php echo $headerAboveClass ?>">
	<?php $templateHelper->renderModules('header-above-1', 'mod'); ?>
	</div><!-- end header-above-1 -->
<?php endif; ?>
<?php if ($modcount > 2 && $headerAboveCount[2]) : ?>
	<div id="header-above-2" class="<?php echo $headerAboveClass ?>">
	<?php $templateHelper->renderModules('header-above-2', 'mod'); ?>
	</div><!-- end header-above-2 -->
<?php endif; ?>
<?php if ($modcount > 3 && $headerAboveCount[3]) : ?>
	<div id="header-above-3" class="<?php echo $headerAboveClass ?>">
	<?php $templateHelper->renderModules('header-above-3', 'mod'); ?>
	</div><!-- end header-above-3 -->
<?php endif; ?>
<?php if ($modcount > 4 && $headerAboveCount[4]) : ?>
	<div id="header-above-4" class="<?php echo $headerAboveClass ?>">
	<?php $templateHelper->renderModules('header-above-4', 'mod'); ?>
	</div><!-- end header-above-4 -->
<?php endif; ?>
<?php if ($modcount > 5 && $headerAboveCount[5]) : ?>
	<div id="header-above-5" class="<?php echo $headerAboveClass ?>">
	<?php $templateHelper->renderModules('header-above-5', 'mod'); ?>
	</div><!-- end header-above-5 -->
<?php endif; ?>
<?php if ($modcount > 6 && $headerAboveCount[6]) : ?>
	<div id="header-above-6" class="<?php echo $headerAboveClass ?>">
	<?php $templateHelper->renderModules('header-above-6', 'mod'); ?>
	</div><!-- end header-above-6 -->
<?php endif; ?>
