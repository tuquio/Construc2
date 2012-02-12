<?php defined('_JEXEC') or die;
/**
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic
 * @copyright	Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @return JDocumentHtml
 * @uses JSite, JFactory, JURI
 */

/** Register the ConstructTemplateHelper Class */
JLoader::register('ConstructTemplateHelper', dirname(__FILE__) . '/helper.php');

$templateHelper = ConstructTemplateHelper::getInstance($this);

/** @var $app JSite To enable use of site configuration */
$app 		= JFactory::getApplication();

/** @var $base_url string Get the base URL of the website */
$base_url 	= JURI::base(true) . '/';

/** @var $tmpl_url string Define relative shortcut for current template directory */
$tmpl_url 	= $base_url. 'templates/'. $this->template;

/* Define shortcuts for often used template parameters */
$customStyleSheet 	=		 $this->params->get('customStyleSheet');
$enableSwitcher 	= (bool) $this->params->get('enableSwitcher');
$showDiagnostics 	= (bool) $this->params->get('showDiagnostics');
$showDateContainer 	= (bool) $this->params->get('showDateContainer');

$fluidMedia			= (bool) $this->params->get('fluidMedia') ? 'fluid-media' : '';
$fullWidth			=		 $this->params->get('fullWidth');
$siteWidth			= (int)	 $this->params->get('siteWidth');
$siteWidthUnit 		= 		 $this->params->get('siteWidthUnit');

$mod_oocss			= (bool) $this->params->get('modOocss', 1);

$loadModal			= (bool) $this->params->get('loadModal');
$loadMoo 			= (bool) $this->params->get('loadMoo', $loadModal);
$loadJQuery 		=		 $this->params->get('loadjQuery');
$loadChromeFrame	=		 $this->params->get('loadGcf'); // if set, contains the version number, i.e '1.0.2'

// "old-school" concatenating of files and free server based compression
$ssiIncludes		= (bool) $this->params->get('ssiIncludes', 0);
$ssiTheme			= 		 $this->params->get('ssiTheme');

// 'filelist' params return -1 for none. make FALSE
if (($customStyleSheet + 1) == 0) $customStyleSheet = false;
if (($ssiTheme + 1) == 0) $ssiTheme = false;

// will contain custom <script> code depending on selected params
$scriptDeclarations	= array();
$styleDeclarations	= array();

/** @var $googleWebFont array Web-Fonts (for BC the 1. param is read using the "old" field name) */
$googleWebFont = $googleWebFontSize = $googleWebFontTargets = array();
for ($i=1; $i <= ConstructTemplateHelper::MAX_WEBFONTS; $i++) {
	$googleWebFont[$i]			= $this->params->def('googleWebFont'.$i);
	$googleWebFontSize[$i]		= $this->params->def('googleWebFontSize'.$i);
	$googleWebFontTargets[$i]	= $this->params->def('googleWebFontTargets'.$i);
}

// Change generator tag
$this->setGenerator( trim($this->params->get('setGeneratorTag', 'Construc2')) );

if ($showDiagnostics) {
	$jmenu = $app->getMenu();
	$amenu = $jmenu->getActive();

	$currentComponent = $amenu->component;
	$catId = $itemId = $articleId = '';
	if ($amenu->component == 'com_content') {
		$itemId 	= $amenu->id;
		$articleId	= $amenu->query['id'];
	}
}

// Load the jQuery JavaScript Library
if ($loadJQuery) {
	// <script onload="if (jQuery) {jQuery.noConflict();}" type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	$this->addScript('//ajax.googleapis.com/ajax/libs/jquery/'. $loadJQuery .'/jquery.min.js');

	// without MooTools we must drop all but core.js
	if ($loadMoo == true) {
		$scriptDeclarations[] = 'if (window.jQuery){jQuery.noConflict();}';
	}
}

// Load the MooTools JavaScript Library
if ($loadMoo == true) {
	JHtml::_('behavior.framework', $loadModal);
	if ($loadModal) {
		// Enable modal pop-ups
		JHtml::_('behavior.modal');
	}
} else {
	// Remove MooTools if set to no.
	$head = $this->getHeadData();
	// without MooTools we must drop all but core.js
	$moos = preg_grep('#/media/system/js(\/(?!core))#', array_keys($head['scripts']));
	if (count($moos) > 0) {
		foreach ($moos as $src) {
			unset($head['scripts'][$src]);
		}
		$this->setHeadData($head);
		unset($src, $moos);
	}
	unset($head);
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

# alpha-X-main-beta-Y
$columnLayout = array('main-only');
if ($columnGroupAlphaCount > 0) {
	$columnLayout = array('alpha-main');
	if ($columnGroupBetaCount > 0) {
		$columnLayout = array('alpha-main-beta');
	}
} elseif ($columnGroupBetaCount > 0) {
	$columnLayout = array('main-beta');
}

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
	$this->addFavicon($tmpl_url.'/favicon.png', 'image/png', 'icon');
}

// Typography (protocol relative URLs)
for ($i = 1; $i <= ConstructTemplateHelper::MAX_WEBFONTS; $i++) {
	if ($googleWebFont[$i]) {
		// Fix Google Web Font name for CSS
		$googleWebFontFamily = str_replace(array('+',':bold',':italic'), ' ', $googleWebFont[$i]);
		$this->addStyleSheet('//fonts.googleapis.com/css?family='.$googleWebFont[$i]);
		$styleDeclarations[] = $googleWebFontTargets[$i]
							. ' {font-family:'.$googleWebFontFamily.', serif;'
							. (($googleWebFontSize[$i]>0) ? 'font-size:'.$googleWebFontSize[$i].';' : '')
							. '}';
	}
}

// Style sheets
if ($ssiIncludes) {
	if ($this->direction == 'rtl') {
		$this->addStyleSheet($tmpl_url.'/css/construc2_rtl.styles', 'text/css');
	} else {
		$this->addStyleSheet($tmpl_url.'/css/construc2.styles', 'text/css');
	}
	if ($ssiTheme) {
		$this->addStyleSheet($tmpl_url.'/themes/'.$ssiTheme, 'text/css');
	}
} else {
	$this->addStyleSheet($tmpl_url.'/css/core/base.css', 'text/css');
	$this->addStyleSheet($tmpl_url.'/css/core/oocss.css', 'text/css');
	$this->addStyleSheet($tmpl_url.'/css/core/screen.css', 'text/css', 'screen');
	if ($this->direction == 'rtl') {
		$this->addStyleSheet($tmpl_url.'/css/core/rtl.css', 'text/css', 'screen');
	}
}

// cheap and all but smart
if ( in_array($app->get('input')->getCmd('layout'), array('edit','form'))
	|| JFactory::getUser()->authorise('core.edit') /* do we need more ACL checks */
	) {
	$this->addStyleSheet($tmpl_url.'/css/core/edit-form.css', 'text/css', 'screen');
} else {
	$this->addStyleSheet($tmpl_url.'/css/core/print.css', 'text/css', 'print');
}

if ($customStyleSheet) {
	$this->addStyleSheet($tmpl_url.'/themes/'.$customStyleSheet, 'text/css','all');
}

// Style sheet switcher
if ($this->params->get('enableSwitcher')) {
	$this->addHeadLink($tmpl_url.'/css/core/diagnostic.css', 'alternate stylesheet', 'rel', array('title'=>'diagnostic'));
	$templateHelper->addHeadLink($tmpl_url.'/css/core/diagnostic.css', null, array('title'=>'diagnostic'), 'alternate stylesheet');

	$this->addHeadLink($tmpl_url.'/css/core/wireframe.css', 'alternate stylesheet', 'rel', array('title'=>'wireframe'));
	$templateHelper->addHeadLink($tmpl_url.'/css/core/wireframe.css', null, array('title'=>'wireframe'), 'alternate stylesheet');

	$this->addScript($tmpl_url.'/js/styleswitch.js');
	$templateHelper->addScript($tmpl_url.'/js/styleswitch.js');
}

// Lea Verou's -prefix-free
if ($this->params->get('prefixfree')) {
	$this->addScript($tmpl_url.'/js/prefixfree.min.js');
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

// JavaScript crap
//	if ($loadMoo == true) {
//		$scriptDeclarations[] = 'if (window.addEvent && window.SmoothScroll){window.addEvent("domready",function(){new SmoothScroll({duration:1200},window);});}';
//	}

/* ----------------------- Internet Explorer Fixes --------------------------- */
// html5 shim
if ($this->params->get('html5shim')) {
	$this->addCustomTag('<!--[if lt IE 9]><script src="'.$tmpl_url.'/js/html5.js" type="text/javascript"></script><![endif]-->');
	$templateHelper->addScript($tmpl_url.'/js/html5.js', 'lt IE 9');
}

// offer Chrome Frame for IE lt 9
$templateHelper->addMetaData('X-UA-Compatible', 'IE=Edge,chrome=1', 'lt IE 9', true);
if ($loadChromeFrame) {
	$templateHelper->addScript('//ajax.googleapis.com/ajax/libs/chrome-frame/' . $loadChromeFrame . '/CFInstall.min.js',
		'lt IE 9',
		array('defer'=>true,'onload'=>'var e=document.createElement("DIV");if (e && CFInstall) {e.id="gcf_placeholder";e.style.zIndex="9999";CFInstall.check({ node: "gcf_placeholder" });}')
		);
}

/* Preview Module Styles for use with index.php?tp=1 */
if ($app->get('input')->getBool('tp') && JComponentHelper::getParams('com_templates')->get('template_positions_display') ) {
	$styleDeclarations[] = '.mod-preview{position:relative}
.mod-preview-wrapper{border:1px solid #ccc;-moz-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;-webkit-box-shadow:2px 2px 6px rgba(0,0,0,0.6); -moz-box-shadow:2px 2px 6px rgba(0,0,0,0.6); box-shadow:2px 2px 6px rgba(0,0,0,0.6); padding:5px 10px;margin:5px 0;opacity:0.9;background:#666;z-index:999;min-height:50px}
.mod-preview-wrapper div.moduletable, .mod-preview-wrapper .menu, .mod-preview-wrapper #breadcrumbs{opacity:0}
.mod-preview-info{color:#fff;background:none;border:none;z-index:999;position:absolute;left:5px;top:5px;font-size:110%}
#left .mod-preview-wrapper, #left2 .mod-preview-wrapper, #right .mod-preview-wrapper, #right2 .mod-preview-wrapper{height:940px}';
}

$scriptDeclarations[] = "if (typeof window.JSON === 'undefined') {document.write('<script src=\"".$tmpl_url."/js/json2.min.js\"><\/script>');}";

// add collected custom style declarations
if ( count($styleDeclarations) ) {
	$this->addStyleDeclaration(implode(PHP_EOL,$styleDeclarations));
	$templateHelper->addStyleDeclaration($styleDeclarations);
}

// add collected custom script declarations
if ( count($scriptDeclarations) ) {
	$this->addScriptDeclaration(implode(PHP_EOL,$scriptDeclarations));
	$templateHelper->addScriptDeclaration($scriptDeclarations);
}

// merge $columnLayout into a string
$columnLayout[] = $fluidMedia;
$columnLayout = array_unique($columnLayout);
$columnLayout = implode(' ', $columnLayout);

