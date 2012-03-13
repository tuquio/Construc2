<?php defined('_JEXEC') or die;
/**
 * Print, Mail, and Edit icons for com_content.
 *
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
JHtml::addIncludePath(JPATH_COMPONENT_SITE .'/helpers');

$isep    = JText::_('JGLOBAL_ICON_SEP');
$actions = array(
				array('<a ', '<img', '</a>'),
				array('<a tabindex="-1" ', '<span class="mi"><img ', '</span></a>')
			);
?>
	<ul class="menu hmenu actionsmenu" title="<?php echo JText::_('Article Actions') ?>">
	<?php if ($params->get('show_print_icon')) { ?>
	<li class="mi print-icon"><?php echo str_replace($actions[0], $actions[1], JHtml::_('icon.print_popup', $this->item, $params)) ?></li>
<?php }

	if ($params->get('show_email_icon')) { ?>
	<li class="mi email-icon"><?php echo str_replace($actions[0], $actions[1], JHtml::_('icon.email', $this->item, $params)) ?></li>
<?php }

	if ($canEdit) { ?>
	<li class="mi edit-icon"><?php echo str_replace($actions[0], $actions[1], JHtml::_('icon.edit', $this->item, $params)) ?></li>
<?php } ?>
	</ul>
