<?php
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
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT_SITE .'/helpers');

$params		= $this->item->params;
$showStuff	= ($params->get('show_author') || $params->get('show_category' ) || $params->get('show_parent_category')
			|| $params->get('show_create_date') || $params->get('show_modify_date') || $params->get('show_publish_date')
			|| $params->get('show_hits'));
$canEdit	= $params->get('access-edit');
$actions	= ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon'));
$noPrint	= !(JFactory::getApplication()->input->get('print'));

/* this layout separates $introtext and $fulltext but content plugins only work
   for the combined $text property, hence we need to reconstuct these parts. */
if ($this->item->fulltext)
{
	$fpos  = strpos($this->item->text, $this->item->fulltext);
	$itext = substr($this->item->text, 0, $fpos);
	$ftext = substr($this->item->text, $fpos);

} else {
	$itext = $this->item->text;
	$ftext = '';
}

$p1 = strpos($ftext, '<ul class="pagenav">');
if ($p1 > 0) {
	$ftext = substr($ftext, 0, $p1);
}
// override introtext and fulltext
unset($this->item->text);
$this->item->introtext = $itext;
$this->item->fulltext  = $ftext;

?>
	<article class="line item-page <?php echo $this->item->parent_alias, ' ', $this->item->category_alias, ' cid-', $this->item->catid, ($this->item->state == 0 ? ' system-unpublished' : '') ?>">
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
	echo '<aside>', $this->item->event->beforeDisplayContent, '</aside>';
}
?>
	</header>
<?php
// splitting introtext and fulltext also allows us to build our own pagenavigation
if (!$params->get('show_intro')) {
	if (isset ($this->item->toc)) {
		echo $this->item->toc;
	}
} else {
	echo '<div id="introtext" class="introtext">';
	if (isset ($this->item->toc)) {
		echo $this->item->toc;
	}

	echo $this->item->introtext;

	echo '</div>';
}

if (!empty($this->item->fulltext)) { ?>
<div id="fulltext" class="fulltext">
<?php echo $this->item->fulltext; ?>
</div>
<?php
}

if ( isset($this->item->prev) && ($this->item->prev || $this->item->next) ) { ?>
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
