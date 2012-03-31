<?php defined('_JEXEC') or die;
/**
 * Construc2 Template Bootstrap
 *
 * @package     Construc2
 * @subpackage  Engine
 * @copyright   (C) 2011-2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @return JDocumentHtml
 * @uses JSite, JFactory, JURI
 */

error_reporting(E_ALL | E_STRICT);

/** Register the ConstructTemplateHelper Class */
JLoader::register('ConstructTemplateHelper', dirname(__FILE__) . '/helper.php');

/** @var $templateHelper ConstructTemplateHelper */
$templateHelper	= ConstructTemplateHelper::getInstance();

/** @var $app JSite To enable use of site configuration */
$app 	= JFactory::getApplication();

/** @var $base_url string Get the base URL of the website */
$base_url 	= JURI::base(true) . '/';

/** @var $tmpl_url string Define relative shortcut for current template directory */
$tmpl_url 	= $base_url. 'templates/'. $this->template;

/* Define some shortcuts :: for BC the 1.9.x params are used as defaults */
$ssiIncludes	= (bool) $this->params->get('ssiIncludes'  , false);
$showSwitcher 	= (bool) $this->params->get('styleswitcher', $this->params->get('enableSwitcher', false)); #FIXME remove BC param
$showFontscaler	= (bool) $this->params->get('fontscaler'   , false);

// some editor form requested, needs mo' styles, and less modules
$editMode = $templateHelper->isEditMode();

// a 'filelist' param returns -1 for 'none'
$templateHelper->feature('ssi'  , $ssiIncludes);
$templateHelper->feature('rtl'  , ($this->direction == 'rtl'));
$templateHelper->feature('edit' , $editMode);
$templateHelper->feature('print', $app->input->get('print', 0));

// Preview Module Positions with index.php?tp=1
if ($app->get('input')->get('tp', 0, 'bool')) {
	$templateHelper->feature('tp', JComponentHelper::getParams('com_templates')->get('template_positions_display', 0));
}

// JSON2 support for oldIEs
$templateHelper->feature('standards.json');
// Google Chrome Frame Install for oldIEs
$templateHelper->feature('standards.cfinstall', $this->params->get('cfinstall', $this->params->get('loadGcf', false))); #FIXME remove BC param
// BrowserChoice.com Popup or Redirect
$templateHelper->feature('standards.browserchoice', $this->params->get('browserchoice', false));
// Lea Verou's -prefix-free
$templateHelper->feature('standards.prefixfree', $this->params->get('prefixfree', false));

// all things different in edit mode
$templateHelper->widget('styleswitch', ($showSwitcher && !$editMode));
$templateHelper->widget('fontscaler', ($showFontscaler && !$editMode));

// tell mobile devices to treat the viewport as being the same width as the
// physical width of the device to make width work in media-queries as expected
// @link http://h5bp.com/viewport
$templateHelper->element('meta')->set('viewport', 'width=device-width,initial-scale=1.0');

/**
 * Some "global" variables for use within Page Layouts
 */

// HTML5 cache manifest (not rendered by default in the <html> element)
// triggers "This website (xxx) is asking to store data on your computer for offline use" in browsers
if ((bool) $this->params->get('html5manifest', false)) {
	if ($this->direction == 'rtl') {
		$cache_manifest = ' manifest="'.$tmpl_url.'/'.$this->template.'_rtl.manifest"';
	} else {
		$cache_manifest = ' manifest="'.$tmpl_url.'/'.$this->template.'.manifest"';
	}
} else {
	$cache_manifest = '';
}

/* Count and Seed Module Position Groups
 * NOTE! In debug mode values are set to their respective maximum
 * @see ConstructTemplateHelper::MAX_MODULES
 * @see ConstructTemplateHelper::MAX_COLUMNS
 */
$headerAboveCount	= $templateHelper->getModulesCount('header-above');
$headerBelowCount	= $templateHelper->getModulesCount('header-below');
$navBelowCount		= $templateHelper->getModulesCount('nav-below');
$contentAboveCount	= $templateHelper->getModulesCount('content-above');
$contentBelowCount	= $templateHelper->getModulesCount('content-below');
$footerAboveCount	= $templateHelper->getModulesCount('footer-above');

/* Count and Seed Column Module Position */
$columnGroupCount      = $templateHelper->getModulesCount('column', ConstructTemplateHelper::MAX_COLUMNS);
$columnGroupAlphaCount = $templateHelper->numModules('group-alpha');
$columnGroupBetaCount  = $templateHelper->numModules('group-beta');

/* Build Column Layout class */
$columnLayout = array('main-only');
if (!$editMode) {
	# alpha-X-main-beta-Y
	if ($columnGroupAlphaCount > 0) {
		$columnLayout = array('alpha-main');
		if ($columnGroupBetaCount > 0) {
			$columnLayout = array('alpha-main-beta');
		}
	} elseif ($columnGroupBetaCount > 0) {
		$columnLayout = array('main-beta');
	}

	// merge $columnLayout into a string
	$columnLayout = array_unique($columnLayout);
}
$columnLayout = trim(implode(' ', $columnLayout));

/* .eof */
