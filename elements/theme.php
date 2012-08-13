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
	/** @var $description string */
	protected $description = 'Default Theme';
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
	 * @var $xml JForm
	 * @see setForm()
	 */
	protected $xml;

	/**
	 * An optional xml file for the theme form.
	 * @var $form string
	 * @see setForm()
	 */
	protected $form;

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
	static $features = array('core'=>array('link'=>array()));

	/**
	 * Apparently a constructor...
	 *
	 * @param string|object  Theme file or template style object
	 */
	protected function __construct($theme = null)
	{
		/* spl_autoload_register(array('CustomTheme', 'autoload')); */

		// theme filename only? 'foo.css', 'bar.styles' (Backend usage)
		if (is_string($theme)) {
			list($this->name, $tmp) = explode('.', "$theme.");
			// make it a "minimal" template style object
			$theme = new stdClass;
			$theme->template = basename(WMPATH_TEMPLATE);
		}

		elseif (is_object($theme))
		{
			// plain array from 'onContentPrepareForm' if run in backend
			if (is_array($theme->params)) {
				$params = new JRegistry($theme->params);
			} else {
				$params = &$theme->params;
			}

			// a Template Style object via ConstructTemplateHelper (Frontend usage)
			$ssi  = (bool) $params->get('ssiIncludes', 0);
			if ($ssi) {
				$this->name = basename($params->get('ssiTheme'), '.styles');
			}
			else {
				$this->name = basename($params->get('theme'), '.css');
			}
		}

		$jtmpl = basename(JPATH_THEMES);
		$this->tmpl_url = JUri::root(true) ."/{$jtmpl}/". $theme->template;

		// an optional .xml file with params for the backend
		$this->form = WMPATH_TEMPLATE .'/themes/'. $this->name . '.xml';
		if (!is_file($this->form)) {
			$this->form = false;
		}

		$this->loadConfig();
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
	final public function build()
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
	final public function setCapture($name, $content)
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
	final public function getCapture($name, $checkonly = false)
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
	final public function setChunks(array $chunks, $replace = false)
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

	final public function getChunks()
	{
		return self::$chunks;
	}

	final public function setChunk($name, $chunk)
	{
		self::$chunks[$chunk] = $chunk;
	}

	final public function getChunk($name, $affixes = null)
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
	 * Adds a "feature" or "widget" to the template which typically involves
	 * loading scripts and styles.
	 *
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
	final public function setFeature($feature, $data)
	{
		if ($data === false) {
			unset(self::$features[$feature]);
			return $this;
		}

		switch ($feature)
		{
			case 'feature.print': // print preview
			case 'feature.tp':    // template position preview
			case 'feature.l10n':  // data uri flags
			case 'feature.rtl':   // right to left scripts
				list($tmp, $name) = explode('.', $feature);
				self::$features['core']['link'][$name] = '{tmpl.css}/core/'.$name.'.css';
				break;

			case 'feature.edit': // frontend editing
				self::$features['core']['link']['forms'] = '{tmpl.css}/core/forms.css';
				self::$features['core']['link']['edit-forms'] = '{tmpl.css}/core/edit-form.css';
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
	final public function getFeature($name)
	{
		if (isset(self::$features[$name])) {
			return self::$features[$name];
		}
		return null;
	}

	/**
	 * @param bool $names_only
	 * @return array
	 */
	final public function getFeatures($names_only = false)
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

	/**
	 * @param string $feature dot-notation of a loadable feature
	 * @param null   $data    Can be an array with a 'module' reference.
	 * @return string A rendered feature.
	 */
	final public function renderFeature($feature, $data=null)
	{
		// known feature but disabled explicitly
		if (array_key_exists($feature, self::$features) && (false === self::$features[$feature])) {
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
				$method = $parts[2];
				self::$features[$feature] = $handler->{$method}($data);
			} else {
				self::$features[$feature] = $handler->build($data);
			}
		} catch (OutOfBoundsException $e) {
			self::$features[$feature] = $e;
		}

		return self::$features[$feature];
	}

	/**
	 * Loads the INI data part from a Theme configuration file.
	 *
	 * See {@link ../docs/ThemeConfiguration.md} in the distribution
	 * archive about the format and rules.
	 *
	 * @return JRegistry
	 */
	final protected function loadConfig()
	{
		// a fake INI file with default settings
		$file_path = WMPATH_TEMPLATE .'/themes/'. $this->name . '.php';
		if (is_file($file_path))
		{
			// treat theme file as an INI file
			$data   = file_get_contents($file_path);
			// strip leading php code
			$start  = strpos($data, '?>', strlen('<?php'));
			$data   = trim(substr($data, ($start > 0 ? $start + 1 : 0)));
			if (function_exists('parse_ini_string')) {
				$config = parse_ini_string($data, true);
			} else {
				$data = preg_replace('/^(\s*\w+\s*=\s*)((?:(?!\s;)[^"\r\n])*?)(\s*(?:\s;.*)?)$/mx', '\1\2\3', $data);
				$data = explode("\n", preg_replace('/(\s*(?:\s;.*)?)$/mx', '', $data) );
				$config = array();
				$sec = '';
				foreach ($data as $item) {
					$a   = preg_split('/(\s*=\s*)/mx', $item);
					$a[0]= trim($a[0], '[]');
					// drop empty lines, remaining comments, and PHP "header"
					if (empty($a[0]) || preg_match('/[;\<\>\?]+/', $a[0])) continue;
					if (isset($a[1])) {
						// '=' in value, ie DSN with params, URLs with query
						if (count($a) > 2) {
							$k    = array_shift($a);
							$a[1] = implode('=', $a);
							$a[0] = $k;
						}
						$output[$sec][$a[0]] = trim($a[1], "'\"`");
					} else {
						$sec = $a[0];
						$output[$sec] = array();
					}
				}
			}
			if ($config || count($config) > 0) {
				$this->config = new JRegistry($config);
			}
		}

		return $this->config;
	}

	/**
	 * Read a configuration value.
	 *
	 * @param  string  $name    The config key or NULL to return all configuration values
	 * @param  null    $default A default value if key is not set.
	 *
	 * @return JRegistry|mixed|null
	 */
	final public function getConfig($name, $default=null)
	{
		if (null === $name) {
			return isset($this->config) ? $this->config : $default;
		}

		return isset($this->config) ? $this->config->get($name, $default) : $default;
	}

	/**
	 * Assigns the form object used in the backend theme manager, load the
	 * supplemental XML config and language files for the theme.
	 *
	 * @param  JForm $form
	 *
	 * @return CustomTheme
	 */
	final public function setForm(JForm $form)
	{
		// only once, please.
		if (isset($this->xml)) {
			return $this;
		}

		// grab the form.
		$this->xml = $form;

		// load XML configuration and language file
		if ($this->form) {
			JFormHelper::addFormPath(WMPATH_TEMPLATE . '/themes');

			$form->loadFile($this->form, false);
			JFactory::getLanguage()->load('theme_'.$this->name, WMPATH_TEMPLATE);
		}

		return $this;
	}

	/**
	 * @return  string  JSON representation, stripped
	 */
	final public function __toString()
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
	final protected static function autoload($class)
	{
		if ($class[0] == 'J' || $class[0] == 'K') {return;}

		// split Caps
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

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

}
