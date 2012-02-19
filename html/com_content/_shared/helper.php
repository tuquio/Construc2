<?php defined('_JEXEC') or die;
/**
 * Content Layout Helper used in blog and list views.
 *
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2012 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/* SearchHelper knows about the (enhanced) stop words list in xx_XXLocalise
 * and is misused to clean the alias for use as a class name of list items
 */
JLoader::register('SearchHelper', JPATH_ADMINISTRATOR .'/components/com_search/helpers/search.php');

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
	static public function showReadmore($item, $params, $class='line readmore')
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

	/**
	 * Attempts to create a nice alias from the $item to use in the class attribute
	 * to apply item and category based styles. The resulting names use '_' as a
	 * word separator to avoid name clashing with module names and common classes.
	 *
	 * @param  object $item     with an $alias and optional $category_alias property
	 * @param  string $item_id  also include an 'item-nn' class name using this string as a prefix
	 * @param  bool   $cat_id   also include an 'cat-nn' class name
	 */
	static public function getCssAlias($item, $item_id='', $cat_id=false)
	{
		static $locale;

		if (!isset($locale)) {
			// need this to find the default language
			$locale = JFactory::getLanguage()->get('tag');
		}

		$alias = str_replace('-', ' ', $item->alias);
		SearchHelper::santiseSearchWord($alias, $item->alias);

		// create some fancy aliases based on the category
		if (isset($item->category_alias)) {
			$alias2 = str_replace('-', ' ', $item->category_alias);
			SearchHelper::santiseSearchWord($alias2, $item->category_alias);
		}
		else {
			$alias2 = '';
		}

		if (isset($item->language))
		{
			// @todo test if xx_XXLocalise::INFLECTION is true
			// even if it proxies, singularization is fine for this
			if ($item->language == 'de-DE' || ($item->language == '*' && $locale == 'de-DE') ) {
				if ( method_exists('de_DELocalise', 'singularise') ) {
					$alias = de_DELocalise::singularise($alias);
					if ($alias2) {
						$alias2 = de_DELocalise::singularise($alias2);
					}
				}
			}
			// fall back for english
			elseif ($item->language == 'en-GB' || ($item->language == '*' && $locale == 'en-GB') ) {
				if ( method_exists('en_GBLocalise', 'singularise') ) {
					$alias = en_GBLocalise::singularise($alias);
					if ($alias2) {
						$alias2 = de_DELocalise::singularise($alias2);
					}
				}
			}
			else {
				// @todo check for other xx-XXLocalise classes
				if ( method_exists('en_GBLocalise', 'singularise') ) {
					$alias = en_GBLocalise::singularise($alias);
					if ($alias2) {
						$alias2 = de_DELocalise::singularise($alias2);
					}
				}
			}
		}

		$alias  = str_replace(' ', '_', trim($alias));
		$alias2 = str_replace(' ', '_', trim($alias2));

		return $alias . ($alias2 ? ' '.$alias2 : '');
	}

}
