<?php defined('_JEXEC') or die;
/**
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Renders the module within a simple DIV container and allows to assign a multitude
 * of class names to the container.
 * Accepted attributes:
 * - level: default 3, the heading level to apply. 3 = <h3>
 * - header-class: default 'ct-header', CSS class for the Hx element
 * In addition to moduleclass and moduleclass_sfx
 * - module-class: optional CSS class for the DIV container
 * - outline-style: optional CSS class for the DIV container
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 */
function modChrome_chtml( $module, &$params, &$attribs )
{
	$module->content = trim($module->content);
	if (empty($module->content)) return;

	$oocss	= isset($attribs['oocss']);

	$css	= array();
	$css[] 	= $oocss ? $attribs['oocss'] : '';

	// 'custom' makes for a pretty weired name
	if ('custom' != $module->module) {
		$css[]	= str_replace('_', '-', $module->module);
	}

	$css[] 	= isset($attribs['module-class'])  ? $attribs['module-class'] : $module->position;
	$css[] 	= isset($attribs['outline-style']) ? 'outline-'.$attribs['outline-style'] : '';
	$css[] 	= $params->get('moduleclass_sfx');
	$css    = trim(implode(' ', array_unique($css) ));

	echo '<div class="', $css, '">';
	if ($oocss) echo '<b class="top"><b class="tl"></b><b class="tr"></b></b>', PHP_EOL;

	echo '<div class="inner">', PHP_EOL;

	if ($module->showtitle)
	{
		$level = isset($attribs['level']) ? (int) $attribs['level'] : 3;

		// flip title for guests and users
		$u = JFactory::getUser()->guest ? '_0' : '_1';
		if (JFactory::getLanguage()->hasKey(strtoupper($module->title . $u))) {
			$module_title = JText::_($module->title . $u);
		}
		else {
			$module_title = $module->title;
		}

		$css = array();
		if ('custom' != $module->module) {
			$css[] = str_replace(array('_','mod_'), array('_',''), $module->module) .'-title';
		}
		$css[] = isset($attribs['header-class']) ? $attribs['header-class'] : 'ct-header';

		$css   = trim(implode(' ', array_unique($css) ));

		if ($oocss) echo '<div class="hd">';
		echo ' <h', $level, ' class="', $css, '">', $module_title, '</h', $level, '>';
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
function modChrome_mod( $module, &$params, &$attribs )
{
	static $toggle = 0;

	$module->content = trim($module->content);
	if (empty($module->content)) return;

	if (array_key_exists('oocss', $attribs)) {
		$attribs['oocss'] = 'mod '. $attribs['oocss'];
	} else {
		$attribs['oocss'] = 'mod';
	}

	if (array_key_exists('toggle', $attribs)) {
		$params->set('moduleclass_sfx', ($toggle % 2 ? 'even' : 'odd'));
		$toggle++;
	} else {
		$toggle = 0;
	}

	modChrome_chtml( $module, $params, $attribs );
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
 * Trigger content plugins on module content. As of J1.7 this feature is built in.
 * @param string $content
 * @param JParameter $params
 */
function modChrome_withevent( $module, &$params, &$attribs )
{
	$module->content = JHtml::_('content.prepare', $module->content, $params);

	modChrome_chtml( $module, $params, $attribs );
}

