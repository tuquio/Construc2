<?php
/**
 * A better article category menu override.
 * This overrides creates a menu as one would expect and only
 * adds heading if other content is supposed to be rendered, too.
 *
 *	$module			stdClass Object of the Module itself
 *					->params in JSON format
 *	$attribs		assoc array with all attributes from <jdoc:load .../>
 *
 *	$params 		JRegistry version of $Module->params
 * 	$moduleclass_sfx	value of that module parameter
 *
 *  $startLevel		integer, hierarchy level
 *
 *  $list			array of (huge) JCategoryNode objects
 *  $path			filepath of this file
 *
 * 	$app			JSite instance
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
defined('_JEXEC') or die;

JLoader::load('ContentLayoutHelper');

function treeWalker($list, &$tree, $level=0)
{
	foreach ($list as $item)
	{
		foreach ($item->getPath() as $slug)
		{
			$ar  = explode(':', $slug);
			$pid = array_shift($ar);
			$tree['pids'][$pid][$item->id] = $item->id;
		}

		if ($item->hasChildren()) {
			treeWalker($item->getChildren(), $tree, $item->level);
		}
	}
	return $tree;
}

$tree = array('pids'=>array());
$tree['pids'][$params->get('parent')] = array();
treeWalker($list, $tree);

$item_heading	= (int)  $params->get('item_heading', 3);
$show_children	= (bool) $params->get('show_children', 0);
$max_level		= (int)  $params->get('maxlevel', 0);

// toggle nesting of a+span to span+a
$outerspan = true;

$_alang = JFactory::getLanguage()->get('tag');
$_ccid  = $app->input->get('catid', $app->input->get('id', 0));
?>
<menu class="menu categories <?php echo $moduleclass_sfx ?>">
<?php require JModuleHelper::getLayoutPath('mod_articles_categories', $params->get('layout', 'default').'_items'); ?>
</menu>
