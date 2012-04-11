<?php defined('_JEXEC') or die;
/**
 * Construc2 Template Theme.
 *
 * @package     Construc2
 * @subpackage  Engine
 * @copyright   (C) 2011-2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
!defined('WMPATH_TEMPLATE') && define('WMPATH_TEMPLATE', dirname(dirname(__FILE__)));
!defined('WMPATH_ELEMENTS') && define('WMPATH_ELEMENTS', WMPATH_TEMPLATE . '/elements');

JLoader::register('ElementRendererAbstract', WMPATH_TEMPLATE . '/elements/renderer/abstract.php');
JLoader::register('ElementRendererHead', WMPATH_TEMPLATE . '/elements/renderer/head.php');
JLoader::register('ElementRendererMeta', WMPATH_TEMPLATE . '/elements/renderer/head.php');
JLoader::register('ElementRendererLink', WMPATH_TEMPLATE . '/elements/renderer/head.php');
JLoader::register('ElementRendererStyles', WMPATH_TEMPLATE . '/elements/renderer/head.php');
JLoader::register('ElementRendererScript', WMPATH_TEMPLATE . '/elements/renderer/head.php');
JLoader::register('ElementRendererScripts', WMPATH_TEMPLATE . '/elements/renderer/head.php');
JLoader::register('ElementRendererCustom', WMPATH_TEMPLATE . '/elements/renderer/head.php');

/**
 * CustomTheme Base Class.
 */
class CustomTheme
{
	/**
	 * @var $theme CustomTheme instance of self
	 * @see getInstance()
	 */
	public static $theme;
	/**#+
	 * Theme Properties
	 * @see getConfig()
	 */
	/** @var $name string lowercase */
	protected $name = 'default';
	/** @var $title string */
	protected $title = 'Default';
	/** @var $description string */
	protected $description = 'Default Theme';
	/** @var $version string */
	protected $version = '0.1.0';
	/** @var $author string */
	protected $author = 'WebMechanic';
	/** @var $path string */
	protected $path = '';
	/** @var $url string */
	protected $url = '';
	/**#- */

	/** @var $tmpl_url string */
	public $tmpl_url = '';

	/**
	 * @var $config JRegistry
	 * @see getConfig()
	 */
	protected $config;

	/**
	 * Used in the Template Manager to assign the parameter form.
	 * @var $jform JForm
	 */
	protected $jform;

	/**
	 * @see setChunks()
	 */
	static $chunks = array(
					'meta'			=> '',
					'module_before'	=> '<div id="{position}{name}" class="{class}">',
					'module_after'	=> '</div>',
					'unit_before'	=> '<div class="{class}">',
					'unit_after'	=> '</div>'
				);

	/**
	 * @see setCapture(), getCapture()
	 */
	static $html = array();

	/**
	 * @see setFeature(), getFeatures(), dropFeatures(), renderFeatures()
	 */
	static $features = array();

	/**
	 * Apparently a constructor...
	 */
	protected function __construct($theme_file = null)
	{
		//	spl_autoload_register(array('CustomTheme', 'autoload'));
		// Frontend: pick the default template
		if ($theme_file === null)
		{
			$tmpl = JFactory::getApplication('site')->getTemplate(true);
			$ssi  = (bool) $tmpl->params->get('ssiIncludes', 0);
			if ($ssi) {
				$this->name = basename($tmpl->params->get('ssiTheme'), '.styles');
			}
			else {
				$this->name = basename($tmpl->params->get('theme'), '.css');
			}
		}
		else if (is_string($theme_file)) {
			// Backend: explicit name given via the theme file
			list($this->name, $tmp) = explode('.', "$theme_file.");
			$tmpl = new stdClass;
			$tmpl->template = basename(WMPATH_TEMPLATE);
		}
FB::log($theme_file);
		// a fake INI file with default settings
		$this->path     = WMPATH_TEMPLATE .'/themes/'. $this->name . '.php';
		// an optional .xml file with params for the backend
		$this->form     = WMPATH_TEMPLATE .'/themes/'. $this->name . '.xml';
		$this->tmpl_url = JUri::root(true) .'/templates/'. $tmpl->template;
		$this->url      = $this->tmpl_url .'/themes';

		$this->config = new JRegistry();

		if (is_file($this->path))
		{
			// fake ini file
			$config = parse_ini_file($this->path, true);
			if ($config || count($config) > 0) {
				$this->config->loadArray($config);
			}
		}
		if (!is_file($this->form))
		{
			$this->form = false;
		}

		$this->title   = $this->config->get('title');
		$this->version = $this->config->get('version');
	}

	/**
	 * @param  string $theme  optional theme file, i.e 'foo.css', 'bar.styles'
	 * @return CustomTheme
	 */
	public static function getInstance($theme = null)
	{
		if (!self::$theme) {
			self::$theme = new self($theme);
		}
		return self::$theme;
	}

	/**
	 * @return CustomTheme
	 */
	public function build()
	{
		// does anyone know what $head['link'] is for? skipping...
		$head = JFactory::getDocument()->getHeadData();

// FB::log(self::$chunks, 'CustomTheme::build $chunks');
// FB::log(array_keys(self::$features), 'CustomTheme::build $features');

		self::$chunks['meta']['renderer.head']    = ElementRendererAbstract::getInstance('renderer.head')->build($head);
		self::$chunks['meta']['renderer.meta']    = ElementRendererAbstract::getInstance('renderer.meta')->build($head['metaTags']);

		// from an HTML perspective this is equivalent to <link>
		self::$chunks['meta']['renderer.link']    = ElementRendererAbstract::getInstance('renderer.link')->build($head['style']);
		self::$chunks['meta']['renderer.styles']  = ElementRendererAbstract::getInstance('renderer.styles')->build($head['styleSheets']);

		self::$chunks['meta']['renderer.script']  = ElementRendererAbstract::getInstance('renderer.script')->build($head['script']);
		self::$chunks['meta']['renderer.scripts'] = ElementRendererAbstract::getInstance('renderer.scripts')->build($head['scripts']);

		self::$chunks['meta']['renderer.custom']  = ElementRendererAbstract::getInstance('renderer.custom')->build($head['custom']);

// FB::log($head, 'build');

		return $this;
	}

	/**
	 * Stores a piece of runtime generated content into a named buffer.
	 *
	 * @param  string  $name     buffer name, usually a template position or a "chunk" of the theme
	 * @param  string  $content  the content to store
	 *
	 * @return CustomTheme
	 *
	 * @todo implement caching
	 */
	public function setCapture($name, $content)
	{
		$buffer = is_array($content) ? trim(implode('', $content)) : trim($content);

		if (strlen($buffer)) {
			settype(self::$html[$name], 'string');
			self::$html[$name] .= trim($buffer);
		}

		return $this;
	}

	/**
	 * @param  string  $name
	 * @param  bool    $checkonly
	 */
	public function getCapture($name, $checkonly = false)
	{
		if ($checkonly == true) {
			return isset(self::$html[$name]) ? strlen(self::$html[$name]) : 0;
		}

		if (isset(self::$html[$name])) {
			return self::$html[$name];
		}
	}

	/**
	 * @param  array  $chunks
	 * @param  bool   $replace  false
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 */
	public function setChunks(array $chunks, $replace = false)
	{
		if (count($chunks)) {
			if ($replace) {
				self::$chunks = $chunks;
			} else {
				self::$chunks = array_merge(self::$chunks, $chunks);
			}
		}
		return $this;
	}

	public function getChunks()
	{
		return self::$chunks;
	}

	public function setChunk($name, $chunk)
	{
		self::$chunks[$chunk] = $chunk;
	}

	public function getChunk($name, $affixes = null)
	{
		$chunks = array($name);
		if (is_string($affixes)) {
			$chunks = array($name, $affixes, $name .'_'. $affixes);
		}
		elseif (is_array($affixes)) {
			$chunks = array();
			foreach ($affixes as $affix) {
				$chunks[] = trim($name .'_'. $affix);
				$chunks[] = trim($name);
				$chunks[] = trim($affix);
			}
		}

		$chunks = array_unique($chunks);
		foreach ($chunks as $chunk) {
			if (isset(self::$chunks[$chunk])) {
				return self::$chunks[$chunk];
			}
		}

		return false;
	}

	/**
	 * Adds a "feature" or "widget" to the frontend theme which typically involves
	 * loading scripts and styles.
	 * Examples:
	 * - feature.msie.edge :        ElementFeatureMsie::edge()
	 * - feature.standards.json :   ElementFeatureStandards::json()
	 * - widget.fontscaler :        ElementWidgetFontscaler()
	 * - widget.styleswitch :       ElementWidgetStyleswitch()
	 *
	 * @param  string  $feature A fully qualified feature or widget identified.
	 * @param  mixed   $data    Some data or FALSE to disable a feature at runtime
	 * @return
	 *
	 * @uses CustomTheme::setFeature()
	 */
	public function setFeature($feature, $data)
	{
		if ($data === false) {
			return $this;
		}

		switch ($feature)
		{
			case 'feature.print': // print preview
			case 'feature.tp':    // template position preview
			case 'feature.l10n':  // data uri flags
			case 'feature.rtl':   // right to left scripts
				if ( isset(self::$features['core']) ) {
					self::$features[$feature]['link'] = '{tmpl.css}/core/'.$feature.'.css';
				}
				break;

			case 'feature.edit': // frontend editing
				if ( isset(self::$features['core']) ) {
					self::$features[$feature]['link'] = '{tmpl.css}/core/forms.css';
					self::$features[$feature]['link'] = '{tmpl.css}/core/edit-form.css';
				}
				break;

			default:
				self::$features[$feature] = $data;
		}

		return $this;
	}

	/**
	 * @param $name
	 * @return ElementRendererAbstract or NULL
	 */
	public function getFeature($name)
	{
		if (isset(self::$features[$name])) {
			return self::$features[$name];
		}
	}

	/**
	 * @param bool $names_only
	 * @return array
	 */
	public function getFeatures($names_only = false)
	{
		return $names_only == false ? self::$features : array_keys(self::$features);
	}

	/**
	 * @param $name
	 */
	public function dropFeature($name)
	{

	}

	/**
	 * @param string $theme
	 * @return ElementRendererAbstract or NULL
	 */
	public function loadFeatures($theme = null)
	{
		if (null == $theme) {
			$theme = $this->name;
		}
		return $this;
	}

	public function setForm(JForm $form)
	{
		// only once, please.
		if (isset($this->jform)) {
			return $this;
		}

		// grab the form.
		$this->jform = $form;

		// load "core" widgets and features
		try {
			JFactory::getLanguage()->load('tpl_features', WMPATH_TEMPLATE);
			$form->loadFile(WMPATH_ELEMENTS . '/features.xml', false);
		} catch(Exception $e) {
			if (defined('DEVELOPER_MACHINE')) {
				FB::warn($e);
			}
		}

		try {
			JFactory::getLanguage()->load('tpl_widgets', WMPATH_TEMPLATE);
			$form->loadFile(WMPATH_ELEMENTS . '/widgets.xml', false);
		} catch(Exception $e) {
			if (defined('DEVELOPER_MACHINE')) {
				FB::warn($e);
			}
		}

		if ($this->form) {
			JFactory::getLanguage()->load($this->name, WMPATH_TEMPLATE);
			$form->loadFile($this->form, false);
		}

		return $this;
	}

	/**
	 * @param      $feature
	 * @param null $data    Can be an array with a 'module' reference.
	 * @return string A rendered feature.
	 */
	public function renderFeature($feature, $data=null)
	{
		if (array_key_exists($feature, self::$features) && (false === (bool)self::$features[$feature])) {
			return $data;
		}

		if (is_array($data)) {
			if (isset($data['module'])) {
				// parse $data['module']->content, ie {fontscaler}
			}
		}

		// [feature|widget].class[.method]
		$parts = explode('.', $feature);
		try
		{
			$handler = ElementRendererAbstract::getInstance($parts[0].'.'.$parts[1], $data);
			if (isset($parts[2])) {
				$method  = $parts[2];
				self::$features[$feature] = $handler->{$method}($data);
			} else {
				self::$features[$feature] = $handler->build($data);
			}
		} catch (OutOfBoundsException $e) {
			self::$features[$feature] = $e;
		}

		return self::$features[$feature];
	}

	public function getConfig($name, $default=null)
	{
		if (null === $name) {
			return $this->config;
		}
		return $this->config->get($name, $default);
	}

	public function get($key, $default=null)
	{
		if (isset($this->$key))
		{
			return $this->$key;
		}
		return $default;
	}

	/**
	 * @return  string  JSON representation, stripped
	 */
	public function __toString()
	{
		$white_list = array('name','title','form','tmpl_url','url');
		$me = new stdClass;
		foreach (get_object_vars($this) as $prop => $val) {
			if ( in_array($prop, $white_list) ) {
				$me->{$prop} = $val;
			}
		}

		$me->config = $this->config->toObject();

		return json_encode($me);
	}

	/**
	 * @static
	 * @param $class
	 * @return void
	 */
	static protected function autoload($class)
	{
		if ($class[0] == 'J' || $class[0] == 'K') {return;}

		$parts = preg_split('/(?<=[a-z])(?=[A-Z])/x', $class);

		$parts[0] = WMPATH_TEMPLATE .'/'. strtolower($parts[0]) .'s';
		// i.e. feature, widget, renderer
		$parts[1] = strtolower($parts[1]);
		// inflection-ish
		$parts[1] .= ($parts[1] == 'renderer') ? '' : 's';
		// filename
		$parts[2] = strtolower($parts[2]) . '.php';
FB::warn($parts, "autoload($class)");
		include_once implode('/', $parts);
	}

}
