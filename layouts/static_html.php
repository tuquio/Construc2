<?php
/**
 * @package		Template Framework for Joomla! 1.6
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C)2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

$chunks = $templateHelper->getStaticHtml($alternateIndexFile);

?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
</head>

<body class="<?php echo $columnLayout; if($useStickyFooter) echo ' sticky-footer'; ?>">
<!--[if IE 6]><div class="ie6 ltie7 ltie8 ltie9 ltie10"><![endif]-->
<!--[if IE 7]><div class="ie7 ltie8 ltie9 ltie10"><![endif]-->
<!--[if IE 8]><div class="ie8 ltie9 ltie10"><![endif]-->
<!--[if IE 9]><div class="ie9 ltie10"><![endif]-->
	<div id="footer-push">
	<a id="page-top"></a>

		<div id="header" class="clear clearfix">
			<div class="mod clearfix">
				<h1 id="logo"><a href="<?php echo $this->baseurl ?>/" title="<?php echo $app->getCfg('sitename');?>"><?php echo $app->getCfg('sitename');?></a></h1>
				<?php if ($this->countModules('header')) : ?>
					<jdoc:include type="modules" name="header" style="jexhtml" />
				<?php endif; ?>
<?php if (isset($chunks['header'])) : ?>
<?php echo $templateHelper->loadStaticHtml('header') ?>
<?php endif; ?>
			</div>
		</div>

		<div id="body-container">
<?php if ($this->countModules('nav')) : ?>
			<div id="nav" class="clear clearfix">
				<jdoc:include type="modules" name="nav" style="raw" />
			</div><!-- end nav-->
<?php endif; ?>
			<div id="content-container" class="clear clearfix">
				<div id="load-first" class="clearfix">
					<a id="content"></a>
					<div id="content-main">
						<div class="mod">
<?php echo $templateHelper->loadStaticHtml() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="footer" class="clear clearfix">
		<div class="mod clearfix">
		<jdoc:include type="modules" name="footer" style="jexhtml" />

<?php if (isset($chunks['header'])) : ?>
<?php echo $templateHelper->loadStaticHtml('footer') ?>
<?php endif; ?>
		</div>
	</div>

<?php if ($this->countModules('debug')) : ?>
<jdoc:include type="modules" name="debug" style="raw" />
<?php endif; ?>

<!--[if IE lte 9]></div><![endif]-->
</body>
</html>