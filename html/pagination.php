<?php  defined('_JEXEC') or die;
/**
 * Template specific chrome to pagination rendering.
 *
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Input variable $list is an array with offsets and the following keys:
 *	 $list[prefix]		: string
 *	 $list[limit]		: int
 *	 $list[limitstart]	: int
 *	 $list[total]		: int
 *	 $list[limitfield]	: HTML select list
 *	 $list[pagescounter]: string "Page N of M"
 *	 $list[pageslinks]	: HTML pagination menu
 *
 * @param  array  $list
 * @return string
 * @todo IMPLEMENT limitfield form
 */
function x_pagination_list_footer($list)
{
	return ''; // '<form>'. $list['limitfield'] . '</form>';
}

/**
 * Input variable $list is an array with offsets and the following keys:
 *	 $list[all]
 *		 [data]		: string
 *		 [active]	: boolean
 *	 $list[start]
 *		 [data]		: string
 *		 [active]	: boolean
 *	 $list[previous]
 *		 [data]		: string
 *		 [active]	: boolean
 *	 $list[next]
 *		 [data]		: string
 *		 [active]	: boolean
 *	 $list[end]
 *		 [data]		: string
 *		 [active]	: boolean
 *	 $list[pages]
 *		 [{PAGE}][data]		: string
 *		 [{PAGE}][active]	: boolean
 *
 * @param  array  $list
 * @return string
 */
function pagination_list_render($list)
{
	// Initialize variables
	$html = '<menu class="pagination" title="'. JText::_('More Pages') .'">';

	if ($list['start']['active']) {
		$html .= '<li class="mi first txt" title="'. JText::_('First page') .'">'. $list['start']['data'] .'</li>';
	}

	if ($list['previous']['active']) {
		$html .= '<li class="mi prev txt">' . str_replace('href', 'id="prev-page" href', $list['previous']['data']) .'</li>';
	}

	foreach ($list['pages'] as $page)
	{
		if ($page['data']['active']) {
			$html .= '<li class="mi num">';
		} else {
			$html .= '<li class="mi num active">';
		}
		$html .= $page['data'];
		$html .= '</li>';
	}

	if ($list['next']['active']) {
		$html .= '<li class="mi next txt">'. str_replace('href', 'rel="prefetch" id="next-page" href', $list['next']['data']) .'</li>';
	}

	if ($list['end']['active']) {
		$html .= '<li class="mi last txt" title="'. JText::_('Last page') .'">'. $list['end']['data'] .'</li>';
	}

	$html .= '</menu>';

	return $html;
}

/**
 * Input variable $item is an object with the following properties:
 *	 $item->base	: integer
 *	 $item->link	: string
 *	 $item->text	: string
 *
 * @param  object  $item
 * @return string
 * @see pagination_item_inactive()
 */
function pagination_item_active($item)
{
	$title = ($item->text > 0) ? ' title="'.JText::sprintf('Page N', $item->text) .'"' : '';
	return '<a class="mi" href="'. $item->link .'#content"'. $title .'><span class="mi">'. $item->text .'</span></a>';
}

/**
 * Input variable $item is an object with the following properties:
 *	 $item->base	: integer
 *	 $item->link	: string
 *	 $item->text	: string
 *
 * @param  object  $item
 * @return string
 * @see pagination_item_active()
 */
function pagination_item_inactive($item)
{
	$title = ($item->text > 0) ? ' title="'.JText::sprintf('Page N', $item->text) .'"' : '';
	return '<span class="mi active"'. $title .'>'. $item->text .'</span>';
}
