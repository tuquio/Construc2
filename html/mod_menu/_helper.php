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
	static public function renderType($module, $params, $menuitem = null)
	{
		$menu = new stdClass;
FB::info($module, 'Modul');
FB::info($params, 'Params');
FB::log($menuitem, 'Menü');

		return $menu;
	}

	/**
	 * Attempts to create a nice alias from the $item to use in the class
	 * attribute to apply item and category based styles.
	 * If $item is a menu[ish] item, also includes type, view and layout.
	 * If $item is an article, parent and category aliases (if available)
	 * will be included.
	 * Category and item IDs appear as cat-N and item-N respectively.
	 *
	 * @param  object $item  with an $alias and maybe more usefull things
	 * @return string The alias
	 */
	static public function getCssAlias($item)
	{
		$d = array();
		// menu item?
		if (isset($item->type)) {
			$d['t'] = $item->type;
			if (isset($item->query['option'])) {
				$d['o'] = str_replace('_', '-', $item->query['option']);
			}
			if (isset($item->query['view'])) {
				$d['v'] = $item->query['view'];
			}
			if (isset($item->query['layout'])) {
				$d['l'] = $item->query['layout'];
			}
		}

		$d['A'] = array();
		if (isset($item->parent_alias)) {
			$d['A']['pa'] = $item->parent_alias;
		}
		if (isset($item->category_alias)) {
			$d['A']['ca'] = $item->category_alias;
		}
		if (isset($item->alias)) {
			$d['A']['ia'] = $item->alias;
		}

		if ($item instanceof JCategoryNode) {
			$d['id'] = 'cid-' . $item->id;
		} else {
			if (isset($item->catid)) {
				$d['cid'] = 'cid-'.$item->catid;
			}
			$d['id'] = 'item-' . $item->id;
		}

		$alias = '';
		foreach ($d['A'] as $k => $ali)
		{
			// single word
			if (strpos($ali, '-') === false) continue;
			// short enough
			if (strlen($ali) <= 20) continue;
			// split and sanitize
			$alias = JStringNormalise::toSpaceSeparated($ali);

			$words = explode(' ', $alias);
			if (count($words) > 1) {
				$ignore = JFactory::getLanguage()->getIgnoredSearchWords();
				$ali = array_diff($words, $ignore);
				if (isset($item->language)) {
					$alias = self::_inflectAlias($ali, $item->language);
				} else {
					$alias = self::_inflectAlias($ali);
				}
			}
		}
		unset($d['A']);

		$alias .= ' ' . implode(' ', $d);

		return trim($alias);
	}

	// @todo refactor to use JStringXXX if that comes available
	static protected function _inflectAlias(&$aliases, $language = null)
	{
		static $locale;

		if (!isset($locale)) {
			// need this to find the default language
			$locale = JFactory::getLanguage()->get('tag');
		}

		if (empty($language) || $language = '*') {
			$language = $locale;
		}

		settype($aliases, 'array');

		// even if it proxies, singularization is fine for this
		if (($language == 'de-DE' || $locale == 'de-DE') && method_exists('de_DELocalise', 'singularise')) {
			foreach ($aliases as $i => $alias) {
				$aliases[$i] = de_DELocalise::inflect($alias, false);
			}
		}
		// fall back for english
		elseif (($language == 'en-GB' || $locale == 'en-GB') && method_exists('en_GBLocalise', 'singularise')) {
			foreach ($aliases as $i => $alias) {
				$aliases[$i] = en_GBLocalise::inflect($alias, false);
			}
		}
		else {
			// @todo check for other xx-XXLocalise classes
			if ( method_exists('en_GBLocalise', 'singularise') ) {
				foreach ($aliases as $i => $alias) {
					$aliases[$i] = en_GBLocalise::inflect($alias, false);
				}
			}
		}

		return implode('-', $aliases);
	}

}
