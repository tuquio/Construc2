<?php
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

$menu		= JSite::getMenu();
$active		= $menu->getActive();
$itemId		= $active ? $active->id : $menu->getDefault()->id;
$link 		= JRoute::_('index.php?option=com_users&view=login&Itemid='.$itemId);

$n 			= count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

if (empty($this->items)) {
	if ($this->params->get('show_no_articles',1)) {
	?><p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p><?php
	}

	return;
}
?><form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
<?php if ($this->params->get('filter_field') != 'hide') { ?>
	<fieldset class="filters">
		<legend class="element-invisible"><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('COM_CONTENT_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?></label>
			<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC') ?>" />
		</div>
<?php }

if ($this->params->get('show_pagination_limit')) { ?>
		<div class="display-limit"><?php
			echo JText::_('JGLOBAL_DISPLAY_NUM'), '&#160;', $this->pagination->getLimitBox();
		?></div><?php
}

if ($this->params->get('filter_field') != 'hide') : ?></fieldset><?php endif; ?>

<table class="category">
<?php if ($this->params->get('show_headings')) { ?>
<thead>
<tr>
	<th class="list-title" id="tableOrdering"><?php  echo JHtml::_('grid.sort', 'COM_CONTENT_HEADING_TITLE', 'a.title', $listDirn, $listOrder) ; ?></th>
	<?php if ($date = $this->params->get('list_show_date')) { ?>
	<th class="list-date" id="tableOrdering2"><?php echo JHtml::_('grid.sort', 'COM_CONTENT_'.$date.'_DATE', 'a.created', $listDirn, $listOrder); ?>	</th>
	<?php } ?>

	<?php if ($this->params->get('list_show_author',1)) { ?>
	<th class="list-author" id="tableOrdering3"><?php echo JHtml::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>	</th>
	<?php } ?>

	<?php if ($this->params->get('list_show_hits',1)) { ?>
	<th class="list-hits" id="tableOrdering4"><?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>	</th>
	<?php } ?>
</tr>
</thead><?php
}
?>
<tbody class="category-list">
<?php
foreach ($this->items as $i => &$article) : ?>
<tr class="row-<?php echo $i % 2; ?>">
<?php
	if (in_array($article->access, $this->user->getAuthorisedViewLevels())) { ?>
	<td class="list-title"><a href="<?php
		echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid));
		?>"><?php
		echo $this->escape($article->title);
		?></a></td>

<?php 	if ($this->params->get('list_show_date'))
		{ ?>
		<td class="list-date"><?php
			echo JHtml::_('date', $article->displayDate, 'DATE_FORMAT_LC1') ?>
		</td>
<?php 	}

		if ($this->params->get('list_show_author',1) && !empty($article->author ))
		{ ?>
		<td class="list-author"><?php
			$author = $article->author;
			$author = ($article->created_by_alias ? $article->created_by_alias : $author);

			if (!empty($article->contactid ) &&  $this->params->get('link_author') == true) {
				echo JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$article->contactid), $author);
			} else {
				echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author);
			} ?>
		</td>
<?php 	}

		if ($this->params->get('list_show_hits',1))
		{ ?>
		<td class="list-hits"><?php echo $article->hits; ?></td>
<?php 	}

	}
	else
	{
?>
	<td><?php
		$returnURL	= JRoute::_(ContentHelperRoute::getArticleRoute($article->slug));
		$fullURL	= new JURI($link);
		$fullURL->setVar('return', base64_encode($returnURL));

		echo $this->escape($article->title).' : '; ?>
	<a href="<?php echo $fullURL; ?>" class="register"><?php echo JText::_( 'COM_CONTENT_REGISTER_TO_READ_MORE' ); ?></a>
	</td><?php

	} ?>
</tr>
<?php
endforeach; ?>
	</tbody>
</table>
<?php

	if (($this->params->def('show_pagination', 2) == 1
	|| ($this->params->get('show_pagination') == 2))
		&& ($this->pagination->get('pages.total') > 1))
	{ ?>
	<div class="pagination"><?php
		if ($this->params->def('show_pagination_results', 1))
		{ ?>
	<p class="counter"><?php echo $this->pagination->getPagesCounter(); ?></p><?php
		}

		echo $this->pagination->getPagesLinks(); ?>
	</div><?php
	}

?>	<input type="hidden" name="filter_order" value="">
	<input type="hidden" name="filter_order_Dir" value="">
	<input type="hidden" name="limitstart" value="">
</form>
<?php

	JHtml::_('behavior.tooltip');
	JHtml::core();

