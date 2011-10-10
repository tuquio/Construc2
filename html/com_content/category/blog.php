<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT_SITE '/helpers');
$cparams = JComponentHelper::getParams('com_media');

?>
<section class="blog">
	<header><?php
if ($this->params->get('show_page_heading')) {
	echo '<h1 class="page_heading">', $this->escape($this->params->get('page_heading')), '</h1>';
}

if ($this->params->get('show_category_title')) {
	echo '<h2 class="subheading category">', $this->category->title, '</h2>';
}
?>	</header>
<?php
if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="line category-desc">
	<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
		<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
	<?php endif; ?>
	<?php if ($this->params->get('show_description') && $this->category->description) : ?>
		<?php echo JHtml::_('content.prepare', $this->category->description); ?>
	<?php endif; ?>
	</div>
<?php
endif;

$leadingcount=0 ;

if (!empty($this->lead_items)) : ?>
<div class="line items-leading">
<?php foreach ($this->lead_items as &$item) : ?>
	<article class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? 'system-unpublished' : null; ?>">
<?php
	$this->item = &$item;
	echo $this->loadTemplate('item');
?>
	</article>
<?php $leadingcount++; ?>
<?php endforeach; ?>
</div>
<?php
endif;

$introcount = (count($this->intro_items));
$counter=0;

if (!empty($this->intro_items)) :

	foreach ($this->intro_items as $key => &$item) :
		$key= ($key-$leadingcount)+1;
		$rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
		$row = $counter / $this->columns ;

		if ($rowcount==1) : ?>
	<div class="line items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row ; ?>">
	<?php endif; ?>
	<article class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
		<?php
			$this->item = &$item;
			echo $this->loadTemplate('item');
		?>
	</article>
	<?php $counter++; ?>
	<?php if (($rowcount == $this->columns) or ($counter ==$introcount)): ?>
	</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($this->link_items)) : ?>
	<?php echo $this->loadTemplate('links'); ?>
<?php endif; ?>

	<?php if (is_array($this->children[$this->category->id]) && count($this->children[$this->category->id]) > 0 && $this->params->get('maxLevel') !=0) : ?>
		<div class="line cat-children">
		<h3><?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?></h3>
			<?php echo $this->loadTemplate('children'); ?>
		</div>
	<?php endif; ?>

<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="line pagination">
<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
	<p class="counter"><?php echo $this->pagination->getPagesCounter(); ?></p>
<?php endif; ?>
<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php  endif; ?>

</section>
