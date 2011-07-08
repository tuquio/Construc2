<?php defined('_JEXEC') or die;
/**
* @package		Template Framework for Joomla! 1.6
* @author		Joomla Engineering http://joomlaengineering.com
* @author		WebMechanic http://webmechanic.biz
* @copyright	Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
* @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Load the ConstructTemplateHelper Class
require_once dirname(__FILE__).'/helper.php';

// To enable use of site configuration
$app 					= JFactory::getApplication();
// Get the base URL of the website
$baseUrl 				= JURI::base();
// Returns a reference to the global document object
$doc 					= JFactory::getDocument();
// Define relative shortcut for current template directory
$template 				= 'templates/'.$this->template;
// Get the current URL
$url 					= clone(JURI::getInstance());
// To access the current user object
$user 					= JFactory::getUser();

// Define shortcuts for template parameters
$customStyleSheet 		= $this->params->get('customStyleSheet');
$enableSwitcher 		= $this->params->get('enableSwitcher');
$IECSS3					= $this->params->get('IECSS3');
$IECSS3Targets			= $this->params->get('IECSS3Targets');
$IE6TransFix			= $this->params->get('IE6TransFix');
$IE6TransFixTargets		= $this->params->get('IE6TransFixTargets');
$fluidMedia				= $this->params->get('fluidMedia');
$fullWidth				= $this->params->get('fullWidth');
$loadModal				= (bool) $this->params->get('loadModal');
$loadMoo 				= (bool) $this->params->get('loadMoo', $loadModal);
$loadjQuery 			= $this->params->get('loadjQuery');
$setGeneratorTag		= trim($this->params->get('setGeneratorTag'));
$showDiagnostics 		= (bool) $this->params->get('showDiagnostics', 0);
$siteWidth				= $this->params->get('siteWidth');
$siteWidthType			= $this->params->get('siteWidthType');
$siteWidthUnit			= $this->params->get('siteWidthUnit');
$stickyFooterHeight		= $this->params->get('stickyFooterHeight');
$useStickyFooter 		= $this->params->get('useStickyFooter');

// Web-Fonts (for BC the 1. param is read using the "old" field name)
$googleWebFont = $googleWebFontSize = $googleWebFontTargets = array();
$googleWebFont[1]			= $this->params->def('googleWebFont1', $this->params->def('googleWebFont'));
$googleWebFontSize[1]		= $this->params->def('googleWebFontSize1', $this->params->def('googleWebFontSize'));
$googleWebFontTargets[1]	= $this->params->def('googleWebFontTargets1', $this->params->def('googleWebFontTargets'));
$googleWebFont[2]			= $this->params->get('googleWebFont2');
$googleWebFontSize[2]		= $this->params->get('googleWebFontSize2');
$googleWebFontTargets[2]	= $this->params->get('googleWebFontTargets2');
$googleWebFont[3]			= $this->params->get('googleWebFont3');
$googleWebFontSize[3]		= $this->params->get('googleWebFontSize3');
$googleWebFontTargets[3]	= $this->params->get('googleWebFontTargets3');

// Change generator tag
$this->setGenerator($setGeneratorTag);

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
	reset($head['scripts']);
	$moos = preg_grep("!/media/system/js/(mootools|caption)!", array_keys($head['scripts']));
	foreach ($moos as $src) {
		unset($head['scripts'][$src]);
	}
	$this->setHeadData($head);
}

// Get the name of the extended template override group
if ($customStyleSheet == '-1') {
	$customStyleSheet   = null;
	$overrideTheme		= null;
} else {
	$overrideTheme		= str_replace('.css', '', $customStyleSheet);
}

// will contain custom script code depending on selected params
$scriptDeclarations 	= array();

#--------------------------------------------------------------------------#

$templateHelper 		= new ConstructTemplateHelper($this);
if ( $overrideTheme ) {
	$templateHelper->addLayout($overrideTheme);
}

#----------------------------- Module Counts -----------------------------#
// from http://groups.google.com/group/joomla-dev-general/browse_thread/thread/b54f3f131dd173d

if ($headerAboveCount = $templateHelper->getModulesCount('header-above', 6)) :
	$headerAboveClass = 'header-above count-'.$headerAboveCount[0];
endif;

#--------------------------------------------------------------------------#

$headerBelowCount = array();
for ($i=1; $i<=6; $i++) :
	$headerBelowCount[$i] = (int) ($this->countModules('header-below-'.$i) > 0);
	$headerBelowCount[0]  = $headerBelowCount[$i];
endfor;
if ($headerBelowCount[0]) : $headerBelowClass = 'header-below count-'.$headerBelowCount;
else : $headerBelowCount = null;
endif;

#--------------------------------------------------------------------------#

$navBelowCount = array();
for ($i=1; $i<=6; $i++) :
	$navBelowCount[$i] = (int) ($this->countModules('nav-below-'.$i) > 0);
	$navBelowCount[0]  = $navBelowCount[$i];
endfor;
if ($navBelowCount[0]) : $navBelowClass = 'nav-below count-'.$navBelowCount;
else : $navBelowCount = null;
endif;

#--------------------------------------------------------------------------#

$contentAboveCount = array();
for ($i=1; $i<=6; $i++) :
	$contentAboveCount[$i] = (int) ($this->countModules('content-above-'.$i) > 0);
	$contentAboveCount[0]  = $contentAboveCount[$i];
endfor;
if ($contentAboveCount[0]) : $contentAboveClass = 'content-above count-'.$contentAboveCount;
else : $contentAboveCount = null;
endif;

#--------------------------------------------------------------------------#

$contentBelowCount = array();
for ($i=1; $i<=6; $i++) :
	$contentBelowCount[$i] = (int) ($this->countModules('content-below-'.$i) > 0);
	$contentBelowCount[0]  = $contentBelowCount[$i];
endfor;
if ($contentBelowCount[0]) : $contentBelowClass = 'content-below count-'.$contentBelowCount;
else : $contentBelowCount = null;
endif;

#--------------------------------------------------------------------------#

$footerAboveCount = array();
for ($i=1; $i<=6; $i++) :
	$footerAboveCount[$i] = (int) ($this->countModules('footer-above-'.$i) > 0);
	$footerAboveCount[0]  = $footerAboveCount[$i];
endfor;
if ($footerAboveCount[0]) : $footerAboveClass = 'footer-above count-'.$footerAboveCount;
else : $footerAboveCount = null;
endif;

#------------------------------ Column Layout -----------------------------#

$column1Count = (int) ($this->countModules('column-1') > 0);
$column2Count = (int) ($this->countModules('column-2') > 0);

$columnGroupAlphaCount = $column1Count + $column2Count;

if ($columnGroupAlphaCount) : $columnGroupAlphaClass = 'column-alpha count-'.$columnGroupAlphaCount; endif;

$column3Count = (int) ($this->countModules('column-3') > 0);
$column4Count = (int) ($this->countModules('column-4') > 0);

$columnGroupBetaCount = $column3Count + $column4Count;
if ($columnGroupBetaCount) : $columnGroupBetaClass = 'column-beta count-'.$columnGroupBetaCount; endif;

$columnLayout= 'main-only';
if (($columnGroupAlphaCount > 0 ) && ($columnGroupBetaCount == 0)) :
	$columnLayout = 'alpha-'.$columnGroupAlphaCount.'-main';
elseif (($columnGroupAlphaCount > 0) && ($columnGroupBetaCount > 0)) :
	$columnLayout = 'alpha-'.$columnGroupAlphaCount.'-main-beta-'.$columnGroupBetaCount;
elseif (($columnGroupAlphaCount == 0) && ($columnGroupBetaCount > 0)) :
	$columnLayout = 'main-beta-'.$columnGroupBetaCount;
endif;

#---------------------------- Head Elements --------------------------------#

// Custom tags
$doc->addCustomTag('<meta name="copyright" content="'.$app->getCfg('sitename').'" />');

// Transparent favicon
if (is_file($template.'/favicon.png')) {
	$doc->addFavicon($template.'/favicon.png', 'image/png', 'icon');
} else if (is_file($template.'/favicon.ico')) {
	$doc->addFavicon($template.'/favicon.ico', 'image/x-icon', 'icon');
}

// Style sheets
$doc->addStyleSheet($template.'/css/core/base.css','text/css');
$doc->addStyleSheet($template.'/css/core/screen.css','text/css','screen');
$doc->addStyleSheet($template.'/css/core/print.css','text/css','print');
if ($customStyleSheet) {
	$doc->addStyleSheet($template.'/css/'.$customStyleSheet,'text/css','screen,projection,print');
}
if ($this->direction == 'rtl') {
	$doc->addStyleSheet($template.'/css/core/rtl.css','text/css','screen');
}

// Style sheet switcher
if ($enableSwitcher) {
	$doc->addHeadLink($template.'/css/core/diagnostic.css', 'alternate stylesheet', 'rel', $attribs = array('title'=>'diagnostic'));
	$doc->addHeadLink($template.'/css/core/wireframe.css', 'alternate stylesheet', 'rel', $attribs = array('title'=>'wireframe'));
	$doc->addScript($template.'/js/styleswitch.js');
}

// Typography (protocol relative URLs)
for ($i=1; $i<=3; $i++) {
	if ($googleWebFont[$i]) {
		// Fix Google Web Font name for CSS
		$googleWebFontFamily[$i] = str_replace(array('+',':bold',':italic')," ",$googleWebFont[$i]);
		$doc->addStyleSheet('//fonts.googleapis.com/css?family='.$googleWebFont[$i].'');
		$doc->addStyleDeclaration(
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
	$doc->addScript($loadjQuery);
	if ($loadMoo == true) {
		$scriptDeclarations[] = "\tif (window.jQuery){jQuery.noConflict();}";
	}
}

// Layout Declarations
if ($siteWidth) {
	$doc->addStyleDeclaration('#body-container, #header-above {'.$siteWidthType.':'.$siteWidth.$siteWidthUnit.'}');
}
if (($siteWidthType == 'max-width') && $fluidMedia ) {
	$columnLayout .= ' fluid-media';
}
if (!$fullWidth) {
	$doc->addStyleDeclaration('#header, #footer {'.$siteWidthType.':'.$siteWidth.$siteWidthUnit.'; margin:0 auto}');
}

// Internet Explorer Fixes
if ($IECSS3) {
	$doc->addCustomTag(
  		 PHP_EOL . '<!--[if IE lt 9]>'
  		.PHP_EOL . '<style type="text/css">'.$IECSS3Targets.' {behavior:url("'.$baseUrl.$template.'/js/PIE.htc")}</style>'
  		.PHP_EOL . '<![endif]-->');
}

if ($useStickyFooter) {
	$doc->addStyleDeclaration(
		 PHP_EOL . '.sticky-footer #body-container {padding-bottom:'.$stickyFooterHeight.'px;}'
		.PHP_EOL . '.sticky-footer #footer {margin-top:-'.$stickyFooterHeight.'px;height:'.$stickyFooterHeight.'px;}'
		);
	$doc->addCustomTag("\n"
		.'<!--[if lt IE 7]>'
		.'<style type="text/css">body.sticky-footer #footer-push {display:table;height:100%}</style>'
		.'<![endif]-->');
}

$doc->addCustomTag(
		 PHP_EOL . '<!--[if lt IE 7]>'
		.PHP_EOL . '<link rel="stylesheet" href="'.$template.'/css/core/ie6.css" type="text/css" media="screen" />'
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
	$doc->addScript($template.'/js/DD_belatedPNG_0.0.8a-min.js');
	$scriptDeclarations[] = "\t/* IE6TransFix */ if (DD_belatedPNG.fix) {DD_belatedPNG.fix('". $IE6TransFixTargets ."')}";
}

// add collected custom script declarations
if ( count($scriptDeclarations) ) {
	$doc->addScriptDeclaration(implode("\n",$scriptDeclarations));
}


