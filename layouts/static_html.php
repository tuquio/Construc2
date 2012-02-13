<?php
/**
 * EXPERIMENTAL! Main Layout to render HTML file fragments (aka chunks).
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2011 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

$chunks = $templateHelper->getStaticHtml($alternateIndexFile);

?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
</head>
<body class="<?php echo $columnLayout ?>">
<?php ConstructTemplateHelper::msieSwatter() ?>
	<div id="footer-push">
	<a id="page-top"></a>

		<div id="header" class="clear clearfix">
			<div class="mod clearfix">
				<h1 id="logo"><a href="<?php echo $this->baseurl ?>/" title="<?php echo $app->getCfg('sitename');?>"><?php echo $app->getCfg('sitename');?></a></h1>
				<?php if ($this->countModules('header')) { ?>
					<jdoc:include type="modules" name="header" style="mod" />
				<?php } ?>
<?php
if (isset($chunks['header'])) {
	echo $templateHelper->loadStaticHtml('header');
}
?>
			</div>
		</div>

		<div id="body-container">
<?php if ($this->countModules('nav')) { ?>
			<div id="nav" class="clear clearfix">
				<jdoc:include type="modules" name="nav" style="raw" />
			</div><!-- end nav-->
<?php } ?>
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
		<jdoc:include type="modules" name="footer" style="mod" />
<?php
if (isset($chunks['header'])) {
	echo $templateHelper->loadStaticHtml('footer');
}
?>
		</div>
	</div>

<?php if ($this->countModules('debug')) { ?>
<jdoc:include type="modules" name="debug" style="raw" />
<?php }

ConstructTemplateHelper::msieSwatter();
ConstructTemplateHelper::afterCompileBody();
?>
</body>
</html>