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

// Define relative shortcut for current template directory
$template 	= 'templates/'.$this->template;
?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
<?php $this->addStyleSheet($template.'/css/core/print.css','text/css','print'); ?>
</head>
<body class="contentpane">
<?php if ($this->countModules('print-popup')) : ?>
	<jdoc:include type="modules" name="print-popup" style="raw" />
<?php endif; ?>
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>