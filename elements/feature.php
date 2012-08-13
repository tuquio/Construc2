<?php
/**
 * Features Abstract Class.
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/** Register Standard Feature Classes */
JLoader::register('ElementFeatureBehavior', WMPATH_TEMPLATE . '/elements/features/behavior.php');

/**
 *
 */
class ElementFeature extends ElementRendererAbstract
{
	/**
	 * Overloading ElementRendererAbstract::getInstance()
	 */
	public static function getInstance($type, $attribs=null)
	{
		if (!isset(self::$elements[$type]))
		{
			$class = 'ElementFeature' . ucfirst($type);
			require_once WMPATH_TEMPLATE . '/elements/features/'. $type . '.php';

			self::$elements[$type] = new $class($attribs);
			self::$elements[$type]->init();
		}

		return self::$elements[$type];
	}

	/**
	 * Dynamic script loader using a self executing function.
	 *
	 *	<code>(function(W,D,src) {
	 *         your prerun code;
	 *         script loader code;
	 *         })(window,document,"script.js");</code>
	 *
	 * The $prerun can be as simple as a JavaScript snippet to test
	 * a client feature and "return", i.e. to test if the JSON object
	 * is available and if so avoid a request for the "json.js" shiv
	 * passed with the $href parameter:
	 *
	 * 	<code>$this->loader('{tmpl.js}/json2.min.js', 'if (W.JSON) {return;}');</code>
	 *
	 * If $prerun is an array it may contain the following keys:
	 * - run :  a JS statement to run before requesting the $href
	 * 			Should call "return" to cancel the request.
	 * - msie:  a conditional comment selector for MSIE 'lt IE 8'
	 *
	 * @todo Implement $prerun array keys
	 *
	 * @see yepnope()
	 * @param  string        $href   The script to load, may contain {subst.keys}
	 * @param  string|array  $prerun see method comments
	 * @return string
	 */
	public function loader($href, $prerun='')
	{
		if (is_array($prerun)) {
			//#FIXME check array keys 'run' and 'msie'
			$prerun = implode(' ', $prerun);
		}

		return	'(function(W,D,src) {'
				.	$prerun
				.	';var a=D.createElement("script");'
				.	'var b=D.getElementsByTagName("script")[0];'
				.	'a.src=src;a.async=true;a.type="text/javascript";'
				.	'b.parentNode.insertBefore(a,b);})(window,document,"'. self::subst($href) .'");';
	}

	/**
	 * Interface to utilize yepnope.js for advanced conditional script and
	 * css loading.
	 *
	 * @link http://yepnopejs.com/
	 * @see loader()
	 */
	public function yepnope()
	{

	}

	/**
	 * Checks whether the named feature is enabled.
	 *
	 * @param  string $name feature to test
	 * @return boolean true if feature is enabled, false otherwise
	 */
	protected function hasFeature($name)
	{
		return ConstructTemplateHelper::getInstance()->getTheme()->getFeature($name);
	}

	/**
	 * @param array $data
	 * @param mixed $options
	 * @return ElementFeature
	 */
	public function build(array &$data, $options=null) {return $this;}

	/**
	 * Proxy to ElementRenderer setter.
	 *
	 * $types:
	 * - link   : data = array, 'href' required
	 * - script : data = array, 'src' required
	 * - styles : data = string
	 * - scripts: data = string
	 *
	 * @see ElementRendererLink::set(), ElementRendererScript::set()
	 * @see ElementRendererStyles::set(), ElementRendererScripts::set()
	 */
	public function set($type, $data, $ua=null)
	{
		$type = strtolower($type);

		if ($type == 'link')
		{
			settype($data['rel'], 'string');
			ElementRendererAbstract::getInstance('renderer.'. $type)->set($data['href'], $data['rel'], $data, $ua);
		}
		elseif ($type == 'script')
		{
			ElementRendererAbstract::getInstance('renderer.'. $type)->set($data['src'], $data, $ua);
		}
		elseif ($type == 'styles')
		{
			// in this case it better be a string ;)
			ElementRendererAbstract::getInstance('renderer.'. $type)->set($this->name, (string) $data, $ua);
		}
		elseif ($type == 'scripts')
		{
			// in this case it better be a string ;)
			ElementRendererAbstract::getInstance('renderer.'. $type)->set((string) $data, null, $ua);
		}

	}

}
