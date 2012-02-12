<?php
/**
 * Content Layout Helper used in blog and list views.
 *
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2012 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

class ContentLayoutHelper
{
	/**
	 * Renders the "ream more" link and must be wrapped in a call such as
	 * <code>
	 * if ($params->get('show_readmore') && $this->item->readmore) {
	 *     echo ContentLayoutHelper::showReadmore($this->item, $params);
	 * }
	 * </code>
	 *
	 * @param  object    $item   Article item
	 * @param  JRegistry $params Layout parameters
	 * @return string
	 */
	static function showReadmore($item, $params, $class='line readmore')
	{
		$more = JText::_('COM_CONTENT_READ_MORE');
		if (JFactory::getLanguage()->hasKey('READ_MORE_1')) {
			$r = rand(1, 5);
			if (JFactory::getLanguage()->hasKey('READ_MORE_'.$r)) {
				$more = JText::_('READ_MORE_'.$r);
			}
		}

		if ($params->get('access-view')) {
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
		}
		else {
			$itemId  = JSite::getMenu()->getActive()->id;
			$URL     = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug));
			$link    = new JURI(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId));
			$link->setVar('return', base64_encode($URL));
		}

		$html = '<p class="'. $class .'"><a href="'. $link .'#content">';

		if (!$params->get('access-view')) {
			$html .= JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
		}
		elseif ($readmore = $item->alternative_readmore) {
			$html .= $readmore;
			if ($params->get('show_readmore_title', 0) != 0) {
				$html .= JHtml::_('string.truncate', ($item->title), $params->get('readmore_limit'));
			}
		}
		elseif ($params->get('show_readmore_title', 0) == 0) {
			$html .= JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
		}
		else {
			$html .=  $more;
			$html .= JHtml::_('string.truncate', ($item->title), $params->get('readmore_limit'));
		}

		$html .= '</a></p>';

		return $html;
	}

}
