<?php defined('_JEXEC') or die;
/**
 * @package		Template Framework for Joomla! 1.6
 * @author		Joomla Engineering http://joomlaengineering.com
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

// Load template logic
require JPATH_THEMES.'/'.$this->template.'/elements/logic.php';

// Add custom layout overrides for components or section here
// eg: $helper->addLayout($option, 'component')

$templateHelper->addLayout('index');
$templateHelper->addLayout('content');
$templateHelper->addLayout('section');

$templateHelper->addLayout('content', 'index');
$templateHelper->addLayout('content', 'component');
$templateHelper->addLayout('content', 'section');

$templateHelper->addLayout("10");
$templateHelper->addLayout("20", 'index');
$templateHelper->addLayout("30", 'component');
$templateHelper->addLayout("40", 'section');

FB::log($templateHelper);
FB::log($templateHelper->getLayout());

// Check for alternate index file, load it if it exists, and leave
if ($alternateIndexFile = $templateHelper->getLayout()) {
	include_once $alternateIndexFile;
	return;
}

include JPATH_THEMES.'/'.$this->template.'/layouts/index.php';
