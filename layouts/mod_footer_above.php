<?php
/**
 * Subtemplate loaded if $footerAboveCount > 0
 */
defined('_JEXEC') or die;
?>
<?php if ($footerAboveCount[1]) : ?>
	<div id="footer-above-1" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-1', 'mod'); ?>
	</div><!-- end footer-above-1 -->
<?php endif; ?>
<?php if ($footerAboveCount[2]) : ?>
	<div id="footer-above-2" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-2', 'mod'); ?>
	</div><!-- end footer-above-2 -->
<?php endif; ?>
<?php if ($footerAboveCount[3]) : ?>
	<div id="footer-above-3" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-3', 'mod'); ?>
	</div><!-- end footer-above-3 -->
<?php endif; ?>
<?php if ($footerAboveCount[4]) : ?>
	<div id="footer-above-4" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-4', 'mod'); ?>
	</div><!-- end footer-above-4 -->
<?php endif; ?>
<?php if ($footerAboveCount[5]) : ?>
	<div id="footer-above-5" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-5', 'mod'); ?>
	</div><!-- end footer-above-5 -->
<?php endif; ?>
<?php if ($footerAboveCount[6]) : ?>
	<div id="footer-above-6" class="<?php echo $footerAboveClass ?>">
	<?php $templateHelper->renderModules('footer-above-6', 'mod'); ?>
	</div><!-- end footer-above-6 -->
<?php endif; ?>
