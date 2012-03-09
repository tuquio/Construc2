<?php defined('_JEXEC') or die;
/**
 * Construc2 Template Theme.
 *
 * @package     Construc2
 * @subpackage  Elements
 * @copyright   (C) 2011-2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

class CustomTheme
{
	protected $name    = 'default';
	protected $title   = 'Default';
	protected $version = '';
	protected $author  = '';

	protected $path    = '';
	protected $url     = '';

	protected $config = array(
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
	 * @staticvar array with optional html{5} chunks to be used in "static_html.php"
	 * @see setChunks()
	 */
	static $chunks = array('header', 'footer', 'aside', 'nav', 'section', 'article');

	public function __construct(ConstructTemplateHelper $helper, $options = array())
	{
		$doc = JFactory::getDocument();
		$ssi = (bool) $doc->params->get('ssiIncludes', 0);

		if ($ssi) {
			$theme = $doc->params->get('ssiTheme');
			$theme = basename($theme, '.styles');
		}
		else {
			$theme = $doc->params->get('customStyleSheet');
			$theme = basename($theme, '.css');
		}

		$this->name = $theme;

		// use "default" == 'template.css"
		if ((int) $theme == -1)
		{
			$helper->addLink('template.css');
		}

		$this->path = JPATH_THEMES .'/'. $doc->template .'/themes/'. $this->name . '.php';
		$this->url  = JUri::root(true) .'/templates/'. $doc->template .'/themes';

		if (is_file($this->path))
		{
			// fake ini file
			$config = parse_ini_file($this->path, true) + $this->config;
			if (!$config || count($config) == 0) {
				break;
			}
FB::log($config);
			foreach ($config['layouts'] as $layout)
			{
				list ($basename, $scope) = explode(',', $layout . ',');
				$helper->addLayout($basename, $scope);
			}
		}

		// @see renderModules()
		$chunks = array(
					'unit_before' => '<div class="{class}">',
					'unit_after'  => '</div>'
				);
		$this->setChunks($chunks);
	}

	public function build(ConstructTemplateHelper $helper)
	{
		if (isset($this->scripts))
		{
			foreach ($this->scripts as $key => $line)
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

				$helper->addScript($this->url .'/'. $src, $ua);
			}
		}
	}


	/**
	 * Will load the static html file names and prepare its related "chunks"
	 * for later inclusion in "static_html.php".
	 * Default chunks are: 'header', 'footer', 'aside', 'nav', 'section', 'article'.
	 * Use setChunks() to configure the list.
	 *
	 * Static HTML files are useful for prototyping a layout or to include contents
	 * that are not managed (manageable) within the CMS.
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
		foreach (self::$chunks as $chunk) {
			$path = $info['dirname'] .'/'. $info['filename'] .'-'. $chunk . '.html';
			if ( $layout[$chunk] = JFile::exists($path) ) {
				$layout[$chunk .'_path'] = $path;
			}
		}

		return array_keys(self::$html);
	}

	/**
	 * Loads an addition static html file given by its $chunk name, e.g. active
	 * html layout "ipsum.html", $chunk="header" yields to "ipsum-header.html" to
	 * be available in "static_html.php" for testing purposes.
	 *
	 * @param string $chunk "main" synonym for the main static html file
	 * @see self::$chunks, setChunks()
	 *
	 * @return string  COntent of the static HTML file (or a HTML comment if the file was not found)
	 */
	public function loadStaticHtml($chunk='main')
	{
		settype(self::$html[$chunk], 'boolean');
		if (self::$html[$chunk] == true) {
			return JFile::read(self::$html[$chunk .'_path']);
		}
		return '<!-- chunk: "'. $chunk .'" not found -->';
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
	static public function setChunks(array $chunks, $replace = false)
	{
		if (count($chunks)) {
			if ($replace) {
				self::$chunks = $chunks;
			} else {
				self::$chunks = array_merge(self::$chunks, $chunks);
			}
		}
	}

	static public function getChunk($name, $suffixes = null)
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

}
