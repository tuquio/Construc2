<?php
/**
 * Features Abstract Class.
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

define('WMPATH_FEATURE' , WMPATH_ELEMENTS .'/features');

/** Register Standard Feature Classes */
JLoader::register('ElementFeatureBehavior', WMPATH_FEATURE . '/behavior.php');

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
			require_once WMPATH_FEATURE .'/'. $type . '.php';

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
	 * @param  string        $href  The script to load, may contain {subst.keys}
	 * @param  string|array  $prerun see method comments
	 */
	public function loader($href, $prerun='')
	{
		if (is_array($prerun)) {
			//#FIXME check properties
		}

		return	'(function(W,D,src) {'
				.	$prerun
				.	';var a=D.createElement("script");'
				.	'var b=D.getElementsByTagName("script")[0];'
				.	'a.src=src;a.async=true;a.type="text/javascript";'
				.	'b.parentNode.insertBefore(a,b);})(window,document,"'. self::subst($href) .'");';
	}

	protected function hasFeature($name)
	{
		static $features = array();

		if (isset($features[$name])) {
			return $features[$name];
		}

		$features[$name] = ConstructTemplateHelper::getInstance()->getTheme()->getFeature($name);
		return $features[$name];
	}

	public function build(array &$data, $options=null) {}

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
		else if ($type == 'script')
		{
			ElementRendererAbstract::getInstance('renderer.'. $type)->set($data['src'], $data, $ua);
		}
		else if ($type == 'styles')
		{
			// in this case it better be a string ;)
			ElementRendererAbstract::getInstance('renderer.'. $type)->set($this->name, (string) $data, $ua);
		}
		else if ($type == 'scripts')
		{
			// in this case it better be a string ;)
			ElementRendererAbstract::getInstance('renderer.'. $type)->set((string) $data, null, $ua);
		}

	}

}

class JFormFieldFeature extends JFormField
{
	protected $type = 'Feature';

	public function __construct($form = null)
	{
		parent::__construct($form);
	}

	protected function getInput()
	{
		$html = '';

		return $html;
	}

	/**@#+
	 * Proxies for logging and debugging
	 * @ignore
	 */
	protected function getFieldName($fieldName)
	{
		return parent::getFieldName($fieldName);
	}

	protected function getId($fieldId, $fieldName)
	{
		return parent::getId($fieldId, $fieldName);
	}

	protected function getTitle()
	{
		return parent::getTitle();
	}

	protected function getLabel()
	{
		return parent::getLabel();
	}

	protected function getName($fieldName)
	{
		return parent::getName($fieldName);
	}
	/**@#- */
}
