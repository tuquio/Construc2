<?php defined('_JEXEC') or die;
/**
 * Print, Mail, and Edit icons for com_content.
 *
 * Item Params of interest:
 * - show_icons: 0|1
 * - show_print_icon: 0|1
 * - show_email_icon: 0|1
 *
 * @package     Template
 * @subpackage  Overrides
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011-2012 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @var ContentViewArticle $this
 * @var JRegistry $params
 */
JHtml::addIncludePath(JPATH_COMPONENT_SITE .'/helpers');

$ielt = array(
			'p'=>array('<a ', '<img', '</a>'),
			'r'=>array('<a tabindex="-1" ', '<span class="mi"><img ', '</span></a>')
		);
$iwhat = $params->get('show_icons') ? 'icon' : 'text';
$dir   = $this->document->direction == 'ltr' ? 'rgt' : 'lft';

?><ul class="menu hmenu actionsmenu <?php echo $dir, ' ', $iwhat,'s' ?>" title="<?php echo JText::_('Article Actions') ?>">
	<?php if ($params->get('show_print_icon')) { ?>
	<li class="print <?php echo $iwhat ?> mi"><?php echo str_replace($ielt['p'], $ielt['r'], ContentLayoutHelper::widget('icon.print_popup', $this->item, $params)) ?></li>
<?php }

	if ($params->get('show_email_icon')) { ?>
	<li class="email <?php echo $iwhat ?> mi"><?php echo str_replace($ielt['p'], $ielt['r'], ContentLayoutHelper::widget('icon.email', $this->item, $params)) ?></li>
<?php }

	if ($canEdit) { ?>
	<li class="edit <?php echo $iwhat ?> mi"><?php echo str_replace($ielt['p'], $ielt['r'], ContentLayoutHelper::widget('icon.edit', $this->item, $params)) ?></li>
<?php } ?>
</ul><?php

// cleanup after ourself
unset($ielt);
