<?php defined('_JEXEC') or die;
/**
 * A better mod_breadcrumbs override
 *
 * @package     Template
 * @subpackage  HTML
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   Copyright (C)2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// nothing to do if: home + don't show home + don't show first)
if ($count == 0) {
	$content = '';
	return;
}

?><div class="menu breadcrumbs <?php echo $moduleclass_sfx ?>"><?php
if ($count > 1 && $params->get('showHere', 1)) {
	echo '<span class="mi first showHere">' .JText::_('MOD_BREADCRUMBS_HERE').'</span>';
}

for ($i = 0; $i < $count; $i++)
{
	$css   = isset($list[$i]->alias) ? ' '.$list[$i]->alias : '';
	if ($i == 0) $css .= ' first';

	$label = '<span class="mi'. $css .'">'. $list[$i]->name .'</span>';

	if ($i < $count - 1)
	{
		if (!empty($list[$i]->link))
		{
			echo '<a class="mi pathway ', $css ,'" href="', $list[$i]->link, '">', $label, '</a>';
			if ($i < $count - 2) {
				echo '</span>';
			}
		} else {
			echo $label;
		}

		// If not the last item in the breadcrumbs add the separator
		if ($i < $count - 2) {
			echo '<span class="bc-item"><span class="sep">', $separator, '</span>';
		}
	}
	else if ($params->get('showLast', 1))
	{
		$css .= ' active last';

		if ($i == 0) {
			if ($params->get('showHome')) {
				echo '<a class="mi pathway ', $css ,'" href="', $list[$i]->link, '">', $label, '</a>';
			} else {
				echo $label;
			}
		}
	}
}

?></div><?php
