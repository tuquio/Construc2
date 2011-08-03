<?php
/**
 * mod_menu override
 * @package		Template
 * @subpackage	HTML
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C)2011 WebMechanic. All rights reserved.
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/* SearchHelper knows about the (enhanced) stop words list in xx_XXLocalise
 * and is misused to clean the alias for use as a class name of list items
 */
JLoader::register('SearchHelper', JPATH_ADMINISTRATOR .'/components/com_search/helpers/search.php');

// need this to find the default language
$locale = JFactory::getLanguage()->get('tag');

// Note. It is important to remove spaces between elements.
?>

<ul class="menu <?php echo $class_sfx;?>"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>
<?php
foreach ($list as $i => &$item) :

	$alias = str_replace('-', ' ', $item->alias);
	SearchHelper::santiseSearchWord($alias, $item->alias);
	$alias = str_replace(' ', '-', $alias);

	// even if it proxies, singularization is fine for this
	if ($item->language == 'de-DE' || $locale == 'de-DE') {
		$alias = de_DELocalise::singularize($alias);
	}
	// fall back for english
	elseif ($item->language == 'en-GB' || $locale == 'en-GB') {
		// @todo test if JLanguage was patched to contain getSingular()
		// @todo test if Localise::INFLECTION is true
		$alias = en_GBLocalise::singularize($alias);
	}
	// fall do some smart check for other xx-XXLocalise class
	else {
		// call static clas via variable. 5.3+
	}

	$class = array($alias);

	if ($item->id == $active_id) {
		$class[] = 'current ';
	}

	if (	$item->type == 'alias' &&
			in_array($item->params->get('aliasoptions'),$path)
		||	in_array($item->id, $path)) {
	  $class[] = 'active ';
	}
	if ($item->deeper) {
		$class[] = 'deeper ';
	}

	if ($item->parent) {
		$class[] = 'parent ';
	}

	if (!empty($class)) {
		$class = ' class="'.implode(' ', $class) .'"';
	}

	echo '<li id="item-'.$item->id.'"'.$class.'>';

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
			require JModuleHelper::getLayoutPath('mod_menu', 'default_'.$item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper) {
		echo '<ul>';
	}
	// The next item is shallower.
	else if ($item->shallower) {
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}
endforeach;
?></ul>
