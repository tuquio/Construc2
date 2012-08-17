<?php defined('_JEXEC') or die;
/**
 * A better mod_menu override
 *
 * Magic class_sfx:
 *  - '_ordered' or '_ol' results in an <OL> element to be created instead of <UL>
 * 		The value is removed from the final class list.
 * Magic moduleclass_sfx:
 *  - '_chapters becomes 'moduletable chapters' to leverage the provided
 *    book chapter numbering found in the example better_menu.css
 *
 * Both *_sfx can have leading white space or not: if encountered it
 * WILL be removed and classes are applied space-separated to allow
 * clean CSS inheritance.
 *
 * Other module params apply as usual.
 *
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @var object $module		the Module itself w/ ->params in JSON format
 * @var array $attribs		all attributes from <jdoc:load .../>
 *
 * @var JRegistry $params	$module->params
 * @var string $class_sfx	value of that module parameter
 * @var integer $showAll	integer value of that menu item parameter: 0|1 show sub items
 *
 * @var array $list 		the current menu (@see $menu), beware of var_dump() !
 * @var object $active		represents the active menu item
 * @var integer $active_id	same as $active->id (Itemid)
 * @var array $path			depicting the nesting level of the current menu item
 *
 * @var JSite $app			instance of JApplication
 * @var JMenuSite $menu 	instance of JMenu, the COMPLETE menu
 * @var string $scope		previous application scope, but usually an empty string
 * @var string $option		active component name incl. com_
 * @var string $chrome		this file's path
 * @var string $content		comes in as an empty string and might be deprecated;
 *							Eventually represents the module's "output".
 *
 * There's no need to set $content manually here unless you want to discard the
 * default output of this layout and return any other 'content'. utterly weired...
 */

JLoader::register('BetterMenuHelper', dirname(__FILE__) . '/_helper.php');

$class_sfx .= ' '.$params->get('menutype');
$_alang = JFactory::getLanguage()->get('tag');

// cleanup moduleclass_sfx
if ( preg_match('#(?:[_|-](chapters|book_chapters))#iu', ($msfx = $params->get('moduleclass_sfx', '')), $m) )
{
	$params->set('moduleclass_sfx', str_replace('_'.$m[1], ' '.$m[1], $msfx) );
	unset($msfx);
}

// <menu> got "lost" in XHTML for no reason, but
// has always been supported by any browser on earth
$elt = 'menu';
$ol_types = array();

// no active menu item, use default (home)
if (!$active) {
	$active = JFactory::getApplication()->getMenu()->getDefault();
}

// ordered list requested?
#TODO $ordered = BetterMenuHelper::renderType($module, $params, $active);

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
	$folders = array(JPATH_THEMES .'/'. $tplname . '/html/mod_menu');
	if ($override != $tplname) {
		$folders[] = JPATH_THEMES .'/'. $override . '/html/mod_menu';
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
	if (is_file($folder . '/css/better_'. $tplname. '.css'))
	{
		$class_sfx .= ' '.$tplname;
		$stylesheet	= '/css/better_'. $tplname. '.css';
	}
	else {
		$stylesheet	= '/css/better_menu.css';
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
foreach ($list as $i => &$item)
{
	$alias = BetterMenuHelper::getCssAlias($item);

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

	$liclass = $class;
	echo '<li id="item-'.$item->id.'" class="'.trim(implode(' ', $class)).'">';

	// Render the menu items using our local overrides.
	switch ($item->type) {
		case 'separator':
		case 'url':
		case 'component':
			require dirname(__FILE__) . '/better_' . $item->type . '.php';
			break;

		default:
			require dirname(__FILE__) . '/better_url.php';
			break;
	}

	// The next item is deeper.
	if ($item->deeper) {
		echo '<'. $elt .' class="submenu">';
	}
	// The next item is shallower.
	elseif ($item->shallower) {
		echo '</li>';
		echo str_repeat('</'.$elt.'></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}
}

?></<?php echo $elt;?>>
