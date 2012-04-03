<?php defined('_JEXEC') or die;
/**
 * Construc2 Template Theme.
 *
 * @package     Construc2
 * @subpackage  Engine
 * @copyright   (C) 2011-2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

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
	protected $name    = 'default';
	/** @var $title string */
	protected $title = 'Default';
	/** @var $description string */
	protected $description = 'Default Theme';
	/** @var $version string */
	protected $version = '0.1.0';
	/** @var $author string */
	protected $author  = 'WebMechanic';
	/** @var $path string */
	protected $path    = '';
	/** @var $url string */
	protected $url     = '';
	/**#- */

	/** @var $tmpl_url string */
	public $tmpl_url = '';

	/**
	 * @var $config JRegistry
	 * @see getConfig()
	 */
	protected $config;

	/**
	 * @see setChunks()
	 */
	static $chunks = array('meta'=>'');

	/**
	 * @see setCapture(), getCapture()
	 */
	static $html   = array();

	/**
	 * @see setFeature(), getFeatures(), dropFeatures(), renderFeatures()
	 */
	protected $features = array();

	/**
	 * @param ConstructTemplateHelper $helper
	 */
	protected function __construct(ConstructTemplateHelper $helper)
	{
		$tmpl   = $helper->getTemplate();

		$ssi    = false;
		$theme  = null;

		$ssi = (bool) $tmpl->params->get('ssiIncludes', 0);
		if ($ssi) {
			$theme = basename($tmpl->params->get('ssiTheme'), '.styles');
		}
		else {
			$theme = basename($tmpl->params->get('theme', $tmpl->params->get('customStyleSheet')), '.css'); #FIXME remove BC param
		}
		$this->name = $theme;

		$this->path     = JPATH_THEMES .'/'. $tmpl->template .'/themes/'. $this->name . '.php';
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

		$this->title   = $this->config->get('title');
		$this->version = $this->config->get('version');

		// @see ConstructTemplateHelper::renderModules()
		$chunks = array(
					'module_before'	=> '<div id="{position}{name}" class="{class}">',
					'module_after'	=> '</div>',
					'unit_before'	=> '<div class="{class}">',
					'unit_after'	=> '</div>'
				);

		$this->setChunks($chunks);

		/** @define "JPATH_BASE/templates/construc2/elements" ElementRendererHead */
		require_once WMPATH_ELEMENTS . '/renderer/head.php';
	}

	/**
	 * @return CustomTheme
	 */
	public static function getInstance(ConstructTemplateHelper $helper)
	{
		if (!self::$theme)
		{
			self::$theme = new self($helper);
		}

		return self::$theme;
	}

	/**
	 * @return string
	 */
	public function build(JDocument $document)
	{
		// does anyone know what $head['link'] is for? skipping...
		$head = $document->getHeadData();

		self::$chunks['meta']['renderer.head']    = ElementRendererAbstract::getInstance('renderer.head')->init($head);
		self::$chunks['meta']['renderer.meta']    = ElementRendererAbstract::getInstance('renderer.meta')->build($head['metaTags']);

		// from an HTML perspective this is equivalent to <link>
		self::$chunks['meta']['renderer.link']    = ElementRendererAbstract::getInstance('renderer.link')->build($head['style']);
		self::$chunks['meta']['renderer.styles']  = ElementRendererAbstract::getInstance('renderer.styles')->build($head['styleSheets']);

		self::$chunks['meta']['renderer.script']  = ElementRendererAbstract::getInstance('renderer.script')->build($head['script']);
		self::$chunks['meta']['renderer.scripts'] = ElementRendererAbstract::getInstance('renderer.scripts')->build($head['scripts']);

		self::$chunks['meta']['renderer.custom']  = ElementRendererAbstract::getInstance('renderer.custom')->build($head['custom']);

		return implode('', self::$chunks['meta']);
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
				if ( isset($this->features['core']) ) {
					$this->features[$feature]['link'] = '{tmpl.css}/core/'.$feature.'.css';
				}
				break;

			case 'feature.edit': // frontend editing
				if ( isset($this->features['core']) ) {
					$this->features[$feature]['link'] = '{tmpl.css}/core/forms.css';
					$this->features[$feature]['link'] = '{tmpl.css}/core/edit-form.css';
				}
				break;

			default:
				$this->features[$feature] = $this->renderFeature($feature, $data);
		}

		return $this;
	}

	/**
	 * @param $name
	 * @return ElementRendererAbstract or NULL
	 */
	public function getFeature($name)
	{
		if (isset($this->features[$name])) {
			return $this->features[$name];
		}
	}

	/**
	 * @param bool $names_only
	 * @return array
	 */
	public function getFeatures($names_only = false)
	{
		return $names_only == false ? $this->features : array_keys($this->features);
	}

	/**
	 * @param $name
	 */
	protected function dropFeature($name)
	{

	}

	/**
	 * @param string $theme
	 * @return ElementRendererAbstract or NULL
	 */
	protected function loadFeatures($theme = null)
	{
		if (null == $theme) {
			$theme = $this->name;
		}
		return $this;
	}

	/**
	 * @param      $feature
	 * @param null $data
	 * @return string A rendered feature.
	 */
	public function renderFeature($feature, $data=null)
	{
		if (array_key_exists($feature, $this->features) && (false === (bool)$this->features[$feature])) {
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
			$method  = isset($parts[2]) ? $parts[2] : false;

			$handler = ElementRendererAbstract::getInstance($parts[0].'.'.$parts[1], $data);
			if ($method) {
				$this->features[$feature] = $handler->{$method}($data);
			} else {
				$this->features[$feature] = $handler->build($data);
			}

		} catch (Exception $e) {
			$this->features[$feature] = $e;
		}

		return $this->features[$feature];
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

}
