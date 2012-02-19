<?php
defined('_JEXEC') or die;

$params		= $this->item->params;
$showLabels	= ($params->get('show_author') || $params->get('show_category' ) || $params->get('show_parent_category'));
$showDates	= ($params->get('show_create_date') || $params->get('show_modify_date') || $params->get('show_publish_date'));
$showMeta	= ($params->get('show_hits'));
$showStuff	= $showLabels || $showDates || $showMeta;

$canEdit	= $params->get('access-edit');
$actions	= ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon'));
$noPrint	= !(JFactory::getApplication()->input->get('print'));

?>
	<article class="article <?php echo $this->item->parent_alias, ' ', $this->item->category_alias, ' cid-', $this->item->catid, ($this->item->state == 0 ? ' system-unpublished' : '') ?>">
	<header class="article">
<?php
if ($params->get('show_title'))
{ ?>
	<h2 class="H2 title"><?php
	if ($params->get('link_titles') && $params->get('access-view')) {
	?><a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>#content"><?php
		echo $this->escape($this->item->title);
	?></a><?php
	} else {
		echo $this->escape($this->item->title);
	} ?></h2>
<?php
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

	<div class="introtext">
<?php echo $this->item->introtext ?>
	</div>

<?php
if ($params->get('show_readmore') && $this->item->readmore) {
	echo ContentLayoutHelper::showReadmore($this->item, $params);
}

if ($showStuff) {
	require JPATH_THEMES . '/construc2/html/com_content/_shared/articledetails.php';
}

	echo $this->item->event->afterDisplayContent;

?>
	</article>

