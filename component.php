<?php defined('_JEXEC') or die;
/**
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 * @todo TEST!
 */
// Load template logic
require JPATH_THEMES.'/'.$this->template.'/elements/logic.php';

// Add custom layout overrides for components or section here
/* eg:
 * $templateHelper->addLayout('component');
 * $templateHelper->addLayout(42, 'section');
 */

// Check for alternate component file, load it if it exists, and leave
if ($alternateIndexFile = $templateHelper->getLayout()) {
	require_once $alternateIndexFile['path'];
	return;
}

// fallback: load standard component-index.php
require_once JPATH_THEMES.'/'.$this->template.'/layouts/component-index.php';
