<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
/*
This layout separates $introtext and $fulltext, however Content Plugins only
work on the combined $text property, hence we need to reconstuct these parts.
Page and item navigation as well as page numbering make things a bit trickier...

Plugin 'pagenavigation' if activated via Artikel Manager parameter:
	- show_item_navigation: 0|1	activated thru component/menu params
   	if 1 the Plugin will add:
   		$item->prev: string, URL (can be empty)
   		$item->next: string, URL (can be empty)
   		$item->pagination; string, original markup <ul class="pagenav">...

   		$item->paginationposition: 0='above', 1='below'
   		$item->paginationrelative: 0='above', 1='below'

Plugin 'pagebreak' if activated via "Shared Options" in Artikel Manager parameter:
	Page break settings
	- show_pagination: 0|1|2 ~ hide|show|auto
	Page numbers settings
	- show_pagination_results: 1

$images stdClass(
		image_intro:	'images/sampledata/fruitshop/apple.jpg',
		float_intro:	'right',	left|none|''
		image_intro_alt:'Apples',
		image_intro_caption:'An image of apples',
		image_fulltext:'',
		float_fulltext:'',
		image_fulltext_alt:'',
		image_fulltext_caption:''
		)
*/

JLoader::register('ContentLayoutHelper', JPATH_THEMES . '/construc2/html/com_content/_shared/helper.php');

// Article Table of Contents, Page Navigation
ContentLayoutHelper::betterToc($this->item);

$params		= $this->item->params;
$showLabels	= ($params->get('show_author') || $params->get('show_category' ) || $params->get('show_parent_category'));
$showDates	= ($params->get('show_create_date') || $params->get('show_modify_date') || $params->get('show_publish_date'));
$showMeta	= ($params->get('show_hits'));
$showStuff	= $showLabels || $showDates || $showMeta;

$canEdit	= $params->get('access-edit');
$actions	= ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon'));
$noPrint	= !(JFactory::getApplication()->input->get('print'));

$images		= json_decode($this->item->images);
$urls		= json_decode($this->item->urls);

$showall	= (1 == (int)JFactory::getApplication()->input->get('showall'));

// property and state classes
$more = array('f'=>array(),'p'=>array(),'s'=>array());
if  ($this->item->featured) 	{$more['f'][] = 'featured';}
if  ($showall)					{$more['f'][] = 'showall';}
if  (isset($this->item->toc))	{$more['p'][] = 'has-toc';}
if  ($actions)					{$more['p'][] = 'has-actions';}
if  (!$this->item->rating)  	{$more['s'][] = 'unrated';}

	// #TODO move to helper
	$ipos = $fpos = $bpos = 0;
	if ($this->item->fulltext)
	{
		if ($params->get('show_pagination_results')) {
			$ipos  = strpos($this->item->text, $this->item->introtext);
			$ptext = substr($this->item->text, 0, $ipos);
		}
		$bpos  = strpos($this->item->fulltext, '<hr class="system-pagebreak"');
		$bpos  = $bpos ? $bpos : strlen($this->item->fulltext);
		$fpos  = strpos($this->item->text, substr($this->item->fulltext, 0, $bpos));
		$itext = substr($this->item->text, 0, $ipos) . substr($this->item->text, $ipos, $fpos - $ipos);
		$ftext = substr($this->item->text, $fpos);
	} else {
		$itext = '';
		$ftext = $this->item->text;
	}
	// have a better pagenav?
	if (isset($this->item->pagenav) ) {
		$ftext = substr($ftext, 0, strpos($ftext, '<div class="pagination"'));
	}
	// override introtext and fulltext
	unset($this->item->text);
	$this->item->introtext = trim($itext);
	$this->item->fulltext  = trim($ftext);

?>
	<article id="the-article" class="line item-page <?php echo implode(' ', $more['p']) ,' ', ContentLayoutHelper::getCssAlias($this->item, true), ($this->item->state == 0 ? ' system-unpublished' : '') ?>">
	<header id="the-header" class="article">
<?php
if ($params->get('show_page_heading')) {
	if ($params->get('show_title')) { echo '<hgroup class="article">'; }
	echo '<h1 class="H1 page-title">', $this->escape($this->params->get('page_heading')), '</h1>';
}
if ($params->get('show_title')) {
	echo '<h2 class="H2 title">', $this->escape($this->item->title), '</h2>';
}
if ($params->get('show_page_heading')) {
	if ($params->get('show_title')) { echo '</hgroup>'; }
}
if (!$params->get('show_intro')) {
	echo $this->item->event->afterDisplayTitle;
}
?>
	</header>

<?php
/* cleanup vote plugin if exists */
if (!ContentLayoutHelper::isEmpty($this->item->event->beforeDisplayContent))
{
	if (strpos($this->item->event->beforeDisplayContent, 'content_rating')) {
		$this->item->event->beforeDisplayContent = str_replace('<br />', '', $this->item->event->beforeDisplayContent);
	}
	echo '<aside class="article ', implode(' ', $more['s']) ,'">', $this->item->event->beforeDisplayContent, '</aside>';
}

if (isset($this->item->toc)) {
	echo $this->item->toc;
}

if ($actions && $noPrint) {
	require JPATH_THEMES . '/construc2/html/com_content/_shared/actionsmenu.php';
}

if ($params->get('show_intro') && $this->item->introtext) { ?>

	<div id="introtext" class="introtext"><?php echo $this->item->introtext ?></div>
<?php
}

if (!empty($this->item->fulltext)) { ?>

	<div id="fulltext" class="fulltext"><?php echo $this->item->fulltext ?></div>
<?php
}

// better multipage navigation
if (isset($this->item->pagenav) ) { ?>

	<footer id="the-footer">
	<h4 class="H4"><?php JText::printf('Article spans several pages', $this->escape($this->item->title)) ?></h4>
	<?php echo $this->item->pagenav ?>
	</footer>

<?php
}

if ($showStuff) {
	require JPATH_THEMES . '/construc2/html/com_content/_shared/articledetails.php';
}

echo $this->item->event->afterDisplayContent;
?>
	</article>

<?php if (isset($this->item->pagination) ) { ?>
<footer id="component-footer" class="component article">
	<nav class="pagination"><ul class="pagenav">
	<?php if ($this->item->prev): ?><li class="mi prev"><a class="mi" href="<?= $this->item->prev ?>#content"><span class="mi"><?= JText::_('Previous Article')?></span></a></li><?php endif; ?>
	<?php if ($this->item->next): ?><li class="mi next"><a class="mi" rel="prefetch" href="<?= $this->item->next ?>#content"><span class="mi"><?= JText::_('Next Article')?></span></a></li><?php endif; ?>
	</ul></nav>
</footer>
<?php }
