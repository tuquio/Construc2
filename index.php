<?php defined('_JEXEC') or die;
/**
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

// Load template logic
require JPATH_THEMES.'/'.$this->template.'/elements/logic.php';

// Add custom layout overrides for components or section here
/* eg:
 * $templateHelper->addLayout('index');
 * $templateHelper->addLayout(42, 'section');
 */

// Check for alternate index file, load it if it exists, and leave
if ($alternateIndexFile = $templateHelper->getLayout()) {
	if ($alternateIndexFile['scope'] == 'html') {
		require_once JPATH_THEMES.'/'.$this->template.'/layouts/static_html.php';
	} else {
		require_once $alternateIndexFile['path'];
	}
	return;
}

// load standard index.php
require_once JPATH_THEMES.'/'.$this->template.'/layouts/index.php';
