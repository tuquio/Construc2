<!DOCTYPE html><?php defined('_JEXEC') or die;
/**
 * Main Error Page entry script.
 *
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
// 4 l10n stuff
$doc = JFactory::getDocument();
list($lang, $region) = explode('-', $doc->language);

$doc_title = $doc->getTitle();

if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
	if ($doc_title == $this->title) {
		$this->title = JText::_('JERROR_AN_ERROR_HAS_OCCURRED');
	}
}

$sitesearch = false;
if (!JComponentHelper::isEnabled('com_finder') && !JPluginHelper::isEnabled('finder')) {
	if (JComponentHelper::isEnabled('com_search')) {
		$sitesearch = JRoute::_('index.php?option=com_search&view=search');
	}
} else {
	$sitesearch = JRoute::_('index.php?option=com_finder&view=search');
}

?>
<html lang="<?php echo $lang ?>" dir="<?php echo $this->direction ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?php echo $doc_title ?> - <?php echo $this->title ?></title>
<meta name="language" content="<?php echo $lang ?>">
<meta name="generator" content="Construc2">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/construc2/css/core/base.css" type="text/css">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/construc2/css/core/oocss.css" type="text/css">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/construc2/css/core/screen.css" type="text/css">
</head>
<body>
<div class="line error">
	<div class="mod" id="errorboxoutline">
		<div class="hd" id="errorboxheader">
		<h2 class="H3"><q><?php echo $doc_title ?></q>: <?php echo $this->title ?></h2>
		</div>
		<div class="bd" id="errorboxbody">
			<p><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT') ?></p>
			<ol>
<?php
	$reasons = array();
	switch ($this->error->getCode())
	{
		case 500:
			$reasons[] = 'JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST';
			$reasons[] = 'JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND';
			break;

		case 404:
			$reasons[] = 'JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING';
			$reasons[] = 'JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE';
			$reasons[] = 'JERROR_LAYOUT_MIS_TYPED_ADDRESS';
			$reasons[] = 'JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND';
			break;

		case 403:
			$reasons[] = 'JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE';
			$reasons[] = 'JERROR_TABLE_BIND_FAILED';
			break;

		case 401:
			$reasons[] = 'JERROR_LOGIN_DENIED';
			$reasons[] = 'JERROR_NOLOGIN_BLOCKED';
			break;
		default:
	}

	foreach ($reasons as $reason) {
		echo '<li>'. JText::_($reason) . '</li>';
	}
?>
			</ol>

<?php if ($sitesearch) { ?>
			<p><strong><?php echo JText::_('JERROR_LAYOUT_SEARCH') ?></strong></p>
			<ul>
				<li><a href="<?php echo $sitesearch ?>"><?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE') ?></a></li>
<?php } else { ?>
			<p><strong><?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES') ?></strong></p>
			<ul>
<?php } ?>
				<li><a href="<?php echo JRoute::_($this->baseurl .'/') ?>" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE') ?>"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE') ?></a></li>
			</ul>

			<p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR') ?></p>
		</div>

		<div class="ft" id="techinfo">
			<p class="firm"><?php echo $this->error->getMessage() ?></p>
		</div>
	</div>
</div>
</body>
</html>
