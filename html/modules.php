<?php defined('_JEXEC') or die;
/**
 * @package		Template Framework for Joomla! 1.6
 * @author		Joomla Engineering http://joomlaengineering.com
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2010, 2011 Matt Thomas. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Renders the module within a simple DIV container and allows to assign a multitude
 * of class names to the container.
 * Accepted attributes:
 * - level: default 3, the heading level to apply. 3 = <h3>
 * - header-class: default 'je-header', CSS class for the Hx element
 * - module-class: optional CSS class for the DIV container
 * 					(besides moduleclass and moduleclass_sfx)
 * - outline-style: optional CSS class for the DIV container
 * 					(besides moduleclass and moduleclass_sfx)
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 */
function modChrome_jexhtml( $module, &$params, &$attribs ) {

	$module->content = trim($module->content);
	if (empty($module->content)) return;

	$headerLevel  = isset($attribs['level'])         ? (int) $attribs['level'] : 3;
	$headerClass  = isset($attribs['header-class'])  ? $attribs['header-class'] : 'je-header';
	$moduleClass  = isset($attribs['module-class'])  ? ' '.$attribs['module-class'] : '';
	$outlineStyle = isset($attribs['outline-style']) ? ' outline-'.$attribs['outline-style'] : '';

	echo '<div class="moduletable', $params->get('moduleclass_sfx'), $module->module, $moduleClass, $outlineStyle, ' clearfix">';
		if ($module->showtitle) {
			echo '<h', $headerLevel, ' class="', $headerClass, '">', $module->title, '</h', $headerLevel, '>';
		}
		echo $module->content ,'
	</div>';
}

/**
 * Renders the module nested in a 3-div compartment to allow "old-style" rounded
 * corners using background images etc.
 * Accepted attributes:
 * - level: default 3, the heading level to apply. 3 = <h3>
 * - header-class: default 'je-header', CSS class for the Hx element
 * - module-class: optional CSS class for the DIV container
 * 					(besides moduleclass and moduleclass_sfx)
 * - outline-style: optional CSS class for the DIV container
 * 					(besides moduleclass and moduleclass_sfx)
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 * @deprecated
 */
function modChrome_jerounded( $module, &$params, &$attribs ) {

	$module->content = trim($module->content);
	if (empty($module->content)) return;

	$headerLevel  = isset($attribs['level'])         ? (int) $attribs['level'] : 3;
	$headerClass  = isset($attribs['header-class'])  ? $attribs['header-class'] : 'je-header';
	$moduleClass  = isset($attribs['module-class'])  ? ' '.$attribs['module-class'] : '';
	$roundedStyle = isset($attribs['rounded-style']) ? ' rounded-'.$attribs['rounded-style'] : '';

	echo '<div class="moduletable', $params->get('moduleclass_sfx'), $module->module, $moduleClass, $roundedStyle, '">'
		, '	<div><div><div>', PHP_EOL;
		if ($module->showtitle) {
			echo '<h', $headerLevel, ' class="', $headerClass, '">', $module->title, '</h', $headerLevel, '>';
		}
		echo $module->content
		, '	</div></div></div>', PHP_EOL,
		'</div>';
}

/**
 * A module style to render modules according to Stubbornella's OOCSS framework, http://oocss.org
 *
 * Although the module container accepts moduleclass_sfx it's value is separated
 * with a space to force use of the cascade and a leading dash or hyphen is removed.
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 * @link http://oocss.org
 */
function modChrome_oocss( $module, &$params, &$attribs ) {

	$module->content = trim($module->content);
	if (empty($module->content)) return;

	$headerLevel  = isset($attribs['level'])         ? (int) $attribs['level'] : 3;
	$moduleClass  = isset($attribs['module-class'])  ? ' '.$attribs['module-class'] : '';
	echo '<div class="mod ', $module->module, ltrim($params->get('moduleclass_sfx'), '_- '), $moduleClass, '">', PHP_EOL
		, '	<b class="top"><b class="tl"></b><b class="tr"></b></b>', PHP_EOL
		, '	<div class="inner">', PHP_EOL;
		if ($module->showtitle) {
			echo '<div class="hd">';
			echo '<h', $headerLevel, ' class="je-header">', $module->title, '</h', $headerLevel, '>';
		}
		echo '<div class="bd">', $module->content , '</div>', PHP_EOL
		, '	</div>', PHP_EOL
		, '	<b class="bottom"><b class="bl"></b><b class="br"></b></b>', PHP_EOL
		, '</div>';
}

