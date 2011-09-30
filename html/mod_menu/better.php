<?php
/**
 * A better mod_menu override
 *
 * Magic class_sfx:
 *  - '_ordered' or '_ol' uses an ordered list instead of the standard
 *    unordered list for rendering.
 * Magix moduleclass_sfx:
 *  - '_chapters becomes 'moduletable chapters' to leverage the provided
 *    book chapter numbering found in the example better_menu.css
 *
 * Both *_sfx can have leading white space or not: if encountered it
 * WILL be removed and classes are applied space-separated to allow
 * clean CSS inheritance.
 *
 * Other module params apply as usual.
 *
 * This are the "global" variables given to us from the Menu Module
 * to work and deal with:
 *	$module			stdClass Object of the Module itself
 *					->params in JSON format
 *	$attribs		assoc array with all attributes from <jdoc:load .../>
 *
 *	$params 		JRegistry version of $Module->params
 * 	$class_sfx		value of that module parameter
 *	$showAll		integer value of that menu item paramater: 0|1 show subitems
 *
 * 	$list			Array version of the current menu (@see $menu)
 * 					often a massice huge list so beware of var_dump()ing it :)
 *	$active			stdClass representing the active menu item
 *	$active_id		same as $active->id (Itemid)
 *	$path 			array depicting the nesting level of the current menu item
 *
 * 	$app			JSite instance
 * 	$lang 			JLanguage instance
 * 	$menu			JMenuSite instance of the WHOLE menu
 *	$scope			previous application scope, but usually an empty string
 *	$option			active component name incl. com_
 *	$chrome			this file's path
 *	$content		comes in as an empty string and might be deprecated;
 *					Eventually represents the module's "output". No need to set
 *					this manually here unless you want to discard the default output
 *					of this layout and return any other 'content'. utterly weired...
 *
 * @package     Template
 * @subpackage  HTML
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   Copyright (C)2011 WebMechanic. All rights reserved.
 * @copyright   Copyright (C)2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$class_sfx .= ' '.$params->get('menutype').'_menu';

// cleanup moduleclass_sfx
if ( preg_match('#(?:[_|-](chapters|book_chapters))#iu', ($msfx = $params->get('moduleclass_sfx', '')), $m) )
{
	$params->set('moduleclass_sfx', str_replace('_'.$m[1], ' '.$m[1], $msfx) );
	unset($msfx);
}

/* SearchHelper knows about the (enhanced) stop words list in xx_XXLocalise
 * and is misused to clean the alias for use as a class name of list items
 */
JLoader::register('SearchHelper', JPATH_ADMINISTRATOR .'/components/com_search/helpers/search.php');

// unordered list
$elt = 'ul';
$ol_types = array();

// no active menu item, use default (home)
if (!$active) {
	$active = JFactory::getApplication()->getMenu()->getDefault();
}

// ordered list requested?
if ( preg_match('#(?:[_|-](ordered|ol))#iu', $class_sfx, $settings) )
{
	$elt = 'ol';
	$ol_types = array(
		'1'=>'decimal',
		'i'=>'lower-roman',
		'I'=>'upper-roman',
		'a'=>'lower-alpha',
		'A'=>'upper-alpha',
		);

	// remove "magic" class setting
	$class_sfx	= str_replace($settings[0], '', $class_sfx);
	array_shift($settings);

	// find template css override or use default 'better_menu.css'
	$tplname  = $app->getTemplate();
	$override = array_shift(explode(':',$params->get('layout', ':')));

	// some potential folders to check for better stylesheets
	$folders = array(JPATH_THEMES .DS. $tplname . '/html/mod_menu');
	if ($override != $tplname) {
		$folders[] = JPATH_THEMES .DS. $override . '/html/mod_menu';
	}

	// locate the goodness and turn it into a URL path
	foreach ((array)$folders as $i => $folder)
	{
		if (is_dir($folder)) {
			$folder = substr(JPath::clean($folder, '/'), strlen(JPATH_SITE));
			break;
		}
	}

	// although we honor the location of this $override for cross-usage
	// within any template(style), the active template folder seems more
	// relevant to contain an appropriate stylesheets ;)
	if (is_file($folder . '/better_'. $tplname. '.css'))
	{
		$class_sfx .= ' '.$tplname;
		$stylesheet	= '/better_'. $tplname. '.css';
	}
	else {
		$stylesheet	= '/better_menu.css';
	}

	JFactory::getDocument()->addStyleSheet(JURI::base(true). $folder . $stylesheet);

	// cleanup after ourselves
	unset($tplname, $folder, $folders, $stylesheet);
}

?><<?php
	// big fuzz for the opening tag ...
	echo $elt;

	// prepare class attribute
	$ol_class = 'menu '.$class_sfx;

	// id attribute for scripting usually, and those who can
	// only handle extra heavy overspecified selectors
	if ($tag_id = $params->get('tag_id', null)) {
		echo ' id="'.$tag_id.'"';
	}

	// type attribute
	// deprecated since HTML 4.01 so you know in case you're a Validator Evangelist
	if ($oltype = $active->params->get('ol_item_type', null)) {
		if (isset($ol_types[$oltype])) {
			echo ' type="'.$oltype.'"';
		}
	}

	// class attribute
	echo ' class="'.$ol_class.'"';

?>><?php
foreach ($list as $i => &$item) :

	$alias = str_replace('-', ' ', $item->alias);
	SearchHelper::santiseSearchWord($alias, $item->alias);

	// need this to find the default language
	$locale = JFactory::getLanguage()->get('tag');

	// @todo test if JLanguage was patched to contain getSingular()
	// @todo test if Localise::INFLECTION is true
	// even if it proxies, singularization is fine for this
	if ($item->language == 'de-DE' || ($item->language == '*' && $locale == 'de-DE') ) {
		if ( method_exists('de_DELocalise', 'singularise') ) {
			$alias = de_DELocalise::singularise($alias);
		}
	}
	// fall back for english
	elseif ($item->language == 'en-GB' || ($item->language == '*' && $locale == 'en-GB') ) {
		if ( method_exists('en_GBLocalise', 'singularise') ) {
			$alias = en_GBLocalise::singularise($alias);
		}
	}
	else {
		// @todo do some smart check for other xx-XXLocalise classes
		if ( method_exists('en_GBLocalise', 'singularise') ) {
			$alias = en_GBLocalise::singularise($alias);
		}
	}

	// we use '_' instead of '-' 'cos there's lesse potential for
	// name-slahes with module names and common classes
	$alias = str_replace(' ', '_', trim($alias));

	$class = array('mi', $alias);

	if ($item->id == $active_id) {
		$class[] = 'current';
	}

	if (	$item->type == 'alias'
		&&	in_array($item->params->get('aliasoptions'), $path)
		||	in_array($item->id, $path)) {
	  $class[] = 'active';
	}

	if ($item->deeper) {
		$class[] = 'deeper';
	}

	if ($item->parent) {
		$class[] = 'parent';
	}

	if (!empty($class)) {
		$class = ' class="'. implode(' ', $class) .'"';
	}

	echo '<li id="item-'.$item->id.'"'.$class.'>';

	// Render the menu items using our local overrides.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
			require dirname(__FILE__) . '/better_'.$item->type.'.php';
			break;

		default:
			require dirname(__FILE__) . '/better_url.php';
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper) {
		echo '<'. $elt .' class="submenu">';
	}
	// The next item is shallower.
	else if ($item->shallower) {
		echo '</li>';
		echo str_repeat('</'.$elt.'></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}
endforeach;
?></<?php echo $elt;?>>
