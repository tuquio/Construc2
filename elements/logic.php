<?php defined('_JEXEC') or die;
/**
 * @package     Construc2
 * @subpackage  Elements
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @author		(C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @return JDocumentHtml
 * @uses JSite, JFactory, JURI
 */

/** Register the ConstructTemplateHelper Class */
JLoader::register('ConstructTemplateHelper', dirname(__FILE__) . '/helper.php');

$templateHelper = ConstructTemplateHelper::getInstance();

/** @var $app JSite To enable use of site configuration */
$app 		= JFactory::getApplication();

/** @var $base_url string Get the base URL of the website */
$base_url 	= JURI::base(true) . '/';

/** @var $tmpl_url string Define relative shortcut for current template directory */
$tmpl_url 	= $base_url. 'templates/'. $this->template;

/* Define some shortcuts */
$enableSwitcher 	= (bool) $this->params->get('enableSwitcher');
$showDiagnostics 	= (bool) $this->params->get('showDiagnostics');

$cssTheme			=		 $this->params->get('customStyleSheet');
$ssiIncludes		= (bool) $this->params->get('ssiIncludes', 0);

// a 'filelist' param returns -1 for 'none'
if (($cssTheme + 1) == 0) $cssTheme = false;

// some editor form requested, needs mo' styles, and less modules
$editMode  = $templateHelper->isEditMode();
$printMode = $app->input->get('print', 0, 'int');

if ($app->getCfg('debug') && ($cts = $app->input->get('cts')) ) {
	$cts = basename($cts, '.css');
	if (is_file(JPATH_THEMES .'/'. $this->template . '/themes/'. $cts .'.css')) {
		$cssTheme = $cts;
	}
}

// all things different in edit and popup mode
if ($editMode || $printMode) {
	$enableSwitcher = false;
	$showDiagnostics = false;
}

if ($showDiagnostics && $app->input->get('tmpl', 'index') == 'index') {
	$jmenu = $app->getMenu();
	$amenu = $jmenu->getActive();

	$currentComponent = $amenu->component;
	$catId = $itemId = $articleId = '';
	if ($amenu->component == 'com_content') {
		$itemId = $amenu->id;
		if (isset($amenu->query['id'])) {
			$articleId = $amenu->query['id'];
		}
	}
}

/* Count and Seed Module Position Groups */
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
if (!$editMode && !$printMode) {
	# alpha-X-main-beta-Y
	if ($columnGroupAlphaCount > 0) {
		$columnLayout = array('alpha-main');
		if ($columnGroupBetaCount > 0) {
			$columnLayout = array('alpha-main-beta');
		}
	} elseif ($columnGroupBetaCount > 0) {
		$columnLayout = array('main-beta');
	}

}

if ($printMode) {
	$columnLayout[] = 'print';
}

// merge $columnLayout into a string
$columnLayout = array_unique($columnLayout);
$columnLayout = trim(implode(' ', $columnLayout));

/* Debug Template Positions */
// max out amount of positions and columns
if ($app->getCfg('debug') && $app->input->get('tpos', 0, 'bool')) {
	$headerAboveCount  = $headerBelowCount  = $navBelowCount    =
	$contentAboveCount = $contentBelowCount = $footerAboveCount = range(0, ConstructTemplateHelper::MAX_MODULES, 1);

	$columnGroupCount = range(0, ConstructTemplateHelper::MAX_COLUMNS, 1);
	$columnGroupAlphaCount = $columnGroupBetaCount = ($columnGroupCount > 1) ? floor($columnGroupCount / 2) : $columnGroupCount;
}

/* HEAD Elements */

// Transparent favicon
if (is_file(JPATH_THEMES .'/'. $this->template .'/favicon.png')) {
	$templateHelper->addLink($tmpl_url.'/favicon.png', 'image/png', 'icon');
}

// Style sheets
if ($ssiIncludes) {
	$templateHelper->addLink($tmpl_url.'/css/construc2.styles?rtl='. ($this->direction == 'rtl') .'&em='. $editMode .'&pm='.$printMode);

	// a 'filelist' param returns -1 for 'none'
	$ssiTheme = $this->params->get('ssiTheme');
	if (($ssiTheme + 1) == 0) {
		$ssiTheme = false;
	}
	if ($ssiTheme) {
		$templateHelper->addLink($tmpl_url.'/themes/'.$ssiTheme .'?rtl='. ($this->direction == 'rtl') .'&em='. $editMode .'&pm='.$printMode);
	}
}
else {
	$templateHelper->addLink($tmpl_url.'/css/core/base.css');
	$templateHelper->addLink($tmpl_url.'/css/core/oocss.css');
	$templateHelper->addLink($tmpl_url.'/css/core/template.css');

	if ($this->direction == 'rtl') {
		$templateHelper->addLink($tmpl_url.'/css/core/rtl.css');
	}

	// "task based" stuff
	if ($editMode) {
		$templateHelper->addLink($tmpl_url.'/css/core/edit-form.css');
	}

	if ($cssTheme) {
		$templateHelper->addLink($tmpl_url.'/themes/'.$cssTheme);
	}
}

/* Preview Module Positions with index.php?tp=1 */
if ($app->get('input')->get('tp', 0, 'bool') && JComponentHelper::getParams('com_templates')->get('template_positions_display')) {
	$templateHelper->addLink($tmpl_url.'/css/core/tp.css');
}

// Style sheet switcher
if ($enableSwitcher) {
	$templateHelper->addLink($tmpl_url.'/css/diagnostic.css', null, array('title'=>'diagnostic'), 'alternate stylesheet');
	// $templateHelper->addScript($tmpl_url.'/js/styleswitch.min.js');
	$templateHelper->addScript($tmpl_url.'/js/src/styleswitch.js', null, array('defer'=>true));
}

// Lea Verou's -prefix-free
if ($this->params->get('prefixfree')) {
	$templateHelper->addScript($tmpl_url.'/js/prefixfree.min.js');
}

// HTML5 cache manifest (not rendered by default in the <html> element)
// triggers "This website (xxx) is asking to store data on your computer for offline use" in browsers
if ((bool) $this->params->get('html5manifest')) {
	if ($this->direction == 'rtl') {
		$cache_manifest = ' manifest="'.$tmpl_url.'/'.$this->template.'_rtl.manifest"';
	} else {
		$cache_manifest = ' manifest="'.$tmpl_url.'/'.$this->template.'.manifest"';
	}
} else {
	$cache_manifest = '';
}

/* .eof */
