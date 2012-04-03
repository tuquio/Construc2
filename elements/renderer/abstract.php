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
	public function build(array &$data, $options=null);
	public function set($key, $value, $ua=null);
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

	/** API compliance with {@link JDocumentRenderer} */
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
			// BC
			if (strpos($type, '.') === false) {
				$type = 'renderer.'. $type;
			}
			$parts = explode('.', $type);

			$class = 'Element'. ucfirst($parts[0]) . ucfirst($parts[1]);

			self::$elements[$type] = new $class($attribs);
			self::$elements[$type]->init();
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

