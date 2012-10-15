<?php defined('_JEXEC') or die;
/**
 * Better Menu Helper.
 *
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
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
		if (is_callable('ConstructTemplateHelper::getCssAlias')) {
			return ConstructTemplateHelper::getCssAlias($item);
		}
		$C = array('');
		// menu item?
		//	$C[] = $item->type;
		if (isset($item->query) && $parent) {
			if (isset($item->query['option'])) {
				$C[key($C)] .= substr($item->query['option'], strpos($item->query['option'], '_')+1);
			}
			if (isset($item->query['view'])) {
				$C[key($C)] .= '-'.$item->query['view'];
			}
		}
		if (isset($item->query['layout'])) {
			$C[] = $item->query['layout'];
		}

		$A = array();
		if ($parent) {
			if (isset($item->parent_alias)) {
				$A[] = $item->parent_alias;
			}
			elseif (isset($item->parent_route)) {
				$A[] = substr($item->parent_route, 0, strpos($item->parent_route, '/'));
			}
		}
		if (isset($item->category_alias)) {
			$A[] = $item->category_alias;
		}
		if (isset($item->alias)) {
			$A[] = $item->alias;
		}
		if (isset($item->images) && preg_match('#\.(jpe?g|png|gif)#',$item->images)) {
			$C[] = 'images';
		}
		if (isset($item->state) && $item->state == 0) {
			$A[] = 'system-unpublished';
		}

		$alias = '';
		foreach ((array)$A as $k => $ali)
		{
			$ali = trim($ali, '-');

			// single word
			if (strpos($ali, '-') === false) continue;
			// short enough
			if (strlen($ali) <= 20) continue;

			// split and sanitize
			$alias = JStringNormalise::toSpaceSeparated($ali);
			$words = explode(' ', $alias);
			if (count($words) > 1) {
				$ali = array_diff($words, JFactory::getLanguage()->getIgnoredSearchWords());
				if (isset($item->language)) {
					$alias = self::_inflectAlias($ali, $item->language);
				} else {
					$alias = self::_inflectAlias($ali);
				}
			}
			$A[$k] = is_array($alias) ? implode('-', $alias) : $alias;
		}

		if ($item instanceof JCategoryNode) {
			list($tmp, $C[]) = explode(':', $item->slug);
			$C[] = 'cid-' . $item->id;
		} else {
			if (isset($item->catid)) {
				$C[] = 'cid-'.$item->catid;
			}
			$C[] = 'item-' . $item->id;
		}

		$words = array_unique( array_merge($C, $A) );
		$alias = implode(' ', $words);

		return trim($alias);
	}

	// @todo refactor to use JStringXXX if that comes available
	protected static function _inflectAlias(&$aliases, $language = null)
	{
		static $locale, $inflect = true;

		if (!$inflect) {
			return $aliases;
		}

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
			else {
				$inflect = false;
			}
		}

		return implode('-', $aliases);
	}

}
