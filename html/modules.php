<?php defined('_JEXEC') or die;
/**
 * @package		Templates
 * @subpackage	HTML
 * @author		Joomla Engineering http://joomlaengineering.com
 * @copyright	Copyright (C) 2010, 2011 Matt Thomas. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Renders the module within a simple DIV container and allows to assign a multitude
 * of class names to the container.
 * Accepted attributes:
 * - level: default 3, the heading level to apply. 3 = <h3>
 * - header-class: default 'je-header', CSS class for the Hx element
 * In addition to moduleclass and moduleclass_sfx
 * - module-class: optional CSS class for the DIV container
 * - outline-style: optional CSS class for the DIV container
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

	echo '<div class="moduletable ', $module->module, ' ', $params->get('moduleclass_sfx'), $moduleClass, $outlineStyle, ' clearfix">';
		if ($module->showtitle) {
			echo '<h', $headerLevel, ' class="', $headerClass, '">', $module->title, '</h', $headerLevel, '>';
		}
		echo $module->content ,'
	</div>';
}

/**
 * A module style to render modules according to Stubbornella's OOCSS framework, http://oocss.org
 * <b>Joomla's default 'moduletable' class is ignored!</b>
 *
 * Accepted attributes:
 * - level: default 3, the heading level to apply. 3 = <h3>
 * - module-class: optional CSS class for the DIV container
 * The module name (mod_foobar) is split to become "mod foobar" to apply
 * the basic styles of ".mod" and potentionally specialized via ".foobar".
 *
 *
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 * @link https://github.com/stubbornella/oocss/wiki/standard-module-format
 * @link http://oocss.org
 * @author WebMechanic http://webmechanic.biz
 */
function modChrome_mod( $module, &$params, &$attribs ) {

	$module->content = trim($module->content);
	if (empty($module->content)) return;

	$headerLevel = isset($attribs['level'])         ? (int) $attribs['level'] : 3;
	$moduleClass = isset($attribs['module-class'])  ? ' '.$attribs['module-class'] : '';
	$moduleName  = str_replace('_', '-', substr($module->module, 4));
	if ($sfx = $params->get('moduleclass_sfx')) {
		$moduleName  .= ' ' . $sfx;
	}

	$oocssClass = isset($attribs['__oocss__'])  ? ' '.$attribs['__oocss__'] : '';

	echo '<div class="mod ', $oocssClass, $moduleName, $moduleClass, '">', PHP_EOL
		, '	<b class="top"><b class="tl"></b><b class="tr"></b></b>', PHP_EOL
		, '	<div class="inner">', PHP_EOL;
		if ($module->showtitle) {
			echo '<div class="hd">';
			echo '<h', $headerLevel, '>', $module->title, '</h', $headerLevel, '>';
		}
		echo '<div class="bd">', $module->content , '</div>', PHP_EOL
		, '	</div>', PHP_EOL
		, '	<b class="bottom"><b class="bl"></b><b class="br"></b></b>', PHP_EOL
		, '</div>';
}

/**
 * Extends {@link modChrome_mod} with the 'complex' class for modules with
 * complex backgrounds and border(-images)
 *
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 * @uses modChrome_mod()
 * @link https://github.com/stubbornella/oocss/wiki/Module
 * @author WebMechanic http://webmechanic.biz
 */
function modChrome_complex( $module, &$params, &$attribs )
{
	$attribs['__oocss__'] = 'complex';
	modChrome_mod( $module, $params, $attribs );
}

/**
 * Extends {@link modChrome_mod} with the 'pop' class for modules used as
 * pop-outs or call-outs.
 *
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 * @uses modChrome_mod()
 * @link https://github.com/stubbornella/oocss/wiki/Module
 * @author WebMechanic http://webmechanic.biz
 */
function modChrome_pop( $module, &$params, &$attribs )
{
	$attribs['__oocss__'] = 'pop';
	modChrome_mod( $module, $params, $attribs );
}

/**
 * Extends {@link modChrome_mod} to render a "talk bubble".
 * Requires "/oocss/plugins/talk.css" to be loaded to function properly.
 * Talk bubbles are used to give context specific help, however they may be
 * used for other purposes like blog comments, cartoon-style talk bubbles, etc
 *
 * Using talk.css pointer images appear on the left (bubble=top|bottom)
 * or upper (bubble=left|right) section of the given side.
 * Setting 'edge' to 'other' toggles that behavior.
 *
 * Configurable attributes:
 * - bubble: top|bottom|left|right, location side of the pointer
 * - edge: other, push pointer to opposit side, also useful for .rtl
 *
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 * @uses modChrome_mod()
 * @link https://github.com/stubbornella/oocss/wiki/Talk-Bubbles
 * @author WebMechanic http://webmechanic.biz
 */
function modChrome_bubble( $module, &$params, &$attribs )
{
	$attribs['__oocss__'] = 'bubble';
	modChrome_mod( $module, $params, $attribs );
}

/**
 * Enter description here ...
 * @param string $content
 * @param JParameter $params
 */
function modChrome_withevent(&$content, $params)
{
	static $item;
	if (null == $item) {
		// Create temporary article-stub to have all req. attriutes
		$item = JTable::getInstance('content');
		$item->parameters = new JParameter('');
	}
	$item->text = $content;

	// Apply content plugins to custom module content
	# JFactory::getApplication()->triggerEvent('onPrepareContent', array (&$item, &$params, 1));
	JDispatcher::getInstance()->trigger('onPrepareContent', array (&$item, &$params, 1));
	$content = $item->text;
}

