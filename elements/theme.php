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
	/** @var $name string */
	protected $name    = 'default';
	/** @var $title string */
	protected $title   = 'Default';
	/** @var $version string */
	protected $version = '0.1.0';
	/** @var $author string */
	protected $author  = 'WebMechanic';
	/** @var $path string */
	protected $path    = '';
	/** @var $url string */
	protected $url     = '';
	/**#- */

	/**
	 * @var $config JObject
	 * @see getConfig()
	 */
	protected $config;

	/**
	 * @ignore
	 * @var array defaults
	 */
	protected $_defaults = array(
				'title'=>'Default',
				'version'=>'',
				'layouts'=>array(),
				'cdn'=>array(),
				'scripts'=>array(),
				'nuke_scripts'=>array(),
				'nuke_styles'=>array(),
				'styleswitcher'=>array(),
				'fontscaler'=>array(),
				);

	/**
	 * @staticvar array chunks from the static html file(s) *
	 * @see getStaticHtml(), loadStaticHtml()
	 */
	static $html;

	/**
	 * @see setChunks()
	 */
	static $chunks = array('meta', 'header', 'footer', 'aside', 'nav', 'section', 'article');

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

		// use "default" == 'template.css"
		if ((int) $theme == -1) {
			$helper->addLink('template.css');
		}

		$this->path = JPATH_THEMES .'/'. $tmpl->template .'/themes/'. $this->name . '.php';
		$this->url  = JUri::root(true) .'/templates/'. $tmpl->template .'/themes';

		$this->config = new JObject($this->_defaults);

		if (is_file($this->path))
		{
			// fake ini file
			$config = parse_ini_file($this->path, true);
			if (!$config || count($config) == 0) {
				break;
			}

			$this->title   = $config['title'];
			$this->version = $config['version'];

			$this->config->setProperties($config);
		}

		// @see ConstructTemplateHelper::renderModules()
		$chunks = array(
					'module_before'	=> '<div id="{position}" class="{class}">',
					'module_after'	=> '</div>',
					'unit_before'	=> '<div class="{class}">',
					'unit_after'	=> '</div>'
				);

		$this->setChunks($chunks);
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
	 * @return CustomTheme
	 */
	public function build(JDocument $document)
	{
		$head = $document->getHeadData();

		self::$chunks['meta'] = !defined('DEVELOPER_MACHINE') ? '' : '<!-- Construc2 -->';

		if (count($head['metaTags']))
		{
			self::$chunks['meta'] .= ElementRenderer::getInstance('meta')->build($head);
		}

		// stub for IE getting onhalt if first CSS is loaded via CC
		// @link http://webforscher.wordpress.com/2010/05/20/ie-6-slowing-down-ie-8/
		self::$chunks['meta'] .= PHP_EOL.'<!--[if IE]><![endif]-->';

		if (count($head['link']))
		{
			self::$chunks['meta'] .= ElementRenderer::getInstance('link')->build($head, 'link');
		}

		if (count($head['style']))
		{	// from an HTML perspective this is equivalent to <link>
			self::$chunks['meta'] .= ElementRenderer::getInstance('link')->build($head, 'style');
		}

		if (count($head['styleSheets']))
		{
			self::$chunks['meta'] .= ElementRenderer::getInstance('styles')->build($head);
		}

		if (count($head['script']))
		{
			self::$chunks['meta'] .= ElementRenderer::getInstance('script')->build($head);
		}

		if (count($head['scripts']))
		{
			self::$chunks['meta'] .= ElementRenderer::getInstance('scripts')->build($head);
		}

		if (count($head['custom']))
		{
			self::$chunks['meta'] .= ElementRenderer::getInstance('custom')->build($head);
		}

		if (defined('DEVELOPER_MACHINE')) {
			self::$chunks['meta'] .= PHP_EOL.'<!-- /Construc2 -->';;
		}

		return $this;
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

	public function getChunk($name, $suffixes = null)
	{
		$chunks = array($name);
		if (is_string($suffixes)) {
			$chunks = array($name, $suffixes, $name .' '. $suffixes);
		}
		elseif (is_array($suffixes)) {
			$chunks = array();
			foreach ($suffixes as $suffix) {
				$chunks[] = trim($name .'_'. $suffix);
				$chunks[] = trim($name);
				$chunks[] = trim($suffix);
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

	public function getConfig($name, $default=null)
	{
		return $this->config->get($name, $default);
	}

}
