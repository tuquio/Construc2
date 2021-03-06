<?php defined('_JEXEC') or die;
/**
 * Component Template entry script.
 *
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @var ConstructTemplateHelper $templateHelper
 */
// Load template logic
require JPATH_THEMES.'/'.$this->template.'/elements/logic.php';

// Add custom layout overrides for components or section here
// eg: $templateHelper->addLayout('component');

// Check for alternate index file, load it if it exists, and leave
if ($alternateIndexFile = $templateHelper->getLayout()) {
	// $alternateIndexFile['scope'] == 'html'
	require_once $alternateIndexFile['path'];
	return;
}

// fallback: load standard component.php
require_once JPATH_THEMES.'/'.$this->template.'/layouts/component.php';
