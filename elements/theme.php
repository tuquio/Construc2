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
	 * @staticvar array chunks from the static html file(s) *
	 * @see getStaticHtml(), loadStaticHtml()
	 */
	static $html;

	/**
	 * @see setChunks()
	 */
	static $chunks = array('meta'=>'');

	/**
	 * @see setFeature(), getFeatures(), dropFeatures(), renderFeatures()
	 */
	protected $features = array();

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

		/** Document Head */
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
	 * Will load the static html files registered for the given $layout and add
	 * their "chunks" for later inclusion and processing.
	 *
	 * Default chunks are: 'header', 'footer', 'aside', 'nav', 'section', 'article'.
	 * Use setChunks() to configure the list.
	 *
	 * Static HTML files are useful for prototyping a layout or to include contents
	 * that are not managed (manageable) within the CMS. The concept shares similarities
	 * with Server Side Includes, where a "master file" (the layout) includes other
	 * named files (chunks) to form the final content.
	 *
	 * @param  array  $layout An array with 'path' and optional 'scope' information
	 * @return array
	 *
	 * @see  setChunks(), loadStaticHtml(), setCapture()
	 * @see  ConstructTemplateHelper::addLayout()
	 * @uses self::$chunks, JFile::exists()
	 */
	public function getStaticHtml(array &$layout)
	{
		if (self::$html['main'] = JFile::exists($layout['path'])) {
			self::$html['main_path'] = $layout['path'];
		}

		$info = pathinfo($layout['path'], PATHINFO_DIRNAME | PATHINFO_FILENAME);

		// run over the list of default and assigned chunks
		foreach (self::$chunks as $name)
		{
			$path = $info['dirname'] .'/'. $info['filename'] .'-'. $name . '.html';
			if ( $layout[$name] = JFile::exists($path) ) {
				$layout[$name .'_path'] = $path;
			}
		}

		return array_keys(self::$html);
	}

	/**
	 * Loads an existing static html file from the theme's layout folder into a
	 * given buffer of the same, e.g. for the html layout "ipsum" the $name="header"
	 * yields to load "ipsum-header.html".
	 *
	 * To store (and cache) an arbitrary piece of runtime generated content use
	 * {@link setCapture()}.
	 *
	 * @param  string  $name  a unique name where "main" is synonym for the "<themename>.html"
	 *
	 * @return string  Content of the static HTML file or a HTML comment if the $name was not found
	 * @see  setCapture()
	 * @uses self::$html, JFile::read()
	 *
	 * @todo implement caching
	 */
	public function loadStaticHtml($name='main')
	{
		settype(self::$html[$name], 'boolean');

		if (self::$html[$name] == true) {
			return JFile::read(self::$html[$name .'_path']);
		}

		return $this;
	}

	/**
	 * Stores a piece of runtime generated content into a named buffer. To load an
	 * existing HTML file from disk into a butter use {@link loadStaticHtml()}.
	 *
	 * @param  string  $name     buffer name, usually a template position or a "chunk" of the theme
	 * @param  string  $content  the content to store
	 *
	 * @return CustomTheme
	 * @see loadStaticHtml(), getStaticHtml()
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
	 * Accepts an array with basename prefixes for the static html feature
	 * provided with "static_html.php". For a list of default chunk names see
	 * {@link self::$chunks}.
	 * If your current html testfile is "ipsum.html" additional files will be
	 * loaded named "ipsum-header.html", "ipsum-footer.html" etc.
	 *
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
	 * Adds a "feature" to the frontend theme which typically involves
	 * loading scripts and styles.
	 *
	 * @param  string  $feature A feature name from theme config
	 * @param  mixed   $data    Some data or FALSE to disable a feature at runtime
	 * @return
	 *
	 * @uses CustomTheme::setFeature()
	 *
	 * @todo pick URLs + attribs from settings.php [features]
	 *
	 * @todo resolve dependencies
	 */
	public function setFeature($feature, $data)
	{
#		if ($data === false) {
#			return $this;
#		}

		list($handler, $feature) = explode('.', strtolower($feature));

FB::log("$handler @ $feature:".(int)$data);

		switch ($feature)
		{
			case 'print': // print preview
			case 'tp':    // template position preview
				if ( isset($this->features['core']) ) {
					$this->features[$feature]['link'] = '{tmpl.css}/core/'.$feature.'.css';
				}
				break;

			default:
				$this->features[$feature] = $this->renderFeature($feature, $data);
		}

		return $this;
	}

	public function getFeature($name)
	{
		if (isset($this->features[$name])) {
			return $this->features[$name];
		}
	}

	public function getFeatures($names_only = false)
	{
		return $names_only == false ? $this->features : array_keys($this->features);
	}

	protected function dropFeature($name)
	{

	}

	protected function loadFeatures($theme = null)
	{
		if (null == $theme) {
			$theme = $this->name;
		}
	}

	public function renderFeature($name, $data=null)
	{
		if (isset($this->features[$name]) && (false == (bool)$this->features[$name])) {
			return $data;
		}

		if (is_array($data)) {
			if (isset($data['module'])) {
				// parse $data['module']->content, ie {fontscaler}
if (defined('DEVELOPER_MACHINE')) {
	FB::log($data, __FUNCTION__."($name) CUSTOM MODULE?");
}
			}
		}

		// [feature|widget].class[.method]
		$parts = explode('.', $name);

		try
		{
			$method  = isset($parts[2]) ? $parts[2] : false;

			$feature = ElementRendererAbstract::getInstance($parts[0].'.'.$parts[1], $data);

			if ($method) {
				$this->features[$name] = $feature->{$method}($data);
			} else {
				$this->features[$name] = $feature->render($data);
			}

		} catch (Exception $e) {
			$this->features[$name] = $e;
		}

		return $this->features[$name];
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
