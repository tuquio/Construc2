<?php
/**
 * Print, Mail, and Edit icons for com_content.
 *
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
$isep = JText::_('JGLOBAL_ICON_SEP');
?>
	<ul class="menu hmenu actionsmenu">
	<?php if ($params->get('show_print_icon')) { ?>
	<li class="mi print-icon"><?php
		echo str_replace(array('<img', '</a>'), array('<span class="mi"><img', '</span></a>'),
				JHtml::_('icon.print_popup', $this->item, $params))
	?></li>
<?php }

	if ($params->get('show_email_icon')) { ?>
	<li class="mi email-icon"><?php
		echo str_replace(array('<img', '</a>'), array('<span class="mi"><img', '</span></a>'),
				JHtml::_('icon.email', $this->item, $params))
	?></li>
<?php }

	if ($canEdit) { ?>
	<li class="mi edit-icon"><?php
		echo str_replace(array('<img', '</a>'), array('<span class="mi"><img', '</span></a>'),
				JHtml::_('icon.edit', $this->item, $params))
	?></li>
<?php } ?>
	</ul>
