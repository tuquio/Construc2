<?php defined('_JEXEC') or die;
/**
 * @package    Template
 * @subpackage Overrides
 * @author     WebMechanic http://webmechanic.biz
 * @copyright  (C) 2011-2012 WebMechanic. All rights reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @var integer   $count
 * @var string    $moduleclass_sfx  recommended value 'hmenu'
 * @var string    $separator
 * @var JRegistry $params
 * @var array     $list
 */

// nothing to do if: home + don't show home + don't show first)
if ($count <= 1) 
{
	if ($count == 0) {
		$content = '';
		return;
	}

	$jmenu = JFactory::getApplication()->getMenu();
	if ($jmenu->getActive() == $jmenu->getDefault()) {
		$content = '';
		return;
	}
}

?><div class="menu breadcrumbs <?php echo $moduleclass_sfx ?>"><?php

if ($params->get('showHere', 0)) {
	echo '<strong class="bc-label here">' .JText::_('MOD_BREADCRUMBS_HERE').'</strong>';
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

	$crumb = '<span class="mi bc-label">'. $list[$i]->name .'</span>';

	if (!empty($list[$i]->link)) {
		$crumb = '<a class="mi '. $list[$i]->css .'" href="'. $list[$i]->link. '">'. $crumb .'</a>';
	}

	if ($i == 0 && $params->get('showHome'))
	{
		echo '<span class="pathway">', $crumb, '</span>';
	}
	else {
		if ($i < $count - 1 || $params->get('showLast')) {
			echo '<span class="pathway"><span class="sep">', $separator, '</span>';
			$crumb .= '</span>';
		}

		echo $crumb;
	}
}

?></div><?php
