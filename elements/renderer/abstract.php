<?php
/**
 * Element Renderer
 *
 * @package     Construc2
 * @subpackage  Renderer
 * @copyright	(C)2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

JLoader::register('ElementFeature', WMPATH_ELEMENTS . '/feature.php');
JLoader::register('ElementWidget' , WMPATH_ELEMENTS . '/widget.php');

/**
 * Element Renderer Interface
 */
interface IElementRenderer
{

	/**
	 * @abstract
	 * @param array $data
	 * @param mixed $options
	 * @return ElementRendererAbstract Concrete instance of subclass.
	 */
	public function build(array &$data, $options=null);

	/**
	 * Sets the value/data for the designated $key of this element.
	 *
	 * Use $uagent to add browser specific resources, typically for MSIE
	 * in which case a conditional comment (CC) based on $uagent is added
	 * to group output.
	 *
	 * MSIE CC $uagent examples:
	 * - IE         = any MSIE with support for CC
	 * - IE 6       = MSIE 6 only
	 * - !IE 6      = all but MSIE 6
	 * - lt IE 9    = MSIE 5 - MSIE 8
	 * - lte IE 9   = MSIE 5 - MSIE 9
	 * - gt IE 6    = MSIE 7 - MSIE 9
	 * - gte IE 9   = MSIE 9
	 * - IEMobile   = MSIE 7 - MSIE 9 on smart phones
	 *
	 * @abstract
	 * @param string $key
	 * @param mixed  $value
	 * @param string $ua
	 * @return ElementRendererAbstract Concrete instance of subclass.
	 */
	public function set($key, $value, $uagent=null);
}

/**
 * Abstract Element Renderer Class
 */
abstract class ElementRendererAbstract
{
	const UA = 'ALL';

	protected $attribs = array();
	protected $name;
	protected $data = array();
	protected static $elements = array();

	/**
	 * @param mixed $attribs
	 */
	protected function __construct($attribs=null)
	{
		if (!isset($this->name))
		{
			if (defined('DEVELOPER_MACHINE')) {FB::warn(get_class($this), 'MISSING NAME!');}
			preg_match('/([A-Z]+[a-z]+)$/', get_class($this), $parts);
			$this->name  = strtolower($parts[1]);
		}

		if (is_array($attribs)) {
			$this->attribs = $attribs;
		}

		$this->init();
	}

	/**
	 * @static
	 * @param      $type
	 * @param null $attribs
	 *
	 * @return ElementRendererAbstract
	 */
	public static function getInstance($type, $attribs=null)
	{
		if (!isset(self::$elements[$type]))
		{
			if (strpos($type, '.') === false) {
				$type = 'renderer.'. $type;
			}
			$parts = explode('.', $type);

			$class = 'Element'. ucfirst($parts[0]) . ucfirst($parts[1]);

			self::$elements[$type] = new $class($attribs);
		}

		return self::$elements[$type];
	}

	/**
	 * @return ElementRendererAbstract
	 */
	protected function init() {return $this;}

	/**
	 * Parse substitution {keys} into their full URL equivalent.
	 *
	 * @param  string $url
	 */
	static public function subst(&$url)
	{
		static $subst;

		if (!isset($subst)) {
			$subst = ConstructTemplateHelper::getInstance()->getConfig('subst');
		}

		if (preg_match('#{([a-z\.]+)}#', $url, $m)) {
			$url = str_replace($m[0], $subst[$m[1]], $url);
		}

		return $url;
	}

	/**
	 * The __toString() method allows a class to decide how it will
	 * react when it is treated like a string.
	 *
	 * @return string A rendering of the $data keys and values.
	 */
	public function __toString()
	{
		$output = '';

		foreach (array_keys($this->data) as $key) {
			if (is_array($this->data[$key])) {
				$output .= implode(PHP_EOL, $this->data[$key]) . PHP_EOL;
			} else {
				$output .= $this->data[$key] . PHP_EOL;
			}
		}

		return $output;
	}

	/**
	 * The __toArray() method allows a class to decide how it will
	 * react when it is treated like an array.
	 *
	 * @return array Same as $data
	 */
	public function __toArray()
	{
		return $this->data;
	}
}

