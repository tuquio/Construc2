<!DOCTYPE html><?php defined('_JEXEC') or die;
/**
 * Modal Window/Popup page layout.
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2011 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @var $templateHelper ConstructTemplateHelper
 * @var $cache_manifest string
 * @var $footerAboveCount array
 */
!defined('WMPATH_LAYOUTS') && define('WMPATH_LAYOUTS', dirname(__FILE__));

$app = JFactory::getApplication();
?>

<html lang="<?php echo $this->language ?>" dir="<?php echo $this->direction ?>">
<head>
<jdoc:include type="head" />
</head>
<body class="main-only modal <?php echo $app->input->get('option'), ' ', $columnLayout ?>">
<?php ConstructTemplateHelper::msieSwatter(); ?>
	<div id="page-top" class="line">
		<div id="body-container" class="line <?php echo $templateHelper->getPageAlias(true) ?>">
			<div class="line content-container">
				<div id="content" class="line content-main">
<?php if ($this->getBuffer('message')) { ?><jdoc:include type="message" /><?php } ?>

<section class="line component">
<jdoc:include type="component" />
</section>
				</div><!-- #content -->
<?php if ($footerAboveCount[0]) { include WMPATH_LAYOUTS. '/mod_footer_above.php'; } ?>
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
