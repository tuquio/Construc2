<?php
/**
 * Better Menu Helper.
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

class BetterMenuHelper
{
	/**
	 * Attempts to create a nice alias from the $item to use in the class attribute
	 * to apply item and category based styles. The resulting names use '_' as a
	 * word separator to avoid name clashing with module names and common classes.
	 *
	 * @param  object  $item with an $alias and optional $category_alias property
	 */
	static public function getCssAlias($item)
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
