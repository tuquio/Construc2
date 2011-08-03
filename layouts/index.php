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

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
</head>

<body class="<?php echo $columnLayout; if($useStickyFooter) echo ' sticky-footer'; ?>">
<!--[if IE 6]><div class="ie6 ltie7 ltie8 ltie9 ltie10"><![endif]-->
<!--[if IE 7]><div class="ie7 ltie8 ltie9 ltie10"><![endif]-->
<!--[if IE 8]><div class="ie8 ltie9 ltie10"><![endif]-->
<!--[if IE 9]><div class="ie9 ltie10"><![endif]-->
	<div id="footer-push"><a id="page-top" name="page-top"></a>

	<?php if ($headerAboveCount) : ?>
		<header class="line above">
		<?php if ($headerAboveCount[1]) : ?>
			<div id="header-above-1" class="<?php echo $headerAboveClass ?>">
			<jdoc:include type="modules" name="header-above-1" style="mod" />
			</div><!-- end header-above-1 -->
		<?php endif; ?>

		<?php if ($headerAboveCount[2]) : ?>
			<div id="header-above-2" class="<?php echo $headerAboveClass ?>">
			<jdoc:include type="modules" name="header-above-2" style="mod" />
			</div><!-- end header-above-2 -->
		<?php endif; ?>

		<?php if ($headerAboveCount[3]) : ?>
			<div id="header-above-3" class="<?php echo $headerAboveClass ?>">
			<jdoc:include type="modules" name="header-above-3" style="mod" />
			</div><!-- end header-above-3 -->
		<?php endif; ?>

		<?php if ($headerAboveCount[4]) : ?>
			<div id="header-above-4" class="<?php echo $headerAboveClass ?>">
			<jdoc:include type="modules" name="header-above-4" style="mod" />
			</div><!-- end header-above-4 -->
		<?php endif; ?>

		<?php if ($headerAboveCount[5]) : ?>
			<div id="header-above-5" class="<?php echo $headerAboveClass ?>">
			<jdoc:include type="modules" name="header-above-5" style="mod" />
			</div><!-- end header-above-5 -->
		<?php endif; ?>

		<?php if ($headerAboveCount[6]) : ?>
			<div id="header-above-6" class="<?php echo $headerAboveClass ?>">
			<jdoc:include type="modules" name="header-above-6" style="mod" />
			</div><!-- end header-above-6 -->
		<?php endif; ?>
		</header><!-- end header-above -->
	<?php endif; ?>

		<div id="header" class="line">
			<div class="date-container">
			<?php echo $templateHelper->dateContainer(); ?>
			</div>
			<?php if ($showDiagnostics) : ?>
			<ul id="diagnostics" class="menu hmenu">
				<li><?php echo $currentComponent; ?></li>
				<?php if($articleId)	echo '<li>article-'.$articleId.'</li>'; ?>
				<?php if($itemId)		echo '<li>item-'.$itemId.'</li>'; ?>
				<?php if($catId)		echo '<li>category-'.$catId.'</li>'; ?>
			</ul>
			<?php endif; ?>
			<h1 id="logo"><a href="<?php echo $this->baseurl ?>/" title="<?php echo $app->getCfg('sitename');?>"><?php echo $app->getCfg('sitename');?></a></h1>
			<?php if ($this->countModules('header')) : ?>
				<jdoc:include type="modules" name="header" style="jexhtml" />
			<?php endif; ?>
			<?php if ($enableSwitcher) : ?>
			<ul id="style-switch" class="menu hmenu" onclick="toggleStylesheet()">
				<li><a href="#" onclick="setActiveStyleSheet('wireframe'); return false;" title="Wireframe"><?php echo JText::_('TPL_JE_CONSTRUCT_COMMUNITY_STYLE_WIREFRAME') ?></a></li>
				<li><a href="#" onclick="setActiveStyleSheet('diagnostic'); return false;" title="Diagnostic"><?php echo JText::_('TPL_JE_CONSTRUCT_COMMUNITY_STYLE_DIAGNOSTIC_MODE') ?></a></li>
				<li><a href="#" onclick="setActiveStyleSheet('normal'); return false;" title="Normal"><?php echo JText::_('TPL_JE_CONSTRUCT_COMMUNITY_STYLE_NORMAL_MODE') ?></a></li>
			</ul>
			<?php endif; ?>
		</div><!-- end header-->

		<div id="body-container">

		<?php if ($headerBelowCount) : ?>
			<header class="line below">
			<?php if ($headerBelowCount[1]) : ?>
				<div id="header-below-1" class="<?php echo $headerBelowClass ?>">
				<jdoc:include type="modules" name="header-below-1" style="mod"/>
				</div><!-- end header-below-1 -->
			<?php endif; ?>

			<?php if ($headerBelowCount[2]) : ?>
				<div id="header-below-2" class="<?php echo $headerBelowClass ?>">
				<jdoc:include type="modules" name="header-below-2" style="mod"/>
				</div><!-- end header-below-2 -->
			<?php endif; ?>

			<?php if ($headerBelowCount[3]) : ?>
				<div id="header-below-3" class="<?php echo $headerBelowClass ?>">
				<jdoc:include type="modules" name="header-below-3" style="mod"/>
				</div><!-- end header-below-3 -->
			<?php endif; ?>

			<?php if ($headerBelowCount[4]) : ?>
				<div id="header-below-4" class="<?php echo $headerBelowClass ?>">
				<jdoc:include type="modules" name="header-below-4" style="mod"/>
				</div><!-- end header-below-4 -->
			<?php endif; ?>

			<?php if ($headerBelowCount[5]) : ?>
				<div id="header-below-5" class="<?php echo $headerBelowClass ?>">
				<jdoc:include type="modules" name="header-below-5" style="mod"/>
				</div><!-- end header-below-5 -->
			<?php endif; ?>

			<?php if ($headerBelowCount[6]) : ?>
				<div id="header-below-6" class="<?php echo $headerBelowClass ?>">
				<jdoc:include type="modules" name="header-below-6" style="mod"/>
				</div><!-- end header-below-6 -->
			<?php endif; ?>
			</header><!-- end header-below -->
		<?php endif; ?>

		<?php if ($this->countModules('breadcrumbs')) : ?>
			<div id="breadcrumbs">
				<jdoc:include type="module" name="breadcrumbs" />
			</div>
		<?php endif; ?>

		<?php if ($this->countModules('nav')) : ?>
			<nav id="nav" class="line">
				<jdoc:include type="modules" name="nav" style="jexhtml" />
			</nav><!-- end nav-->
		<?php endif; ?>

			<div class="line content-container">
			<?php if ($navBelowCount) : ?>
				<div id="nav-below">
				<?php if ($navBelowCount[1]) : ?>
					<div id="nav-below-1" class="<?php echo $navBelowClass ?>">
					<jdoc:include type="modules" name="nav-below-1" style="mod" />
					</div><!-- end nav-below-1 -->
				<?php endif; ?>

				<?php if ($navBelowCount[2]) : ?>
					<div id="nav-below-2" class="<?php echo $navBelowClass ?>">
					<jdoc:include type="modules" name="nav-below-2" style="mod" />
					</div><!-- end nav-below-2 -->
				<?php endif; ?>

				<?php if ($navBelowCount[3]) : ?>
					<div id="nav-below-3" class="<?php echo $navBelowClass ?>">
					<jdoc:include type="modules" name="nav-below-3" style="mod" />
					</div><!-- end nav-below-3 -->
				<?php endif; ?>

				<?php if ($navBelowCount[4]) : ?>
					<div id="nav-below-4" class="<?php echo $navBelowClass ?>">
					<jdoc:include type="modules" name="nav-below-4" style="mod" />
					</div><!-- end nav-below-4 -->
				<?php endif; ?>

				<?php if ($navBelowCount[5]) : ?>
					<div id="nav-below-5" class="<?php echo $navBelowClass ?>">
					<jdoc:include type="modules" name="nav-below-5" style="mod" />
					</div><!-- end nav-below-5 -->
				<?php endif; ?>

				<?php if ($navBelowCount[6]) : ?>
					<div id="nav-below-6" class="<?php echo $navBelowClass ?>">
					<jdoc:include type="modules" name="nav-below-6" style="mod" />
					</div><!-- end nav-below-6 -->
				<?php endif; ?>
				</div><!-- end nav-below -->
			<?php endif; ?>

				<div id="content" class="load-first">
					<div class="content-main">
					<?php if ($contentAboveCount) : ?>
						<div class="line content-above">
						<?php if ($contentAboveCount[1]) : ?>
							<div id="content-above-1" class="<?php echo $contentAboveClass ?>">
							<jdoc:include type="modules" name="content-above-1" style="jexhtml" oocss="mod" />
							</div><!-- end content-above-1 -->
						<?php endif; ?>

						<?php if ($contentAboveCount[2]) : ?>
							<div id="content-above-2" class="<?php echo $contentAboveClass ?>">
							<jdoc:include type="modules" name="content-above-2" style="jexhtml" oocss="mod" />
							</div><!-- end content-above-2 -->
						<?php endif; ?>

						<?php if ($contentAboveCount[3]) : ?>
							<div id="content-above-3" class="<?php echo $contentAboveClass ?>">
							<jdoc:include type="modules" name="content-above-3" style="jexhtml" oocss="mod" />
							</div><!-- end content-above-3 -->
						<?php endif; ?>

						<?php if ($contentAboveCount[4]) : ?>
							<div id="content-above-4" class="<?php echo $contentAboveClass ?>">
							<jdoc:include type="modules" name="content-above-4" style="jexhtml" oocss="mod" />
							</div><!-- end content-above-4 -->
						<?php endif; ?>

						<?php if ($contentAboveCount[5]) : ?>
							<div id="content-above-5" class="<?php echo $contentAboveClass ?>">
							<jdoc:include type="modules" name="content-above-5" style="jexhtml" oocss="mod" />
							</div><!-- end content-above-5 -->
						<?php endif; ?>

						<?php if ($contentAboveCount[6]) : ?>
							<div id="content-above-6" class="<?php echo $contentAboveClass ?>">
							<jdoc:include type="modules" name="content-above-6" style="jexhtml" oocss="mod" />
							</div><!-- end content-above-6 -->
						<?php endif; ?>
						</div><!-- end content-above -->
					<?php endif; ?>
					<?php if ($this->getBuffer('message')) : ?>
						<jdoc:include type="message" />
					<?php endif; ?>

<div class="line">
	<jdoc:include type="component" />
</div>

					<?php if ($contentBelowCount) : ?>
						<div class="line content-below">
						<?php if ($contentBelowCount[1]) : ?>
							<div id="content-below-1" class="<?php echo $contentBelowClass ?>">
							<jdoc:include type="modules" name="content-below-1" style="jexhtml" oocss="mod" />
							</div><!-- end content-below-1 -->
						<?php endif; ?>

						<?php if ($contentBelowCount[2]) : ?>
							<div id="content-below-2" class="<?php echo $contentBelowClass ?>">
							<jdoc:include type="modules" name="content-below-2" style="jexhtml" oocss="mod" />
							</div><!-- end content-below-2 -->
						<?php endif; ?>

						<?php if ($contentBelowCount[3]) : ?>
							<div id="content-below-3" class="<?php echo $contentBelowClass ?>">
							<jdoc:include type="modules" name="content-below-3" style="jexhtml" oocss="mod" />
							</div><!-- end content-below-3 -->
						<?php endif; ?>

						<?php if ($contentBelowCount[4]) : ?>
							<div id="content-below-4" class="<?php echo $contentBelowClass ?>">
							<jdoc:include type="modules" name="content-below-4" style="jexhtml" oocss="mod" />
							</div><!-- end content-below-4 -->
						<?php endif; ?>

						<?php if ($contentBelowCount[5]) : ?>
							<div id="content-below-5" class="<?php echo $contentAboveClass ?>">
							<jdoc:include type="modules" name="content-below-5" style="jexhtml" oocss="mod" />
							</div><!-- end content-below-5 -->
						<?php endif; ?>

						<?php if ($contentBelowCount[6]) : ?>
							<div id="content-below-6" class="<?php echo $contentAboveClass ?>">
							<jdoc:include type="modules" name="content-below-6" style="jexhtml" oocss="mod" />
							</div><!-- end content-below-6 -->
						<?php endif; ?>
						</div><!-- end content-below -->
					<?php endif; ?>
					</div><!-- end content-main -->

				<?php if ($columnGroupAlphaCount) : ?>
					<div class="column-group group-alpha">
					<?php if ($columnGroupCount[1]) : ?>
						<div id="column-1" class="<?php echo $columnGroupAlphaClass ?>">
						<jdoc:include type="modules" name="column-1" style="jexhtml" oocss="mod" />
						</div><!-- end column-1 -->
					<?php endif; ?>
					<?php if ($columnGroupCount[2]) : ?>
						<div id="column-2" class="<?php echo $columnGroupAlphaClass ?>">
						<jdoc:include type="modules" name="column-2" style="jexhtml" oocss="mod" />
						</div><!-- end column-2 -->
					<?php endif; ?>
					</div><!-- end column-group-alpha -->
				<?php endif; ?>

				</div><!-- end load-first -->

				<?php if ($columnGroupBetaCount) : ?>
				<div class="column-group group-beta">
				<?php if ($columnGroupCount[3]) : ?>
					<div id="column-3" class="<?php echo $columnGroupBetaClass ?>">
					<jdoc:include type="modules" name="column-3" style="jexhtml" oocss="mod" />
					</div><!-- end column-2 -->
				<?php endif; ?>
				<?php if ($columnGroupCount[4]) : ?>
					<div id="column-4" class="<?php echo $columnGroupBetaClass ?>">
					<jdoc:include type="modules" name="column-4" style="jexhtml" oocss="mod" />
					</div><!-- end column-4 -->
				<?php endif; ?>
				</div><!-- end column-group-beta -->
				<?php endif; ?>

			<?php if ($footerAboveCount) : ?>
				<div class="line footer-above">
				<?php if ($footerAboveCount[1]) : ?>
					<div id="footer-above-1" class="<?php echo $footerAboveClass ?>">
					<jdoc:include type="modules" name="footer-above-1" style="mod" />
					</div><!-- end footer-above-1 -->
				<?php endif; ?>
				<?php if ($footerAboveCount[2]) : ?>
					<div id="footer-above-2" class="<?php echo $footerAboveClass ?>">
					<jdoc:include type="modules" name="footer-above-2" style="mod" />
					</div><!-- end footer-above-2 -->
				<?php endif; ?>
				<?php if ($footerAboveCount[3]) : ?>
					<div id="footer-above-3" class="<?php echo $footerAboveClass ?>">
					<jdoc:include type="modules" name="footer-above-3" style="mod" />
					</div><!-- end footer-above-3 -->
				<?php endif; ?>
				<?php if ($footerAboveCount[4]) : ?>
					<div id="footer-above-4" class="<?php echo $footerAboveClass ?>">
					<jdoc:include type="modules" name="footer-above-4" style="mod" />
					</div><!-- end footer-above-4 -->
				<?php endif; ?>
				<?php if ($footerAboveCount[5]) : ?>
					<div id="footer-above-5" class="<?php echo $footerAboveClass ?>">
					<jdoc:include type="modules" name="footer-above-5" style="mod" />
					</div><!-- end footer-above-5 -->
				<?php endif; ?>
				<?php if ($footerAboveCount[6]) : ?>
					<div id="footer-above-6" class="<?php echo $footerAboveClass ?>">
					<jdoc:include type="modules" name="footer-above-6" style="mod" />
					</div><!-- end footer-above-6 -->
				<?php endif; ?>
				</div><!-- end footer-above -->
			<?php endif; ?>

			</div><!-- end content-container -->
		</div><!-- end body-container -->
	</div><!-- end footer-push -->

	<footer id="footer" class="line">
		<a id="to-page-top" href="<?php $url->setFragment('page-top'); echo $url->toString();?>" class="to-additional"><?php echo JText::_('TPL_JE_CONSTRUCT_COMMUNITY_JUMP_BACK_TO_TOP') ?></a>
	<?php if ($this->countModules('syndicate')) : ?>
		<div class="mod syndicate">
		<jdoc:include type="modules" name="syndicate" />
		</div>
	<?php endif; ?>
		<jdoc:include type="modules" name="footer" style="jexhtml" />
	</footer><!-- end footer -->

<?php if ($this->countModules('debug')) : ?>
	<jdoc:include type="modules" name="debug" style="raw" />
<?php endif; ?>

<?php if ($this->countModules('analytics')) : ?>
	<jdoc:include type="modules" name="analytics" />
<?php endif; ?>

<!--[if IE]></div><![endif]-->
	</body>
</html>