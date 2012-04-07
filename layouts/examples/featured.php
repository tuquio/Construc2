<?php defined('_JEXEC') or die;
/**
 * Front page sample page layout.
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2011 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
</head>

<body class="<?php echo $columnLayout ?>">
<?php ConstructTemplateHelper::msieSwatter(); ?>
		<div id="body-container" class="line">
			<div class="line content-container">
				<div id="content" class="line content-main">

<?php if ($this->getBuffer('message')) : ?><jdoc:include type="message" /><?php endif; ?>
<section class="line component">
<jdoc:include type="component" />
</section>
				</div>>
			</div>>
		</div>>
<?php ConstructTemplateHelper::msieSwatter(); ?>
</body>
</html>
