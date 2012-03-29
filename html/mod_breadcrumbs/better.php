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

if ($params->get('showHere', 0)) {
	echo '<strong class="bc-item first here">' .JText::_('MOD_BREADCRUMBS_HERE').'</strong>';
}

for ($i = 0; $i < $count; $i += 1)
{
	if ($i > 0 && !empty($list[$i]->link)) {
		$css = explode('/', $list[$i]->link);
		$css = array_pop($css);
		$list[$i]->css = preg_replace('/^\d+\-/', '', $css);
	}
	else {
		$list[$i]->css = ($i == 0) ? 'home' : '';
	}

	$crumb = '<span class="mi">'. $list[$i]->name .'</span>';

	if (!empty($list[$i]->link)) {
		$crumb = '<a class="mi '. $list[$i]->css .'" href="'. $list[$i]->link. '">'. $crumb .'</a>';
	}

	if ($i == 0 && $params->get('showHome'))
	{
		echo $crumb;
	}
	else {
		if ($i < $count - 1 || $params->get('showLast')) {
			echo '<span class="pathway"><span class="sep">'. $separator .'</span>';
			$crumb .= '</span>';
		}

		echo $crumb;
	}
}

?></div><?php
