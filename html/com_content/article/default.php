<?php defined('_JEXEC') or die;
/*
	ContentViewArticle properties
	item
	params
	print
	state			JObject
	document
	user			JUser
	baseurl
	pageclass_sfx
	_name _models _basePath _defaultModel _layout _layoutExt _layoutTemplate
	_path _template _output _escape _charset _errors
*/

JLoader::register('ContentLayoutHelper', JPATH_THEMES . '/construc2/html/com_content/_shared/helper.php');

$params		= $this->item->params;
$showLabels	= ($params->get('show_author') || $params->get('show_category' ) || $params->get('show_parent_category'));
$showDates	= ($params->get('show_create_date') || $params->get('show_modify_date') || $params->get('show_publish_date'));
$showMeta	= ($params->get('show_hits'));
$showStuff	= $showLabels || $showDates || $showMeta;

$canEdit	= $params->get('access-edit');
$actions	= ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon'));
$noPrint	= !(JFactory::getApplication()->input->get('print'));

$showall	= (1 == (int)JFactory::getApplication()->input->get('showall')) ? 'showall ' : '';
$showtoc	= isset($this->item->toc) ? 'has-toc ' : '';
$showact	= $actions ? 'has-actions ' : '';

/*
 this layout separates $introtext and $fulltext, however Content Plugins only
 work on the combined $text property, hence we need to reconstuct these parts.
 Page and item navigation as well as page numbering make things a bit trickier...

 Plugin 'pagenavigation' if both enabled and "activated" via Artikel Manager parameter:
	- show_item_navigation: 0|1	activated thru component/menu params
   	if 1 the Plugin will add:
   		$item->prev: string, URL (can be empty)
   		$item->next: string, URL (can be empty)
   		$item->pagination; string, markup <ul class="pagenav">..

   		$item->paginationposition: 0='above', 1='below'
   		$item->paginationrelative: 0='above', 1='below'

Plugin 'pagebreak' if both enabled and "activated" via "Shared Options" in Artikel Manager parameter:
	Page break settings
	- show_pagination: 0|1|2 ~ hide|show|auto
	Page numbers settings
	- show_pagination_results: 1

*/

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

// override introtext and fulltext
unset($this->item->text);
$this->item->introtext = trim($itext);
$this->item->fulltext  = trim($ftext);

// Article Table of Contents: nav.unit.size2of5.page-toc.rgt, h3.H4.toc-title, ol.toc-items, li.mi
ContentLayoutHelper::betterToc($this->item, $showall);

?>
	<article class="line item-page <?php echo $showall, $showtoc, $showact, ContentLayoutHelper::getCssAlias($this->item), ($this->item->state == 0 ? ' system-unpublished' : '') ?>">
	<header class="article">
<?php
if ($params->get('show_page_heading')) {
	echo ($params->get('show_title')) ? '<hgroup class="article">' : '';
	echo '<h1 class="H1 page-title">', $this->escape($this->params->get('page_heading')), '</h1>';
}
if ($params->get('show_title')) {
	echo '<h2 class="H2 title">', $this->escape($this->item->title), '</h2>';
}
if ($params->get('show_page_heading')) {
	echo ($params->get('show_title')) ? '</hgroup>' : '';
}

if ($actions && $noPrint) {
	require JPATH_THEMES . '/construc2/html/com_content/_shared/actionsmenu.php';
}

if (!$params->get('show_intro')) {
	echo $this->item->event->afterDisplayTitle;
}

/* cleanup vote plugin if exists */
if ($this->item->event->beforeDisplayContent)
{
	if (strpos($this->item->event->beforeDisplayContent, 'content_rating')) {
		$this->item->event->beforeDisplayContent = str_replace('<br />', '', $this->item->event->beforeDisplayContent);
	}
	echo '<aside class="article">', $this->item->event->beforeDisplayContent, '</aside>';
}
?>
	</header>
<?php
if (isset($this->item->toc)) {
	echo $this->item->toc;
}

if ($params->get('show_intro') && $this->item->introtext) { ?>
<div id="introtext" class="introtext">
<?php echo $this->item->introtext ?>
</div>
<?php
}

if (!empty($this->item->fulltext)) { ?>
<div id="fulltext" class="fulltext">
<?php echo $this->item->fulltext; ?>
</div>
<?php
}

if (isset($this->pagination) ) { ?>
<nav class="pagination"><ul class="pagenav">
<?php if ($this->item->prev): ?><li class="mi prev"><a class="mi" href="<?= $this->item->prev ?>#content"><span class="mi"><?= JText::_('Previous Article')?></span></a></li><?php endif; ?>
<?php if ($this->item->next): ?><li class="mi next"><a class="mi" rel="prefetch" href="<?= $this->item->next ?>#content"><span class="mi"><?= JText::_('Next Article')?></span></a></li><?php endif; ?>
</ul></nav>
<?php
}

if ($showStuff) {
	require JPATH_THEMES . '/construc2/html/com_content/_shared/articledetails.php';
}

echo $this->item->event->afterDisplayContent;

?>
	</article>
