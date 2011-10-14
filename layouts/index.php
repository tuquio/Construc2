<?php
/**
 * @package     Templates
 * @subpackage  Layouts
 * @author		Joomla Engineering http://joomlaengineering.com
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" <?php echo $cache_manifest ?>>
<head>
<jdoc:include type="head" />
</head>

<body class="<?php echo $columnLayout ?>">
<!--[if IE 6]><div class="msie ie6 ltie7 ltie8 ltie9 ltie10"><![endif]-->
<!--[if IE 7]><div class="msie ie7 ltie8 ltie9 ltie10"><![endif]-->
<!--[if IE 8]><div class="msie ie8 ltie9 ltie10"><![endif]-->
<!--[if IE 9]><div class="msie ie9 ltie10"><![endif]-->
	<div id="page-top">
		<div id="header" class="line page-head">
	<?php if ($headerAboveCount) : ?>
		<header class="line above">
	<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_header_above.php' ?>
		</header><!-- end header-above -->
	<?php endif; ?>

		<h1 id="logo"><a href="<?php echo $this->baseurl ?>/"><span><?php echo $app->getCfg('sitename');?></span></a></h1>
		<?php if ($showDateContainer) : ?>
		<div class="date-container"><?php echo $templateHelper->dateContainer(); ?></div>
		<?php endif; ?>
		<?php if ($showDiagnostics) : ?>
		<ul id="diagnostics" class="menu hmenu">
			<li><?php echo $currentComponent; ?></li>
			<?php if($articleId)	echo '<li>article-'.$articleId.'</li>'; ?>
			<?php if($itemId)		echo '<li>item-'.$itemId.'</li>'; ?>
			<?php if($catId)		echo '<li>category-'.$catId.'</li>'; ?>
		</ul>
		<?php endif; ?>
		<?php
		if ($this->countModules('header')) :
			$templateHelper->renderModules('header', 'jexhtml');
		endif;
		?>
		<?php if ($enableSwitcher) : ?>
		<ul id="style-switch" class="menu hmenu" onclick="toggleStylesheet()">
			<li><a href="#" onclick="setActiveStyleSheet('wireframe'); return false;" title="Wireframe"><?php echo JText::_('TPL_CONSTRUCT_STYLE_WIREFRAME') ?></a></li>
			<li><a href="#" onclick="setActiveStyleSheet('diagnostic'); return false;" title="Diagnostic"><?php echo JText::_('TPL_CONSTRUCT_STYLE_DIAGNOSTIC_MODE') ?></a></li>
			<li><a href="#" onclick="setActiveStyleSheet('normal'); return false;" title="Normal"><?php echo JText::_('TPL_CONSTRUCT_STYLE_NORMAL_MODE') ?></a></li>
		</ul>
		<?php endif; ?>
		</div><!-- .page-head -->

		<div id="body-container">
		<?php if ($headerBelowCount) : ?>
			<header class="line below">
		<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_header_below.php' ?>
			</header><!-- .below  -->
		<?php endif; ?>
		<?php if ($this->countModules('nav')) : ?>
			<nav id="nav" class="line mainnavi" role="navigation">
			<?php $templateHelper->renderModules('nav'); ?>
			</nav><!-- #nav .mainnavi-->
		<?php endif; ?>
<?php
		if ($this->countModules('breadcrumbs')) :
			$templateHelper->renderModules('breadcrumbs');
		endif;
?>
			<div class="line content-container">
			<?php if ($navBelowCount) : ?>
				<div id="nav-below" class="nav-below">
				<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_nav_below.php' ?>
				</div><!-- .nav-below -->
			<?php endif; ?>
				<div id="content" class="load-first">
					<div class="content-main">
					<?php if ($contentAboveCount) : ?>
						<div class="line content-above">
						<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_content_above.php' ?>
						</div><!--.content-above -->
					<?php endif; ?>
					<?php if ($this->getBuffer('message')) : ?>
						<jdoc:include type="message" />
					<?php endif; ?>
<div class="line"><jdoc:include type="component" /></div>
					<?php if ($contentBelowCount) : ?>
						<div class="line content-below">
						<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_content_below.php' ?>
						</div><!-- .content-below -->
					<?php endif; ?>
					</div><!-- .content-main -->
				<?php if ($columnGroupAlphaCount) : ?>
					<div class="column-group group-alpha">
					<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_column_group_alpha.php' ?>
					</div><!-- .column-group.group-alpha -->
				<?php endif; ?>
				</div><!-- end load-first -->
			<?php if ($columnGroupBetaCount) : ?>
				<div class="column-group group-beta">
				<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_column_group_beta.php' ?>
				</div><!-- .column-group.group-beta -->
			<?php endif; ?>
			<?php if ($footerAboveCount) : ?>
				<div class="line footer-above">
				<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_footer_above.php' ?>
				</div><!-- .footer-above -->
			<?php endif; ?>
			</div><!-- .content-container -->
		</div><!-- #body-container -->
	</div><!-- #page-top -->

	<footer id="footer" class="line page-foot">
	<?php /* echo JText::_('TPL_CONSTRUCT_JUMP_BACK_TO_TOP') */ ?>
	<?php if ($this->countModules('syndicate')) : ?>
		<div class="mod syndicate">
		<?php $templateHelper->renderModules('syndicate'); ?>
		</div><!-- .syndicate -->
	<?php endif; ?>
	<?php $templateHelper->renderModules('footer', 'jexhtml'); ?>
	</footer><!-- end footer -->
<?php
if ($this->countModules('debug')) :
	$templateHelper->renderModules('debug', 'raw');
endif;

if ($this->countModules('analytics')) :
	$templateHelper->renderModules('analytics');
endif;
?>
<!--[if IE]></div><![endif]-->
	</body>
</html>
