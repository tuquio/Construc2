<?php defined('_JEXEC') or die;
/**
 * Construc2 Template Theme.
 *
 * @package     Construc2
 * @subpackage  Engine
 * @copyright   (C) 2011-2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/** Register some Widget Classes */
#	JLoader::register('ContentWidgets', dirname(__FILE__) . '/widgets/content.php');
#	JLoader::register('BehaviorWidgets', dirname(__FILE__) . '/widgets/behavior.php');

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
	 * @see addFeature(), getFeatures()
	 */
	protected $features = array('core'=>false, 'ssi'=>false);

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
			$theme = basename($tmpl->params->get('customStyleSheet'), '.css');
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
			if (!$config || count($config) == 0) {
				break;
			}

			$this->config->loadArray($config);
		}

		$this->title   = $this->config->get('title');
		$this->version = $this->config->get('version');

		// @see ConstructTemplateHelper::renderModules()
		$chunks = array(
					'module_before'	=> '<div id="{position}" class="{class}">',
					'module_after'	=> '</div>',
					'unit_before'	=> '<div class="{class}">',
					'unit_after'	=> '</div>'
				);

		$this->setChunks($chunks);

		/** Document Head */
		require_once JPATH_THEMES . '/construc2/x~incubator/elements/renderer/head.php';
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

		self::$chunks['meta']['head']    = ElementRenderer::getInstance('head')->build($head);
		self::$chunks['meta']['meta']    = ElementRenderer::getInstance('meta')->build($head['metaTags']);

		// from an HTML perspective this is equivalent to <link>
		self::$chunks['meta']['link']    = ElementRenderer::getInstance('link')->build($head['style'], 'style');
		self::$chunks['meta']['styles']  = ElementRenderer::getInstance('styles')->build($head['styleSheets']);

		self::$chunks['meta']['script']  = ElementRenderer::getInstance('script')->build($head['script']);
		self::$chunks['meta']['scripts'] = ElementRenderer::getInstance('scripts')->build($head['scripts']);

		self::$chunks['meta']['custom']	 = ElementRenderer::getInstance('custom')->build($head['custom']);

		return implode(PHP_EOL, self::$chunks['meta']);
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
	 * @param  array   $options  RESERVED
	 *
	 * @return CustomTheme
	 * @see loadStaticHtml(), getStaticHtml()
	 *
	 * @todo implement caching
	 */
	public function setCapture($name, $content, $options = array())
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
	 * @param  mixed   $data    Some data or FALSE to remove feature
	 * @return
	 *
	 * @uses CustomTheme::addFeature()
	 *
	 * @todo pick URLs + attribs from settings.php [features]
	 * @todo resolve dependencies
	 */
	public function addFeature($feature, $data=null)
	{
		$feature = strtolower($feature);

		if (false === $data) {
			//#FIXME resolve dependencies
			unset($this->features[$feature]);
			return;
		}

		$css = $js = array();
		switch ($feature)
		{
			case 'core':
				// core stylesheets
				$css[] = $this->tmpl_url.'/css/core/base.css';
				$css[] = $this->tmpl_url.'/css/core/oocss.css';
				$css[] = $this->tmpl_url.'/css/core/template.css';
				break;

			case 'ssi':
				// Apache SSI based .styles and .scripts
				$css[] = $this->tmpl_url.'/css/construc2.styles?v=2';
				$css[] = $this->tmpl_url.'/themes/'. $this->name . '.styles?v=2';
				break;

			case 'rtl':
				// right to left support
				if ($this->features['core']) {
					$css[] = $this->tmpl_url . '/css/core/rtl.css';
				}
				break;

			case 'editor':
				// WYSIWYG editor styles
				if ($this->features['core']) {
					$css[] = $this->tmpl_url . '/css/core/forms.css';
					$css[] = $this->tmpl_url . '/css/core/edit-form.css';
				}
				break;

			case 'print':
				// print preview
				$css[] = $this->tmpl_url . '/css/core/print.css';
				break;

			case 'tp':
				// template position preview
				if ($this->features['core']) {
					$css[] = $this->tmpl_url . '/css/core/tp.css';
				}
				break;

			case 'css3':
				// Lea Verou's -prefix-free
				$js[] = $this->tmpl_url . '/js/prefixfree.min.js';
				break;

			case 'diagnostic':
				//#FIXME title needed, 'alternate stylesheet'
				// diagnostic stylesheet(s)
				$css[] = $this->tmpl_url . '/css/core/diagnostic.css';
				$js[] = '';
				break;

			case 'styleswitch':
				// style switcher (JS based)
				$js[] = $this->tmpl_url . '/js/switcher.min.js';
				break;

			default:
				//#FIXME resolve dependencies
				$this->features[$feature] = $data;
		}

		if ( count($css) ) {
			$this->features[$feature]['link'] = $css;
			$link = ElementRenderer::getInstance('link');
			foreach ($css as $url) {
				$link->set($url);
			}
		}

		if ( count($js) ) {
			$this->features[$feature]['script'] = $js;
			$script = ElementRenderer::getInstance('script');
			foreach ($js as $url) {
				$script->set($url);
			}
		}

		return $this;
	}

	// $type ~ style, script, html, ... ?
	public function getFeatures($type=null)
	{
		//#FIXME resolve dependencies
		return $this->features;
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
