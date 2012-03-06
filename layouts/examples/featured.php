<?php defined('_JEXEC') or die;
/**
 * Frontpage sample page layout.
 *
 * @package		Templates
 * @subpackage  Construc2
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
<?php ConstructTemplateHelper::msieSwatter() ?>
<?php if ($this->getBuffer('message')) : ?>
<div class="line"><jdoc:include type="message" /></div>
<?php endif; ?>
<div class="line"><jdoc:include type="component" /></div>
<?php
ConstructTemplateHelper::msieSwatter();
ConstructTemplateHelper::afterCompileBody();
?>
</body>
</html>
