<?php
/**
 * Element Renderer
 *
 * @package     Construc2
 * @subpackage  Renderer
 * @copyright	(C)2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

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
			FB::warn(get_class($this), 'MISSING NAME!');
			preg_match('/([A-Z]+[a-z]+)$/', get_class($this), $parts);
			$this->name  = strtolower($parts[1]);
		}

		if (is_array($attribs)) {
			$this->attribs = $attribs;
		}
	}

	public static function getInstance($type, $attribs=null)
	{
		if (!isset(self::$elements[$type]))
		{
			$class = __CLASS__ . ucfirst($type);
			self::$elements[$type] = new $class($attribs);
			self::$elements[$type]->init();
		}

		return self::$elements[$type];
	}

	protected function init() {return $this;}

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

	public function __toArray()
	{
		return $this->data;
	}

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
}

