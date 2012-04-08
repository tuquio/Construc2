<!DOCTYPE html><?php defined('_JEXEC') or die;
/**
 * Component page layout.
 *
	com_content &print=1  &layout=XYZ  &page=[NN]
	com_media  &view=images  &e_name=jform_articletext
	com_media  &view=imagesList  &folder=[XYZ]  &asset=NN  &author=NN
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2011 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @var ConstructTemplateHelper $templateHelper
 * @var string $cache_manifest
 * @var string $columnLayout
 */
!defined('WMPATH_LAYOUTS') && define('WMPATH_LAYOUTS', dirname(__FILE__));

$app = JFactory::getApplication();
?>

<html lang="<?php echo $this->language ?>" dir="<?php echo $this->direction ?>" <?php echo $cache_manifest ?>>
<head>
<jdoc:include type="head" />
</head>
<body class="main-only component <?php echo $app->input->get('option'), ' ', $columnLayout ?>">
<?php ConstructTemplateHelper::msieSwatter(); ?>
	<div id="page-top" class="line <?php echo $templateHelper->getPageAlias(true) ?>">
<?php if ($app->input->get('print')) { ?>

		<div id="page-head" class="line page-head"><div class="head-inner">

		<h1 id="logo"><a class="site-link" href="<?php echo $this->baseurl ?>/"><span class="site-name"><?php echo $app->getCfg('sitename');?></span></a></h1>
		</div></div>
<?php } ?>
		<div id="body-container" class="line">
			<div class="line content-container">
				<div id="content" class="line content-main">

<?php if ($this->getBuffer('message')) { ?><jdoc:include type="message" /><?php } ?>
<section class="line component">
<jdoc:include type="component" />
</section>

				</div><!-- .content-main -->
<?php if ($this->countModules('footer')) { $templateHelper->renderModules('footer'); } ?>

			</div><!-- .content-container -->
		</div><!-- #body-container -->
	</div><!-- #page-top -->
<?php
if ($this->countModules('debug')) { $templateHelper->renderModules('debug', 'raw'); }
if ($this->countModules('analytics')) { $templateHelper->renderModules('analytics', 'raw'); }

ConstructTemplateHelper::msieSwatter();
?>
	</body>
</html>
