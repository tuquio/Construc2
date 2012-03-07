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

/* Define shortcuts for often used template parameters */
$customStyleSheet 	= 		 $this->params->get('customStyleSheet');
$enableSwitcher 	= (bool) $this->params->get('enableSwitcher');
$showDiagnostics 	= (bool) $this->params->get('showDiagnostics');

$loadModal			= (bool) $this->params->get('loadModal');
$loadMoo 			= (bool) $this->params->get('loadMoo', $loadModal);
$loadJQuery 		=		 $this->params->get('loadjQuery');

// "old-school" concatenating of files and free server based compression
$ssiIncludes		= (bool) $this->params->get('ssiIncludes', 0);
$ssiTheme			=		 $this->params->get('ssiTheme');

// some editor form requested, need mo' styles
$editMode = in_array($app->input->get('layout'), array('edit','form'))
		||  in_array($app->input->get('option'), array('com_media'));

// all things different in edit mode
if ($editMode) {
	$loadMoo = true;
	$enableSwitcher = $showDiagnostics = false;
}

// 'filelist' params return -1 for none. make FALSE
if (($customStyleSheet + 1) == 0) $customStyleSheet = false;
if (($ssiTheme + 1) == 0) $ssiTheme = false;

// will contain custom <script> code depending on selected params
$scriptDeclarations	= array();
$styleDeclarations	= array();

if ($showDiagnostics) {
	$jmenu = $app->getMenu();
	$amenu = $jmenu->getActive();

	$currentComponent = $amenu->component;
	$catId = $itemId = $articleId = '';
	if ($amenu->component == 'com_content') {
		$itemId 	= $amenu->id;
		if (isset($amenu->query['id'])) {
			$articleId = $amenu->query['id'];
		}
	}
}

// Load the jQuery JavaScript Library
if ($loadJQuery) {
	$templateHelper->addScript('//ajax.googleapis.com/ajax/libs/jquery/'. $loadJQuery .'/jquery.min.js');
}

// Load the MooTools JavaScript Library, else @see ConstructTemplateHelper::sortScripts()
if ($loadMoo == true) {
	JHtml::_('behavior.framework');
	if ($loadModal) {
		// Enable modal pop-ups
		JHtml::_('behavior.modal');
	}
}

/* ----------------------------- Module Counts ----------------------------- */
if ($headerAboveCount = $templateHelper->getModulesCount('header-above', ConstructTemplateHelper::MAX_MODULES)) {
	$headerAboveClass = 'above count-'.$headerAboveCount[0];
}

if ($headerBelowCount = $templateHelper->getModulesCount('header-below', ConstructTemplateHelper::MAX_MODULES)) {
	$headerBelowClass = 'below count-'.$headerBelowCount[0];
}

if ($navBelowCount = $templateHelper->getModulesCount('nav-below', ConstructTemplateHelper::MAX_MODULES)) {
	$navBelowClass = 'below count-'.$navBelowCount[0];
}

if ($contentAboveCount = $templateHelper->getModulesCount('content-above', ConstructTemplateHelper::MAX_MODULES)) {
	$contentAboveClass = 'above count-'.$contentAboveCount[0];
}

if ($contentBelowCount = $templateHelper->getModulesCount('content-below', ConstructTemplateHelper::MAX_MODULES)) {
	$contentBelowClass = 'below count-'.$contentBelowCount[0];
}

if ($footerAboveCount = $templateHelper->getModulesCount('footer-above', ConstructTemplateHelper::MAX_MODULES)) {
	$footerAboveClass = 'above count-'.$footerAboveCount[0];
}

/* ------------------------------ Column Layout ----------------------------- */
$columnGroupCount = $templateHelper->getModulesCount('column', ConstructTemplateHelper::MAX_COLUMNS);

$columnGroupAlphaCount = $columnGroupCount[1] + $columnGroupCount[2];
if ($columnGroupAlphaCount) {
	$columnGroupAlphaClass = 'column-alpha colcount-'.$columnGroupAlphaCount;
}

$columnGroupBetaCount = $columnGroupCount[3] + $columnGroupCount[4];
if ($columnGroupBetaCount) {
	$columnGroupBetaClass = 'column-beta colcount-'.$columnGroupBetaCount;
}

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
	$columnLayout[] = (bool) $this->params->get('fluidMedia') ? 'fluid-media' : '';
	$columnLayout = array_unique($columnLayout);
}
$columnLayout = trim(implode(' ', $columnLayout));

/* --------------------------- Debug Positions ------------------------------- */
// #TODO get positions from xml and transform names into variable counterparts
if ($app->getCfg('debug') && $app->input->get('tpos', 0, 'int')) {
	$headerAboveCount  = $headerBelowCount  = $navBelowCount    =
	$contentAboveCount = $contentBelowCount = $footerAboveCount = range(0, ConstructTemplateHelper::MAX_MODULES, 1);

	$columnGroupCount  = range(0, ConstructTemplateHelper::MAX_COLUMNS, 1);
	$columnGroupAlphaCount = $columnGroupBetaCount = ($columnGroupCount > 1) ? floor($columnGroupCount / 2) : $columnGroupCount;
}

/* ---------------------------- Head Elements -------------------------------- */
// Custom tags
// tell mobile devices to treat the viewport as being the same width as the
// physical width of the device to make width work in media-queries as expected
$this->setMetaData('viewport', 'width=device-width,initial-scale=1.0');

// Transparent favicon
if (is_file(JPATH_THEMES .'/'. $this->template .'/favicon.png')) {
	$templateHelper->addLink($tmpl_url.'/favicon.png', 'image/png', 'icon');
}

// Typography (protocol relative URLs)
$templateHelper->webFonts();

// Style sheets
if ($ssiIncludes) {
	$templateHelper->addLink($tmpl_url.'/css/construc2.styles?rtl=' . (int)($this->direction == 'rtl') . '&em=' .(int)$editMode );
	if ($ssiTheme) {
		$templateHelper->addLink($tmpl_url.'/themes/'.$ssiTheme .'?rtl=' . (int)($this->direction == 'rtl') . '&em=' .(int)$editMode);
	}
} else {
	$templateHelper->addLink($tmpl_url.'/css/core/base.css');
	$templateHelper->addLink($tmpl_url.'/css/core/oocss.css');
	$templateHelper->addLink($tmpl_url.'/css/core/screen.css');

	if ($this->direction == 'rtl') {
		$templateHelper->addLink($tmpl_url.'/css/core/rtl.css');
	}

	// "task based" stuff
	if ($editMode) {
		$templateHelper->addLink($tmpl_url.'/css/core/edit-form.css');
	} else {
		$templateHelper->addLink($tmpl_url.'/css/core/print.css');
	}

	if ($customStyleSheet) {
		$templateHelper->addLink($tmpl_url.'/themes/'.$customStyleSheet);
	}
}

// Style sheet switcher
if ($enableSwitcher) {
	$templateHelper->addLink($tmpl_url.'/css/core/diagnostic.css', null, array('title'=>'diagnostic'), 'alternate stylesheet');
	// $templateHelper->addScript($tmpl_url.'/js/styleswitch.min.js');
	$templateHelper->addScript($tmpl_url.'/js/src/styleswitch.js');
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

/* ----------------------- Internet Explorer Fixes --------------------------- */
// html5 shim
if ($this->params->get('html5shim')) {
	$templateHelper->addScript($tmpl_url.'/js/html5.js');
}

// JSON shim
$scriptDeclarations[] = '(function(W,D,src) {if (W.JSON) return;var a=D.createElement("script");var b=D.getElementsByTagName("script")[0];a.src=src;a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b);})(window,document,"'. $tmpl_url .'/js/json2.min.js");';

/* Preview Module Styles for use with index.php?tp=1 */
if ($app->get('input')->getBool('tp') && JComponentHelper::getParams('com_templates')->get('template_positions_display') ) {
	$styleDeclarations[] = '.mod-preview{position:relative}';
	$styleDeclarations[] = '.mod-preview-wrapper{border:none;outline:1px solid #ccc;box-shadow:2px 2px 6px rgba(0,0,0,0.6);padding:5px 10px;margin:5px 0;opacity:0.9;background:#666;z-index:999;min-height:50px}';
	$styleDeclarations[] = '.mod-preview-wrapper div.moduletable, .mod-preview-wrapper .menu, .mod-preview-wrapper #breadcrumbs{opacity:0}';
	$styleDeclarations[] = '.mod-preview-info{color:#fff;background:none;border:none;z-index:999;position:absolute;left:5px;top:5px;font-size:110%}';
	$styleDeclarations[] = '#left .mod-preview-wrapper, #left2 .mod-preview-wrapper, #right .mod-preview-wrapper, #right2 .mod-preview-wrapper{height:940px}';
}

// add collected custom style declarations
if ( count($styleDeclarations) ) {
	$templateHelper->addStyle($styleDeclarations);
}

// add collected custom script declarations
if ( count($scriptDeclarations) ) {
	$templateHelper->addScriptDeclaration($scriptDeclarations);
}
