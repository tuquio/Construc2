<!DOCTYPE html><?php defined('_JEXEC') or die;
/**
 * Main page layout.
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2011 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @var JSite $app
 * @var ConstructTemplateHelper $templateHelper
 * @var string $cache_manifest
 * @var string $columnLayout
 * @var array $headerAboveCount
 * @var array $headerBelowCount
 * @var array $navBelowCount
 * @var array $contentAboveCount
 * @var array $contentBelowCount
 * @var int $columnGroupAlphaCount
 * @var int $columnGroupBetaCount
 * @var array $footerAboveCount
 * @var bool $enableSwitcher
 */
!defined('WMPATH_LAYOUTS') && define('WMPATH_LAYOUTS', dirname(__FILE__));

?>
<html lang="<?php echo $this->language ?>" dir="<?php echo $this->direction ?>" <?php echo $cache_manifest ?>>
<head>
<jdoc:include type="head" />
</head>
<body class="<?php echo $columnLayout ?>">
<?php ConstructTemplateHelper::msieSwatter(); ?>
	<div id="page-top" class="line <?php echo $templateHelper->getPageAlias(true) ?>">
		<div id="page-head" class="line page-head">
<?php if ($headerAboveCount[0]) : include WMPATH_LAYOUTS. '/mod_header_above.php'; endif; ?>

		<h1 id="logo"><a class="site-link" href="<?php echo $this->baseurl ?>/"><span class="site-name"><?php echo $app->getCfg('sitename');?></span></a></h1>
<?php if ($this->countModules('header')) : $templateHelper->renderModules('header', 'raw'); endif; ?>

		<?php if ($enableSwitcher) : ?>
		<ul id="styleswitcher" class="menu style-switcher">
			<li class="mi switcher wireframe"  data-style="wireframe"><?php echo JText::_('TPL_CONSTRUC2_STYLE_WIREFRAME') ?></li>
			<li class="mi switcher diagnostic" data-style="diagnostic"><?php echo JText::_('TPL_CONSTRUC2_STYLE_DIAGNOSTIC_MODE') ?></li>
			<li class="mi switcher normal"     data-style="normal"><?php echo JText::_('TPL_CONSTRUC2_STYLE_NORMAL_MODE') ?></li>
		</ul>
		<?php endif; ?>

		</div><!-- .page-head -->

		<div id="body-container" class="line <?php echo $templateHelper->getPageAlias() ?>">
<?php if ($headerBelowCount[0]) : include WMPATH_LAYOUTS. '/mod_header_below.php'; endif; ?>

		<?php if ($this->countModules('nav')) : ?>

			<nav id="mainnavi" class="line mainnavi">
			<?php $templateHelper->renderModules('nav'); ?>
			</nav><!-- #mainnavi .mainnavi-->
		<?php endif; ?>

<?php if ($this->countModules('breadcrumbs')) : $templateHelper->renderModules('breadcrumbs'); endif; ?>

			<div class="line content-container">
<?php if ($navBelowCount[0]) : include WMPATH_LAYOUTS. '/mod_nav_below.php'; endif; ?>

				<div id="content" class="line content-main">

<?php if ($this->getBuffer('message')) : ?><jdoc:include type="message" /><?php endif; ?>

<?php if ($contentAboveCount[0]) :	include WMPATH_LAYOUTS. '/mod_content_above.php'; endif; ?>
	<section class="line component">
	<jdoc:include type="component" />
<?php if ($contentBelowCount[0]) : include WMPATH_LAYOUTS. '/mod_content_below.php'; endif; ?>
	</section>

<?php if ($columnGroupAlphaCount > 0) : include WMPATH_LAYOUTS. '/mod_column_group_alpha.php'; endif; ?>
				</div><!-- end content-main -->

<?php if ($columnGroupBetaCount > 0) : include WMPATH_LAYOUTS. '/mod_column_group_beta.php'; endif; ?>

<?php if ($footerAboveCount[0]) : include WMPATH_LAYOUTS. '/mod_footer_above.php'; endif; ?>
			</div><!-- .content-container -->
		</div><!-- #body-container -->
	</div><!-- #page-top -->

	<footer id="page-foot" class="line page-foot"><div class="foot-inner">
<?php /* echo JText::_('TPL_CONSTRUC2_JUMP_BACK_TO_TOP') */ ?>
<?php if ($this->countModules('syndicate')) : ?>
		<div class="mod syndicate">
	<?php $templateHelper->renderModules('syndicate'); ?>
		</div><!-- .syndicate -->
<?php endif; ?>
<?php $templateHelper->renderModules('footer', 'raw'); ?>
	</div></footer><!-- end footer -->
<?php
if ($this->countModules('debug')) : $templateHelper->renderModules('debug', 'raw'); endif;
if ($this->countModules('analytics')) : $templateHelper->renderModules('analytics', 'raw'); endif;

ConstructTemplateHelper::msieSwatter();
?>
	</body>
</html>
