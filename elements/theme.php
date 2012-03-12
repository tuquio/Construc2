<?php defined('_JEXEC') or die;
/**
 * Construc2 Template Theme.
 *
 * @package     Construc2
 * @subpackage  Elements
 * @copyright   (C) 2011-2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Proxy for the onBeforeCompileHead event because the Dispatcher only
 * allows function or class-based observers and insists on instatiating
 * the given 'class' for unknown reasons.
 */
function CustomThemeContentEvent($context, $item, $params, $limitstart=0)
{
	FB::log($context, __FUNCTION__);
}

class CustomTheme
{
	/**
	 * @var $helper ConstructTemplateHelper instance of self
	 * @see getInstance()
	 */
	public static $theme;

	protected $name    = 'default';
	protected $title   = 'Default';
	protected $version = '';
	protected $author  = '';

	protected $path    = '';
	protected $url     = '';

	protected $config = array(
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
	static $chunks = array('header', 'footer', 'aside', 'nav', 'section', 'article');

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

		if (is_file($this->path))
		{
			// fake ini file
			$config = parse_ini_file($this->path, true) + $this->config;
			if (!$config || count($config) == 0) {
				break;
			}

			$this->title   = $config['title'];
			$this->version = $config['version'];
		}

		// @see ConstructTemplateHelper::renderModules()
		$chunks = array(
					'unit_before' => '<div class="{class}">',
					'unit_after'  => '</div>'
				);

		$this->setChunks($chunks);
	}

	/**
	 * @return ConstructTemplateHelper
	 */
	public static function getInstance(ConstructTemplateHelper $helper)
	{
		if (!self::$theme)
		{
			self::$theme = new self($helper);

			// register event handler
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->register('onContentAfterTitle',    'CustomThemeContentEvent');
			$dispatcher->register('onContentBeforeDisplay', 'CustomThemeContentEvent');
			$dispatcher->register('onContentAfterDisplay',  'CustomThemeContentEvent');
		}

		return self::$theme;
	}

	public function build()
	{
		$helper = ConstructTemplateHelper::getInstance();

		foreach ($this->config['scripts'] as $key => $line)
		{
			list($ua, $src) = explode(',', $line);
			settype($ua, 'int');

			if (0 == $ua) {
				continue;
			}
			else if ($ua == 4) {
				$ua = 'IE';
			}
			else if ($ua >= 6 && $ua <=9) {
				$ua = 'IE '.$ua;
			}
			else {
				$ua = '';
			}

			$helper->addScript($src, $ua);
		}
	}

	/**
	 * Will load a static html file name registered for the given $layout and add
	 * its related "chunks" for later inclusion with "static_html.php".
	 *
	 * Default chunks are: 'header', 'footer', 'aside', 'nav', 'section', 'article'.
	 * Use setChunks() to configure the list.
	 *
	 * Static HTML files are useful for prototyping a layout or to include contents
	 * that are not managed (manageable) within the CMS and shares similarities with
	 * Server Side Includes, where a "master file" includes other named files given
	 * positions.
	 *
	 * @param  array  $layout
	 * @return array
	 * @see self::$chunks, loadStaticHtml(), setChunks()
	 */
	public function getStaticHtml(array &$layout)
	{
		self::$html = &$layout;
		if (self::$html['main'] = JFile::exists($layout['path'])) {
			self::$html['main_path'] = $layout['path'];
		}

		$info = pathinfo($layout['path'], PATHINFO_DIRNAME | PATHINFO_FILENAME);
		foreach (self::$html as $name) {
			$path = $info['dirname'] .'/'. $info['filename'] .'-'. $name . '.html';
			if ( $layout[$name] = JFile::exists($path) ) {
				$layout[$name .'_path'] = $path;
			}
		}

		return array_keys(self::$html);
	}

	/**
	 * Loads an addition static html file given by its name, e.g. for a selected
	 * html layout "ipsum.html" the $name="header" yields to "ipsum-header.html"
	 *
	 * @param  string  $name  aunique name where "main" is synonym for the "<themename>.html"
	 *
	 * @see self::$chunks, setChunks()
	 * @return string  COntent of the static HTML file or a HTML comment if the $name was not found
	 */
	public function loadStaticHtml($name='main')
	{
		settype(self::$html[$name], 'boolean');

		if (self::$html[$name] == true) {
			return JFile::read(self::$html[$name .'_path']);
		}

		return '<!-- fragment: "'. $name .'" not found -->';
	}

	public function setStaticHtml($name, $content)
	{
		self::$html[$name] = is_array($content) ? implode(PHP_EOL, $content) : $content;

		return $this;
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
		return isset($this->config[$name]) ? $this->config[$name] : $default;
	}

	/**
	 * @param string     $context    event originator
	 * @param object     $item       data to be deleted
	 * @param JRegistry  $params     item parameters
	 * @param int        $limitstart data offset or paginator
	 */
	static public function onContentAfterTitle($context, $item, $params, $limitstart=0)
	{
		FB::info($context, __FUNCTION__);
	}

	/**
	 * @param string     $context    event originator
	 * @param object     $item       data to be deleted
	 * @param JRegistry  $params     item parameters
	 * @param int        $limitstart data offset or paginator
	 */
	static public function onContentBeforeDisplay($context, $item, $params, $limitstart=0)
	{
		FB::info($context, __FUNCTION__);
	}

	/**
	 * @param string     $context    event originator
	 * @param object     $item       data to be deleted
	 * @param JRegistry  $params     item parameters
	 * @param int        $limitstart data offset or paginator
	 */
	static public function onContentAfterDisplay($context, $item, $params, $limitstart=0)
	{
		FB::info($context, __FUNCTION__);
	}

}
