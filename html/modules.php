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

	$oocss	= isset($attribs['oocss']);

	$css	= array();
	$css[] 	= $oocss ? $attribs['oocss'] : '';
	$css[]	= str_replace('_', '-', $module->module);
	$css[] 	= $params->get('moduleclass_sfx');

	$css[] 	= isset($attribs['module-class'])  ? $attribs['module-class'] : 'moduletable';
	$css[] 	= isset($attribs['outline-style']) ? 'outline-'.$attribs['outline-style'] : '';

	echo '<div class="', trim(implode(' ', $css)), '">';
	if ($oocss) echo '<b class="top"><b class="tl"></b><b class="tr"></b></b>', PHP_EOL;

	echo '<div class="inner">', PHP_EOL;

	if ($module->showtitle) {
		$level = isset($attribs['level'])         ? (int) $attribs['level'] : 3;
		$css   = array();
		$css[] = str_replace('_', '-', $module->module);
		$css[] = isset($attribs['header-class'])  ? $attribs['header-class'] : 'je-header';
		if ($oocss) echo '<div class="hd">';
		echo ' <h', $level, ' class="', trim(implode(' ', $css)), '">', $module->title, '</h', $level, '>';
		if ($oocss) echo '</div>', PHP_EOL;
	}

	if ($oocss) echo '<div class="bd">', PHP_EOL;
	echo $module->content;
	if ($oocss) echo ' </div>', PHP_EOL;

	echo '</div><!-- .inner -->';

	if ($oocss) echo '<b class="bottom"><b class="bl"></b><b class="br"></b></b>', PHP_EOL;
	echo '</div>', PHP_EOL;
}

/**
 * A module style to render modules according to Stubbornella's OOCSS framework, http://oocss.org
 * If this style is explicitly used <b>Joomla's default 'moduletable' class is ignored!</b>
 *
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 * @link  https://github.com/stubbornella/oocss/wiki/standard-module-format
 * @link  http://oocss.org
 * @author WebMechanic http://webmechanic.biz
 */
function modChrome_mod( $module, &$params, &$attribs ) {

	$module->content = trim($module->content);
	if (empty($module->content)) return;

	if (array_key_exists('oocss', $attribs)) {
		$attribs['oocss'] = 'mod '. $attribs['oocss'];
	} else {
		$attribs['oocss'] = 'mod';
	}

	modChrome_jexhtml( $module, $params, $attribs );
}

/**
 * Extends {@link modChrome_mod} with the 'complex' class for modules with
 * complex backgrounds and border(-images)
 *
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 * @uses  modChrome_mod()
 * @link  https://github.com/stubbornella/oocss/wiki/Module
 * @author WebMechanic http://webmechanic.biz
 */
function modChrome_complex( $module, &$params, &$attribs )
{
	$attribs['oocss'] = 'complex';
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
	$attribs['oocss'] = 'pop';
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
	$attribs['oocss'] = 'bubble';
	$attribs['oocss'] = isset($attribs['bubble']) ? $attribs['bubble'] : '';
	$attribs['oocss'] = isset($attribs['edge'])   ? $attribs['edge'] : '';

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
		$item->parameters = new JRegistry('');
	}
	$item->text = $content;

	// Apply content plugins to custom module content
	# JFactory::getApplication()->triggerEvent('onPrepareContent', array (&$item, &$params, 1));
	JDispatcher::getInstance()->trigger('onPrepareContent', array (&$item, &$params, 1));
	$content = $item->text;
}

