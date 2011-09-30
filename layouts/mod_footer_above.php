<?php
/**
 * Subtemplate loaded if $footerAboveCount > 0
 */
defined('_JEXEC') or die;
$modcount = count($headerBelowCount);
?>
<?php if ($modcount > 1 && $footerAboveCount[1]) : ?>
	<div id="footer-above-1" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-1', 'mod'); ?>
	</div><!-- end footer-above-1 -->
<?php endif; ?>
<?php if ($modcount > 2 && $footerAboveCount[2]) : ?>
	<div id="footer-above-2" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-2', 'mod'); ?>
	</div><!-- end footer-above-2 -->
<?php endif; ?>
<?php if ($modcount > 3 && $footerAboveCount[3]) : ?>
	<div id="footer-above-3" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-3', 'mod'); ?>
	</div><!-- end footer-above-3 -->
<?php endif; ?>
<?php if ($modcount > 4 && $footerAboveCount[4]) : ?>
	<div id="footer-above-4" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-4', 'mod'); ?>
	</div><!-- end footer-above-4 -->
<?php endif; ?>
<?php if ($modcount > 5 && $footerAboveCount[5]) : ?>
	<div id="footer-above-5" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-5', 'mod'); ?>
	</div><!-- end footer-above-5 -->
<?php endif; ?>
<?php if ($modcount > 6 && $footerAboveCount[6]) : ?>
	<div id="footer-above-6" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-6', 'mod'); ?>
	</div><!-- end footer-above-6 -->
<?php endif; ?>
