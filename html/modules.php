<?php defined('_JEXEC') or die;
/**
 * Module "Chrome style" functions: mod, complex, pop, bubble, withevent, chtml.
 *
 * modChrome_chtml is the working horse the other "styles" proxy to after
 * setting different class attributes.
 *
 * @package     Templates
 * @subpackage  Construc2
 * @copyright   (C)2011-2012 WebMechanic
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
 *
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 */
function modChrome_chtml( $module, &$params, &$attribs )
{
	static $ooobj = array('mod'=>false,'complex'=>false,'pop'=>false,'bubble'=>false);

	// don't render empty modules, "whitelist" for mod_custom and mod_banner
	if (ConstructTemplateHelper::isEmpty($module->content)) return;

	$css	= array();
	$oocss  = null;

	if (isset($attribs['oocss']))
	{
		$oocss = (object) $ooobj;
		$css   = explode(' ', $attribs['oocss']);
		array_filter($css);
		foreach ($css as $modx)
		{
			$modx = trim($modx);
			if ($modx) {
				$oocss->{$modx} = true;
			}
		}
	}

	// 'custom' makes for a pretty weired name
	if ('custom' != $module->module) {
		$css[] = str_replace('_', '-', $module->module);
	}

	$css[] 	= isset($attribs['module-class'])  ? $attribs['module-class'] : $module->position;
	$css[] 	= $params->get('moduleclass_sfx');
	$css    = trim(implode(' ', array_unique($css) ));

	echo '<div class="', $css, '">';

		if ($oocss && $oocss->complex) {
			echo '<b class="top"><b class="tl"></b><b class="tr"></b></b>', PHP_EOL;
		}

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

		if ($oocss && $oocss->complex) {
			echo '<b class="bottom"><b class="bl"></b><b class="br"></b></b>', PHP_EOL;
		}

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
 */
function modChrome_mod( $module, &$params, &$attribs )
{
	static $toggle = 0;

	// don't render empty modules, "whitelist" for mod_custom and mod_banner
	if (ConstructTemplateHelper::isEmpty($module->content)) return;

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
 */
function modChrome_bubble( $module, &$params, &$attribs )
{
	$attribs['oocss'] = 'bubble';
	if (isset($attribs['bubble'])) $attribs['oocss'] .= ' ' . $attribs['bubble'];
	if (isset($attribs['edge']))   $attribs['oocss'] .= ' ' . $attribs['edge'];

	modChrome_mod( $module, $params, $attribs );
}

/**
 * Trigger content plugins on module content. As of J1.7 this feature is built in.
 *
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 */
function modChrome_withevent( $module, &$params, &$attribs )
{
	// don't render empty modules, "whitelist" for mod_custom and mod_banner
	if (ConstructTemplateHelper::isEmpty($module->content)) return;

	$module->content = JHtml::_('content.prepare', $module->content, $params);

	modChrome_chtml( $module, $params, $attribs );
}

/**
 * As simple as it can get: the (optional) title at the given heading level
 * and the content.
 *
 * @param object     $module
 * @param JRegistry  $params
 * @param array      $attribs
 */
function modChrome_raw( $module, &$params, &$attribs )
{
	// don't render empty modules, "whitelist" for mod_custom and mod_banner
	if (ConstructTemplateHelper::isEmpty($module->content)) return;

	if ($module->showtitle) {
		$level = isset($attribs['level']) ? (int) $attribs['level'] : 3;
		echo ' <h', $level, ' class="', $css, '">', $module->title, '</h', $level, '>';
	}
	echo $module->content;
}
