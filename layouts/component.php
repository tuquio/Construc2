<!DOCTYPE html><?php defined('_JEXEC') or die;
/**
 * Component page layout.
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2011 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

// option=com_media&view=images&tmpl=component&e_name=jform_articletext
// option=com_media&view=imagesList&tmpl=component&folder=&asset=134&author=43
$app = JFactory::getApplication();

?>
<html lang="<?php echo $this->language ?>" dir="<?php echo $this->direction ?>" <?php echo $cache_manifest ?>>
<head>
<jdoc:include type="head" />
</head>
<body class="main component <?php echo $app->input->get('option'), ' ', $app->input->get('print') ? 'print' : $app->input->get('layout') ?>">
<?php ConstructTemplateHelper::msieSwatter() ?>
	<div id="page-top">
		<div id="body-container" class="<?php echo $templateHelper->getPageAlias(true)?>">
			<div class="line content-container">
				<div id="content" class="line content-main">

<?php if ($this->getBuffer('message')) : ?><jdoc:include type="message" /><?php endif; ?>
<section class="line component">
<jdoc:include type="component" />
</section>

				</div><!-- .content-main -->
<?php if ($footerAboveCount) : include JPATH_THEMES .'/'. $this->template . '/layouts/mod_footer_above.php'; endif; ?>
			</div><!-- .content-container -->
		</div><!-- #body-container -->
	</div><!-- #page-top -->
<?php
if ($this->countModules('debug')) : $templateHelper->renderModules('debug', 'raw'); endif;
if ($this->countModules('analytics')) : $templateHelper->renderModules('analytics', 'raw'); endif;

ConstructTemplateHelper::msieSwatter();
?>
	</body>
</html>
