<?php defined('_JEXEC') or die;
/**
* @package		Template Framework for Joomla! 1.6
* @author		Joomla Engineering http://joomlaengineering.com
* @copyright	Copyright (C) 2010 Matt Thomas | Joomla Engineering. All rights reserved.
* @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

#--------------------------------------------------------------------------#
// from http://groups.google.com/group/joomla-dev-general/browse_thread/thread/b54f3f131dd173d

$supraCount1 = (int) ($this->countModules('supra1') > 0);
$supraCount2 = (int) ($this->countModules('supra2') > 0);
$supraCount3 = (int) ($this->countModules('supra3') > 0);
$supraCount4 = (int) ($this->countModules('supra4') > 0);

$supraModuleCount = $supraCount1 + $supraCount2 + $supraCount3 + $supraCount4;

if ($supraModuleCount) : $supraModuleClass = 'count-'.$supraModuleCount; endif;

#--------------------------------------------------------------------------#

$subHeadCount1 = (int) ($this->countModules('subhead1') > 0);
$subHeadCount2 = (int) ($this->countModules('subhead2') > 0);
$subHeadCount3 = (int) ($this->countModules('subhead3') > 0);
$subHeadCount4 = (int) ($this->countModules('subhead4') > 0);
$subHeadCount5 = (int) ($this->countModules('subhead5') > 0);
$subHeadCount6 = (int) ($this->countModules('subhead6') > 0);

$subHeadCount = $subHeadCount1 + $subHeadCount2 + $subHeadCount3 + $subHeadCount4 + $subHeadCount5 + $subHeadCount6;

if ($subHeadCount) : $subHeadClass = 'count-'.$subHeadCount; endif;

#--------------------------------------------------------------------------#

$subNavCount1 = (int) ($this->countModules('user1') > 0);
$subNavCount2 = (int) ($this->countModules('user2') > 0);
$subNavCount3 = (int) ($this->countModules('user3') > 0);
$subNavCount4 = (int) ($this->countModules('user4') > 0);

$subNavCount = $subNavCount1 + $subNavCount2 + $subNavCount3 + $subNavCount4;

if ($subNavCount) : $subNavClass = 'count-'.$subNavCount; endif;

#--------------------------------------------------------------------------#

$contentTopCount1 = (int) ($this->countModules('top') > 0);
$contentTopCount2 = (int) ($this->countModules('top2') > 0);
$contentTopCount3 = (int) ($this->countModules('top3') > 0);
$contentTopCount4 = (int) ($this->countModules('top4') > 0);

$contentTopCount = $contentTopCount1 + $contentTopCount2 + $contentTopCount3 + $contentTopCount4;

if ($contentTopCount) : $contentTopClass = 'count-'.$contentTopCount; endif;

#--------------------------------------------------------------------------#

$contentBottomCount1 = (int) ($this->countModules('user5') > 0);
$contentBottomCount2 = (int) ($this->countModules('user6') > 0);
$contentBottomCount3 = (int) ($this->countModules('user7') > 0);
$contentBottomCount4 = (int) ($this->countModules('user8') > 0);

$contentBottomCount = $contentBottomCount1 + $contentBottomCount2 + $contentBottomCount3 + $contentBottomCount4;

if ($contentBottomCount) : $contentBottomClass = 'count-'.$contentBottomCount; endif;

#--------------------------------------------------------------------------#

$contentLeftCount1 = (int) ($this->countModules('left') > 0);
$contentLeftCount2 = (int) ($this->countModules('left2') > 0);

$contentLeftCount = $contentLeftCount1 + $contentLeftCount2;

if ($contentLeftCount) : $contentLeftClass = 'count-'.$contentLeftCount; endif;

#--------------------------------------------------------------------------#

$contentRightCount1 = (int) ($this->countModules('right') > 0);
$contentRightCount2 = (int) ($this->countModules('right2') > 0);

$contentRightCount = $contentRightCount1 + $contentRightCount2;

if ($contentRightCount) : $contentRightClass = 'count-'.$contentRightCount; endif;

#--------------------------------------------------------------------------#

$subContentCount1 = (int) ($this->countModules('sub1') > 0);
$subContentCount2 = (int) ($this->countModules('sub2') > 0);
$subContentCount3 = (int) ($this->countModules('sub3') > 0);
$subContentCount4 = (int) ($this->countModules('sub4') > 0);
$subContentCount5 = (int) ($this->countModules('sub5') > 0);
$subContentCount6 = (int) ($this->countModules('sub6') > 0);

$subContentCount = $subContentCount1 + $subContentCount2 + $subContentCount3 + $subContentCount4 + $subContentCount5 + $subContentCount6;

if ($subContentCount) : $subContentClass = 'count-'.$subContentCount; endif;

#--------------------------------------------------------------------------#

$columnLayout= 'main-only';
	
if (($contentLeftCount > 0 ) && ($contentRightCount == 0)) :
	$columnLayout = 'left-'.$contentLeftCount.'-main';
elseif (($contentLeftCount > 0) && ($contentRightCount > 0)) :
	$columnLayout = 'left-'.$contentLeftCount.'-main-right-'.$contentRightCount;
elseif (($contentLeftCount == 0) && ($contentRightCount > 0)) :
	$columnLayout = 'main-right-'.$contentRightCount;
endif;
	
#--------------------------------------------------------------------------#