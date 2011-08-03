<?php defined('_JEXEC') or die;
/**
 * @package		Templates
 * @subpackage  Helper
 * @author		Joomla Engineering http://joomlaengineering.com
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @return JDocumentHtml
 * @uses JSite
 * @uses JFactory
 */

/** Load the ConstructTemplateHelper Class */
# JLoader::import($this->template.'.elements.helper', 'templates', 'templates.');
require_once dirname(__FILE__) . '/helper.php';

/** @var $app JSite To enable use of site configuration */
$app 					= JFactory::getApplication();
/** @var $base_url string Get the base URL of the website */
$base_url 				= JURI::base();
/** @var $tmpl_url string Define relative shortcut for current template directory */
$tmpl_url 				= $base_url. 'templates/'. $this->template;
/** @var $url JURI Get the current URL */
$url 					= clone(JURI::getInstance());

/* Define shortcuts for often used template parameters */
$customStyleSheet 		= 		 $this->params->get('customStyleSheet');
$enableSwitcher 		= (bool) $this->params->get('enableSwitcher');
$useStickyFooter 		= (bool) $this->params->get('useStickyFooter');
$stickyFooterHeight		= (bool) $this->params->get('stickyFooterHeight');
$IECSS3					= (bool) $this->params->get('IECSS3');
$IECSS3Targets			= 		 $this->params->get('IECSS3Targets');
$IE6TransFix			= (bool) $this->params->get('IE6TransFix');
$IE6TransFixTargets		= 		 $this->params->get('IE6TransFixTargets');
$fluidMedia				= (bool) $this->params->get('fluidMedia');
$fullWidth				= 		 $this->params->get('fullWidth');
$siteWidth				= 		 $this->params->get('siteWidth');
$siteWidthType			= 		 $this->params->get('siteWidthType');
$siteWidthUnit			= 		 $this->params->get('siteWidthUnit');
$showDiagnostics 		= (bool) $this->params->get('showDiagnostics');
$loadModal				= (bool) $this->params->get('loadModal');
$loadMoo 				= (bool) $this->params->get('loadMoo', $loadModal);
$loadjQuery 			= 		 $this->params->get('loadjQuery');

/** @var $googleWebFont array Web-Fonts (for BC the 1. param is read using the "old" field name) */
$googleWebFont = $googleWebFontSize = $googleWebFontTargets = array();
for ($i=1; $i <= ConstructTemplateHelper::MAX_WEBFONTS; $i++) {
	$googleWebFont[$i]			= $this->params->def('googleWebFont'.$i);
	$googleWebFontSize[$i]		= $this->params->def('googleWebFontSize'.$i);
	$googleWebFontTargets[$i]	= $this->params->def('googleWebFontTargets'.$i);
}

// Change generator tag
$this->setGenerator( trim($this->params->get('setGeneratorTag')) );

// Load the MooTools JavaScript Library
if ($loadMoo == true) {
	JHtmlBehavior::framework(true);
	if ($loadModal) {
		// Enable modal pop-ups - see html/mod_footer/default.php to customize
		JHtmlBehavior('modal');
	}
}

// Remove MooTools if set to no.
if ($loadMoo == false) {
	$head = $this->getHeadData();
	// without MooTools we must drop all but core.js
	$moos	= preg_grep('#/media/system/js(\/(?!core))#', array_keys($head['scripts']));
	foreach ($moos as $src) {
		unset($head['scripts'][$src]);
	}
	$this->setHeadData($head);
}

#--------------------------------------------------------------------------#

$templateHelper = &ConstructTemplateHelper::getInstance($this);

// will contain custom script code depending on selected params
$scriptDeclarations	= array();

#----------------------------- Module Counts -----------------------------#
// from http://groups.google.com/group/joomla-dev-general/browse_thread/thread/b54f3f131dd173d

if ($headerAboveCount = $templateHelper->getModulesCount('header-above', ConstructTemplateHelper::MAX_MODULES)) :
	$headerAboveClass = 'above count-'.$headerAboveCount[0];
endif;

#--------------------------------------------------------------------------#

if ($headerBelowCount = $templateHelper->getModulesCount('header-below', ConstructTemplateHelper::MAX_MODULES)) :
	$headerBelowClass = 'below count-'.$headerBelowCount[0];
endif;

#--------------------------------------------------------------------------#

if ($navBelowCount = $templateHelper->getModulesCount('nav-below', ConstructTemplateHelper::MAX_MODULES)) :
	$navBelowClass = 'below count-'.$navBelowCount[0];
endif;

#--------------------------------------------------------------------------#

if ($contentAboveCount = $templateHelper->getModulesCount('nav-below', ConstructTemplateHelper::MAX_MODULES)) :
	$contentAboveClass = 'above count-'.$contentAboveCount[0];
endif;

#--------------------------------------------------------------------------#

if ($contentBelowCount = $templateHelper->getModulesCount('content-below', ConstructTemplateHelper::MAX_MODULES)) :
	$contentBelowClass = 'below count-'.$contentBelowCount[0];
endif;

#--------------------------------------------------------------------------#

if ($footerAboveCount = $templateHelper->getModulesCount('footer-above', ConstructTemplateHelper::MAX_MODULES)) :
	$footerAboveClass = 'above count-'.$footerAboveCount[0];
endif;

#------------------------------ Column Layout -----------------------------#

$columnGroupCount = $templateHelper->getModulesCount('column', ConstructTemplateHelper::MAX_COLUMNS);

$columnGroupAlphaCount = $columnGroupCount[1] + $columnGroupCount[2];
if ($columnGroupAlphaCount) : $columnGroupAlphaClass = 'column-alpha colcount-'.$columnGroupAlphaCount; endif;

$columnGroupBetaCount = $columnGroupCount[3] + $columnGroupCount[4];
if ($columnGroupBetaCount) : $columnGroupBetaClass = 'column-beta colcount-'.$columnGroupBetaCount; endif;

# alpha-X-main-beta-Y
$columnLayout = 'main-only';
if ($columnGroupAlphaCount > 0) {
	$columnLayout = 'alpha-main';
	if ($columnGroupBetaCount > 0) {
		$columnLayout .= '-beta';
	}
} elseif ($columnGroupBetaCount > 0) {
	$columnLayout = 'main-beta';
}

// FB::info($columnGroupCount, 'columns: '.$columnLayout); #HACK FB log filename

#---------------------------- Head Elements --------------------------------#

// Custom tags
$this->addCustomTag('<meta name="copyright" content="'.$app->getCfg('sitename').'" />');

// Transparent favicon
if (is_file(JPATH_THEMES .'/'. $this->template .'/favicon.png')) {
	$this->addFavicon($tmpl_url.'/favicon.png', 'image/png', 'icon');
} else if (is_file(JPATH_THEMES .'/'. $this->template .'/favicon.ico')) {
	$this->addFavicon($tmpl_url.'/favicon.ico', 'image/x-icon', 'icon');
}

// Style sheets
$this->addStyleSheet($tmpl_url.'/css/core/base.css','text/css');
$this->addStyleSheet($tmpl_url.'/css/core/oocss.css','text/css');
$this->addStyleSheet($tmpl_url.'/css/core/screen.css','text/css','screen');
$this->addStyleSheet($tmpl_url.'/css/core/print.css','text/css','print');
if ($customStyleSheet) {
	$this->addStyleSheet($tmpl_url.'/css/'.$customStyleSheet,'text/css','screen,projection,print');
}
if ($this->direction == 'rtl') {
	$this->addStyleSheet($tmpl_url.'/css/core/rtl.css','text/css','screen');
}
// cheap and all but smart
if ( in_array(JRequest::get('layout','cmd'), array('edit','form')) ) {
	$this->addStyleSheet($tmpl_url.'/css/core/edit-form','text/css','screen');
}

// Style sheet switcher
if ($enableSwitcher) {
	$this->addHeadLink($tmpl_url.'/css/core/diagnostic.css', 'alternate stylesheet', 'rel', $attribs = array('title'=>'diagnostic'));
	$this->addHeadLink($tmpl_url.'/css/core/wireframe.css', 'alternate stylesheet', 'rel', $attribs = array('title'=>'wireframe'));
	$this->addScript($tmpl_url.'/js/styleswitch.js');
}

// Typography (protocol relative URLs)
for ($i=1; $i<=ConstructTemplateHelper::MAX_WEBFONTS; $i++) {
	if ($googleWebFont[$i]) {
		// Fix Google Web Font name for CSS
		$googleWebFontFamily[$i] = str_replace(array('+',':bold',':italic')," ",$googleWebFont[$i]);
		$this->addStyleSheet('//fonts.googleapis.com/css?family='.$googleWebFont[$i].'');
		$this->addStyleDeclaration(
			$googleWebFontTargets[$i]
			.' {font-family:'.$googleWebFontFamily[$i].', serif;'
			.(($googleWebFontSize[$i]>0) ? 'font-size:'.$googleWebFontSize[$i].';' : '')
			.'}'
			);
	}
}

// JavaScript
if ($loadMoo == true) {
	$scriptDeclarations[] = "\tif (window.addEvent){window.addEvent('domready',function(){new SmoothScroll({duration:1200},window);});}";
}
if ($loadjQuery) {
	$this->addScript('//ajax.googleapis.com/ajax/libs/jquery/'. $loadjQuery .'/jquery.min.js');

	$head = $this->getHeadData();
	$jqjs = preg_grep('#(jquery\.)#', array_keys($head['scripts']));
/*
 * #FIXME view layouts können jQuery-Plugins dazuladen, die dann
 * jedoch zu früh ausgeführt werden. jQuery selbst wird zu spät geladen...
	// jQuery Google CDN takes precendence
	foreach ((array) $jqjs as $i => $url) {
		if ( false !== strpos($url, 'googleapis.com') ) {
			$jquery = array($url => $head['scripts'][$url]);
			// nuke old entry
			unset($head['scripts'][$url]);
			// recombine
			$head['scripts'] = $jquery + $head['scripts'];
		}
	}
	$this->setHeadData($head);
*/
	// without MooTools we must drop all but core.js
	if ($loadMoo == true) {
		$scriptDeclarations[] = "\tif (window.jQuery){jQuery.noConflict();}";
	}
}

// Layout Declarations
if ($siteWidth) {
	$this->addStyleDeclaration('#body-container, #header-above {'.$siteWidthType.':'.$siteWidth.$siteWidthUnit.'}');
}
if (($siteWidthType == 'max-width') && $fluidMedia ) {
	$columnLayout .= ' fluid-media';
}
if (!$fullWidth) {
	$this->addStyleDeclaration('#header, #footer {'.$siteWidthType.':'.$siteWidth.$siteWidthUnit.'; margin:0 auto}');
}

// Internet Explorer Fixes
# html5 shim
$this->addCustomTag('<!--[if lt IE 9]><script src="'.$base_url.'media/system/js/html5.js" type="text/javascript"></script><![endif]-->');

if ($IECSS3) {
	$this->addCustomTag(
  		 PHP_EOL . '<!--[if lt IE 9]>'
  		.PHP_EOL . '<style type="text/css">'.$IECSS3Targets.' {behavior:url("'.$tmpl_url.'/js/PIE.htc")}</style>'
  		.PHP_EOL . '<![endif]-->');
}

if ($useStickyFooter) {
	$this->addStyleDeclaration(
		 PHP_EOL . '.sticky-footer #body-container {padding-bottom:'.$stickyFooterHeight.'px;}'
		.PHP_EOL . '.sticky-footer #footer {margin-top:-'.$stickyFooterHeight.'px;height:'.$stickyFooterHeight.'px;}'
		);
	$this->addCustomTag("\n"
		.'<!--[if lt IE 7]>'
		.'<style type="text/css">body.sticky-footer #footer-push {display:table;height:100%}</style>'
		.'<![endif]-->');
}

$this->addCustomTag(
		 PHP_EOL . '<!--[if lt IE 7]>'
		.PHP_EOL . '<link rel="stylesheet" href="'.$tmpl_url.'/css/core/ie6.css" type="text/css" media="screen" />'
		.PHP_EOL . '<style type="text/css">'
		.PHP_EOL . 'body {text-align:center}'
		.PHP_EOL . '#body-container {text-align:left}'
		.PHP_EOL .  ((!$fullWidth)
			? PHP_EOL . '#body-container, #header-above, #header, #footer {width: expression( document.body.clientWidth >'.($siteWidth -1).' ? "'.$siteWidth.$siteWidthUnit.'" : "auto" );margin:0 auto}'
			: PHP_EOL . '#body-container, #header-above {width: expression( document.body.clientWidth >'.($siteWidth -1).' ? "'.$siteWidth.$siteWidthUnit.'" : "auto");margin:0 auto;}')
		.PHP_EOL . '</style>'
		.PHP_EOL . '<![endif]-->'
		);

if ($IE6TransFix) {
	$this->addScript($tmpl_url.'/js/DD_belatedPNG_0.0.8a-min.js');
	$scriptDeclarations[] = "\t/* IE6TransFix */ if (DD_belatedPNG.fix) {DD_belatedPNG.fix('". $IE6TransFixTargets ."')}";
}

// add collected custom script declarations
if ( count($scriptDeclarations) ) {
	$this->addScriptDeclaration(implode("\n",$scriptDeclarations));
}


