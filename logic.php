<?php defined('_JEXEC') or die;
/**
* @package		Template Framework for Joomla! 1.6
* @author		Joomla Engineering http://joomlaengineering.com
* @copyright	Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
* @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

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
$googleWebFont 			= $this->params->get('googleWebFont');
$googleWebFontSize		= $this->params->get('googleWebFontSize');
$googleWebFontTargets	= $this->params->get('googleWebFontTargets');
$googleWebFont2			= $this->params->get('googleWebFont2');
$googleWebFontSize2		= $this->params->get('googleWebFontSize2');
$googleWebFontTargets2	= $this->params->get('googleWebFontTargets2');
$googleWebFont3			= $this->params->get('googleWebFont3');
$googleWebFontSize3		= $this->params->get('googleWebFontSize3');
$googleWebFontTargets3	= $this->params->get('googleWebFontTargets3');
$loadMoo 				= $this->params->get('loadMoo');
$loadModal				= $this->params->get('loadModal');
$loadjQuery 			= $this->params->get('loadjQuery');
$setGeneratorTag		= $this->params->get('setGeneratorTag');
$showDiagnostics 		= $this->params->get('showDiagnostics');
$siteWidth				= $this->params->get('siteWidth');
$siteWidthType			= $this->params->get('siteWidthType');
$siteWidthUnit			= $this->params->get('siteWidthUnit');
$stickyFooterHeight		= $this->params->get('stickyFooterHeight');
$useStickyFooter 		= $this->params->get('useStickyFooter');

// Change generator tag
$this->setGenerator($setGeneratorTag);

// Load the MooTools JavaScript Library
if ($loadMoo) {
	JHTML::_('behavior.framework', true);
	if ($loadModal) {
		// Enable modal pop-ups - see html/mod_footer/default.php to customize
		JHTML::_('behavior.modal');
	}
}

// Remove MooTools if set to no.
if ( !$loadMoo ) {
	$head=$this->getHeadData();
	reset($head['scripts']);
	unset($head['scripts'][$this->baseurl . '/media/system/js/mootools-core.js']);
	unset($head['scripts'][$this->baseurl . '/media/system/js/mootools-more.js']);		
	$this->setHeadData($head);
}

// Fix Google Web Font name for CSS
$googleWebFontFamily 	= str_replace(array('+',':bold',':italic')," ",$googleWebFont);
$googleWebFontFamily2 	= str_replace(array('+',':bold',':italic')," ",$googleWebFont2);
$googleWebFontFamily3 	= str_replace(array('+',':bold',':italic')," ",$googleWebFont3);

// Get the name of the extended template override group
$overrideGroup			= str_replace(".css","",$customStyleSheet);

#----------------------------- Moldule Counts -----------------------------#
// from http://groups.google.com/group/joomla-dev-general/browse_thread/thread/b54f3f131dd173d

$headerAboveCount1 = (int) ($this->countModules('header-above-1') > 0);
$headerAboveCount2 = (int) ($this->countModules('header-above-2') > 0);
$headerAboveCount3 = (int) ($this->countModules('header-above-3') > 0);
$headerAboveCount4 = (int) ($this->countModules('header-above-4') > 0);
$headerAboveCount5 = (int) ($this->countModules('header-above-5') > 0);
$headerAboveCount6 = (int) ($this->countModules('header-above-6') > 0);

$headerAboveCount = $headerAboveCount1 + $headerAboveCount2 + $headerAboveCount3 + $headerAboveCount4 + $headerAboveCount5 + $headerAboveCount6;

if ($headerAboveCount) : $headerAboveClass = 'count-'.$headerAboveCount; endif;

#--------------------------------------------------------------------------#

$headerBelowCount1 = (int) ($this->countModules('header-below-1') > 0);
$headerBelowCount2 = (int) ($this->countModules('header-below-2') > 0);
$headerBelowCount3 = (int) ($this->countModules('header-below-3') > 0);
$headerBelowCount4 = (int) ($this->countModules('header-below-4') > 0);
$headerBelowCount5 = (int) ($this->countModules('header-below-5') > 0);
$headerBelowCount6 = (int) ($this->countModules('header-below-6') > 0);

$headerBelowCount = $headerBelowCount1 + $headerBelowCount2 + $headerBelowCount3 + $headerBelowCount4 + $headerBelowCount5 + $headerBelowCount6;

if ($headerBelowCount) : $headerBelowClass = 'count-'.$headerBelowCount; endif;

#--------------------------------------------------------------------------#

$navBelowCount1 = (int) ($this->countModules('nav-below-1') > 0);
$navBelowCount2 = (int) ($this->countModules('nav-below-2') > 0);
$navBelowCount3 = (int) ($this->countModules('nav-below-3') > 0);
$navBelowCount4 = (int) ($this->countModules('nav-below-4') > 0);
$navBelowCount5 = (int) ($this->countModules('nav-below-5') > 0);
$navBelowCount6 = (int) ($this->countModules('nav-below-6') > 0);

$navBelowCount = $navBelowCount1 + $navBelowCount2 + $navBelowCount3 + $navBelowCount4 + $navBelowCount5 + $navBelowCount6;

if ($navBelowCount) : $navBelowClass = 'count-'.$navBelowCount; endif;

#--------------------------------------------------------------------------#

$contentAboveCount1 = (int) ($this->countModules('content-above-1') > 0);
$contentAboveCount2 = (int) ($this->countModules('content-above-2') > 0);
$contentAboveCount3 = (int) ($this->countModules('content-above-3') > 0);
$contentAboveCount4 = (int) ($this->countModules('content-above-4') > 0);
$contentAboveCount5 = (int) ($this->countModules('content-above-5') > 0);
$contentAboveCount6 = (int) ($this->countModules('content-above-6') > 0);

$contentAboveCount = $contentAboveCount1 + $contentAboveCount2 + $contentAboveCount3 + $contentAboveCount4 + $contentAboveCount5 + $contentAboveCount6;

if ($contentAboveCount) : $contentAboveClass = 'count-'.$contentAboveCount; endif;

#--------------------------------------------------------------------------#

$contentBelowCount1 = (int) ($this->countModules('content-below-1') > 0);
$contentBelowCount2 = (int) ($this->countModules('content-below-2') > 0);
$contentBelowCount3 = (int) ($this->countModules('content-below-3') > 0);
$contentBelowCount4 = (int) ($this->countModules('content-below-4') > 0);
$contentBelowCount5 = (int) ($this->countModules('content-below-5') > 0);
$contentBelowCount6 = (int) ($this->countModules('content-below-6') > 0);

$contentBelowCount = $contentBelowCount1 + $contentBelowCount2 + $contentBelowCount3 + $contentBelowCount4 + $contentBelowCount5 + $contentBelowCount6;

if ($contentBelowCount) : $contentBelowClass = 'count-'.$contentBelowCount; endif;

#--------------------------------------------------------------------------#

$footerAboveCount1 = (int) ($this->countModules('footer-above-1') > 0);
$footerAboveCount2 = (int) ($this->countModules('footer-above-2') > 0);
$footerAboveCount3 = (int) ($this->countModules('footer-above-3') > 0);
$footerAboveCount4 = (int) ($this->countModules('footer-above-4') > 0);
$footerAboveCount5 = (int) ($this->countModules('footer-above-5') > 0);
$footerAboveCount6 = (int) ($this->countModules('footer-above-6') > 0);

$footerAboveCount = $footerAboveCount1 + $footerAboveCount2 + $footerAboveCount3 + $footerAboveCount4 + $footerAboveCount5 + $footerAboveCount6;

if ($footerAboveCount) : $footerAboveClass = 'count-'.$footerAboveCount; endif;

#------------------------------ Column Layout -----------------------------#

$column1Count = (int) ($this->countModules('column-1') > 0);
$column2Count = (int) ($this->countModules('column-2') > 0);

$columnGroupAlphaCount = $column1Count + $column2Count;

if ($columnGroupAlphaCount) : $columnGroupAlphaClass = 'count-'.$columnGroupAlphaCount; endif;

$column3Count = (int) ($this->countModules('column-3') > 0);
$column4Count = (int) ($this->countModules('column-4') > 0);

$columnGroupBetaCount = $column3Count + $column4Count;
if ($columnGroupBetaCount) : $columnGroupBetaClass = 'count-'.$columnGroupBetaCount; endif;


$columnLayout= 'main-only';
	
if (($columnGroupAlphaCount > 0 ) && ($columnGroupBetaCount == 0)) :
	$columnLayout = 'alpha-'.$columnGroupAlphaCount.'-main';
elseif (($columnGroupAlphaCount > 0) && ($columnGroupBetaCount > 0)) :
	$columnLayout = 'alpha-'.$columnGroupAlphaCount.'-main-beta-'.$columnGroupBetaCount;
elseif (($columnGroupAlphaCount == 0) && ($columnGroupBetaCount > 0)) :
	$columnLayout = 'main-beta-'.$columnGroupBetaCount;
endif;
	
#--------------------------------------------------------------------------#

$templateIndex			= JPATH_THEMES.'/'.$this->template.'/layouts/index.php';
$templateGroupIndex		= JPATH_THEMES.'/'.$this->template.'/layouts/'.$overrideGroup.'-index.php';

#--------------------------------------------------------------------------#	

if(file_exists($templateGroupIndex)){
		$alternateIndexFile = $templateGroupIndex;}
elseif(file_exists($templateIndex)){
		$alternateIndexFile = $templateIndex;}		
else unset($alternateIndexFile);

#---------------------------- Head Elements --------------------------------#

// Custom tags
$doc->addCustomTag('<meta name="copyright" content="'.$app->getCfg('sitename').'" />');

// Transparent favicon
$doc->addFavicon($template.'/favicon.png', 'image/png','icon');

// Style sheets
$doc->addStyleSheet($template.'/css/screen.css','text/css','screen');
$doc->addStyleSheet($template.'/css/print.css','text/css','print');
if ($customStyleSheet !='-1')
	$doc->addStyleSheet($template.'/css/'.$customStyleSheet,'text/css','screen');
if ($this->direction == 'rtl')
	$doc->addStyleSheet($template.'/css/rtl.css','text/css','screen');

// Style sheet switcher
if ($enableSwitcher) {
	$doc->addCustomTag('<link rel="alternate stylesheet" href="'.$template.'/css/diagnostic.css" type="text/css" media="screen" title="diagnostic" />');
	$doc->addCustomTag('<link rel="alternate stylesheet" href="'.$template.'/css/wireframe.css" type="text/css" media="screen" title="wireframe" />');
	$doc->addScript($template.'/js/styleswitch.js');
}

// Typography
if ($googleWebFont) {
	$doc->addStyleSheet('http://fonts.googleapis.com/css?family='.$googleWebFont.'');
	$doc->addStyleDeclaration('  '.$googleWebFontTargets.' {font-family:'.$googleWebFontFamily.', serif;font-size:'.$googleWebFontSize.';}');
}
if ($googleWebFont2) {
	$doc->addStyleSheet('http://fonts.googleapis.com/css?family='.$googleWebFont2.'');
	$doc->addStyleDeclaration('  '.$googleWebFontTargets2.' {font-family:'.$googleWebFontFamily2.', serif;font-size:'.$googleWebFontSize2.';}');
}
if ($googleWebFont3) {
	$doc->addStyleSheet('http://fonts.googleapis.com/css?family='.$googleWebFont3.'');
	$doc->addStyleDeclaration('  '.$googleWebFontTargets3.' {font-family:'.$googleWebFontFamily3.', serif;font-size:'.$googleWebFontSize3.';}');
}

// JavaScript
$doc->addCustomTag("\n".'  <script type="text/javascript">window.addEvent(\'domready\',function(){new SmoothScroll({duration:1200},window);});</script>');
if ($loadjQuery)
	$doc->addScript($loadjQuery);

// Layout Declarations
if ($siteWidth)
	$doc->addStyleDeclaration("\n".'  #body-container, #header-above {'.$siteWidthType.':'.$siteWidth.$siteWidthUnit.'}');
if (($siteWidthType == 'max-width') && $fluidMedia )
	$doc->addStyleDeclaration("\n".'  img, object {max-width:100%}');		
if (!$fullWidth)
	$doc->addStyleDeclaration("\n".'  #header, #footer {'.$siteWidthType.':'.$siteWidth.$siteWidthUnit.'; margin:0 auto}');
	
// Internet Explorer Fixes	
if ($IECSS3) {
  $doc->addCustomTag("\n".'  <!--[if !IE 9]>
  <style type="text/css">'.$IECSS3Targets.' {behavior:url("'.$baseUrl.'templates/'.$this->template.'/js/PIE.htc")}</style>
  <![endif]-->');
}
if ($useStickyFooter) {
	$doc->addStyleDeclaration("\n".'  .sticky-footer #body-container {padding-bottom:'.$stickyFooterHeight.'px;}
  .sticky-footer #footer {margin-top:-'.$stickyFooterHeight.'px;height:'.$stickyFooterHeight.'px;}');
	$doc->addCustomTag("\n".'  <!--[if lt IE 7]>
  <style type="text/css">body.sticky-footer #footer-push {display:table;height:100%}</style>
  <![endif]-->');
}

$doc->addCustomTag('<!--[if lt IE 7]>
  <link rel="stylesheet" href="'.$template.'/css/ie6.css" type="text/css" media="screen" />
  <style type="text/css">
  body {text-align:center}
  #body-container {text-align:left}');  
  if (!$fullWidth) {
  $doc->addCustomTag('#body-container, #header-above, #header, #footer {width: expression( document.body.clientWidth >'.($siteWidth -1).' ? "'.$siteWidth.$siteWidthUnit.'" : "auto" );margin:0 auto}');
  }
  else {
  $doc->addCustomTag('#body-container, #header-above {width: expression( document.body.clientWidth >'.($siteWidth -1).' ? "'.$siteWidth.$siteWidthUnit.'" : "auto" );margin:0 auto}');
  }
  $doc->addCustomTag('</style>');
  if ($IE6TransFix) {
  $doc->addCustomTag('  <script type="text/javascript" src="'.$template.'/js/DD_belatedPNG_0.0.8a-min.js"></script>
  <script>DD_belatedPNG.fix(\''.$IE6TransFixTargets.'\');</script>');
  }
  $doc->addCustomTag('<![endif]-->');