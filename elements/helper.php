<?php
/**
 * @package     Templates
 * @author      Joomla Engineering http://joomlaengineering.com
 * @author		WebMechanic http://webmechanic.biz
 * @copyright   Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

/**
 * Proxy for the onBeforeCompileHead event because the Dispatcher only
 * allows function or class-based observers and insists on instatiating
 * the given 'class' for unknown reasons.
 */
function ConstructTemplateHelperCompileHead()
{
	ConstructTemplateHelper::$helper->beforeCompileHead();
}

/**
 * ConstructTemplateHelper
 *
 * Helper functions for the Construct Template Framework
 *
 * @package	   Templates
 * @subpackage Helper
 * @since 1.0
 */
class ConstructTemplateHelper
{
	const MAX_MODULES  = 6;
	const MAX_COLUMNS  = 4;
	const MAX_WEBFONTS = 3;

	/** @var array List of template layout files */
	protected $layouts = array();

	protected $layoutpath;

	/** @var JDocumentHTML $tmpl template instance */
	protected $tmpl;

	/** @var ConstructTemplateHelper instance of self
	 * @see getInstance() */
	public static $helper;

	/**
	 * @staticvar array chunks from the static html file(s) *
	 * @see getStaticHtml(), loadStaticHtml()
	 */
	static $html;

	/**
	 * @staticvar array with optional html chunks to be used in "static_html.php"
	 * @see setChunks()
	 */
	static $chunks = array('header','footer','aside','nav','section','article');

	/**@#+
	 * Protected head and meta elemente for custom browser ressources
	* @var array
	*/
	static protected $head = array();
	/**@#- */

	/**
	 * Template Helper constructor expects the template object as its argument.
	 *
	 * @param JDocument $template
	 */
	public function __construct(JDocument $template)
	{
		$this->tmpl =& $template;
		$this->layoutpath = JPATH_THEMES .'/'. $this->tmpl->template .'/layouts';

		// Get the name of the extended template override group
		$override = $template->params->get('customStyleSheet');
		if ($override != '-1') {
			$override = str_replace('.css', '', $override);
			$this->addLayout($override);
		}

		$this->addLayout('index');

		// register event handler
		JDispatcher::getInstance()->register('onBeforeCompileHead', 'ConstructTemplateHelperCompileHead');
	}

	public static function getInstance(JDocument $template)
	{
		if (!self::$helper) {
			self::$helper = new ConstructTemplateHelper($template);
		}
		return self::$helper;
	}

	public function getTemplate()
	{
		return $this->tmpl;
	}

	/**
	 * Registers a layout file for use with a specific component name,
	 * section id or article id.
	 *
	 * Use the second parameter $scope for fine grained overrides
	 * - 'index'     in  /layouts/            using  {$customStyle}-index.php
	 * - 'component' in  /layouts/component/  using  {$currentComponent}.php
	 * - 'section'   in  /layouts/section/    using  section-{$sectionId}.php
	 * - 'article'   in  /layouts/article/    using  article-{$articleId}.php
	 *
	 * @param string $basename required basename of the layout file (no suffix)
	 * @param string $scope    optional scope, 'component' oder 'section'
	 * @return ConstructTemplateHelper for fluid interface
	 */
	public function addLayout($basename, $scope=null)
	{
		if ($scope) {
			$scope = trim($scope);
			$scope = strtolower($scope);
		}

		// could be either a section or article id
		if (is_numeric($basename)) {
			// default to section
			if (empty($scope)) {
				$scope = 'section';
			}
		}
		else {
			// catch 'html-foobar.css' for /layouts/html/foobar.html
			list($_base, $_name) = preg_split('/[^\w]+/', $basename . ' ');
			if ('html' == $_base) {
				$scope    = $_base;
				$basename = $_name;
			}
		}

		$ext = '.php';
		switch ($scope) {
			case 'index':
				$scope     = '';
				$basename .= '-index';
				break;
			case 'component':
				// just to validate $scope
				break;
			case 'section':
				$basename = 'section-' . $basename;
				break;
			case 'article':
				$basename = 'article-' . $basename;
				break;
			case 'html':
				// static file for css tests
				$ext = '.html';
				break;
		}

		$layout     = ltrim($scope .'/'. basename($basename, $ext) . $ext, ' /_-');
		$layoutpath = $this->layoutpath .'/' . $layout;

		if (JFile::exists($layoutpath)) {
			$this->layouts[$layout] = array('path'=>$layoutpath, 'scope'=>$scope);
		}

		return $this;
	}

	/**
	 * getLayoutOverride
	 *
	 * determine if file is available and return path or false condition
	 *
	 * usage:
	 *
	 * 1. include Joomla File and Folder classes
	 *
	 * jimport('joomla.filesystem.file');
	 * jimport('joomla.filesystem.folder');
	 *
	 * 2. instantiate the ConstructTemplateHelper class
	 *   if (JFile::exists(dirname(__FILE__).'/library/template.php')) {
	 *     include dirname(__FILE__).'/library/template.php';
	 *   }
	 *   $helper = new ConstructTemplateHelper ($this);
	 *
	 * 3. populate the ConstructTemplateHelper property (the .php suffix is added automatically)
	 *		@see addLayout() for more details
	 *
	 *   $helper->addLayout('index')
	 *          ->addLayout($themeName, 'index')
	 *          ->addLayout($currentComponent, 'component')
	 *          ->addLayout($sectionId, 'section');
	 *
	 * 4. call the ConstructTemplateHelper getIncludeFile method
	 *
	 * 	if ($alternateIndexFile = $layoutOverride->getIncludeFile()) {
	 * 	    include_once $alternateIndexFile;
	 *      return;
	 * 	}
	 *
	 * @return string  filepath of layout or void if not found
	 *
	 * @todo do some additional magic based on the active menu item
	 */
	public function getLayout()
	{
		if (count($this->layouts) == 0) {
			return;
		}

		$jmenu = JFactory::getApplication()->getMenu()->getActive();

		foreach ($this->layouts as $layout) {
			// return first file that exists
			if (JFile::exists($layout['path'])) {
				return $layout;
			}
		}
	}

	/**
	 * Returns the list of registered (and found) layout files.
	 *
	 * @return array
	 */
	public function getLayouts()
	{
		return $this->layouts;
	}

	/**
	 * Will load the static html file names and prepare its depended "chunks"
	 * for later inclusion in "static_html.php".
	 *
	 * @param  array  $layout
	 * @return array
	 * @see loadStaticHtml(), setChunks()
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
	 * Accepts an array with basename prefixes for the static html feature provided
	 * with "static_html.php". For a list of default chunk names see {@link self::$chunks}.
	 * If your current html testfile is "ipsum.html" additional files will be loaded named
	 * "ipsum-header.html", "ipsum-footer.html" etc.
	 *
	 * @param array $chunks
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 */
	static public function setChunks(array $chunks)
	{
		if (count($chunks)) {
			self::$chunks = $chunks;
		}
		return $this;
	}

	/**
	 * Counts and returns the amount of active Modules in the given position group.
	 *
	 * @param string  $group
	 * @param integer $max default=5
	 *
	 * @return array|null
	 */
	public function getModulesCount($group, $max = ConstructTemplateHelper::MAX_MODULES)
	{
		settype($max, 'int');
		if ($max < 1) $max = 1;

		$modules = array_fill(0, $max, 0);

		for ($i=1; $i<=$max; $i++) :
		$modules[$i] = $this->tmpl->countModules($group .'-'. $i);
		endfor;

		$i = array_sum($modules);
		if ($i == 0) {
			return null;
		}

		$modules[0] = $i;

		return $modules;
	}

	/**
	 * @param string $position
	 * @param string $style
	 * @param array  $params
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 */
	public function renderModules($position, $style=null, $params=null)
	{
		if ( !$params ) {
			$params = array();
		}
		$params['name'] = $position;
		($style) ? $params['style'] = $style : true;

		foreach (JModuleHelper::getModules($position) as $_module)
		{
			echo JModuleHelper::renderModule($_module, $params);
		}
		return $this;
	}

	/**@#+
	 * Add browser specific ressources, typically for MSIE in which case a
	* conditional comment (CC) based on $uagent is added to group output.
	*
	* The interface is modeled after JDocument[Html] but not API compliant.
	* Most optional arguments related to mime types in the JDOcument interface
	* have been removed because this affects HTML documents only.
	*
	* $uagent
	*  - IE 		= any MSIE with support for CC
	*  - IE 6		= MSIE 6 only
	*  - !IE 6		= all but MSIE 6
	*  - lt IE 9	= MSIE 5 - MSIE 8
	*  - lte IE 9	= MSIE 5 - MSIE 9
	*  - gt IE 6	= MSIE 7 - MSIE 9
	*  - gte IE 9	= MSIE 9
	*	- IEMobile	= MSIE 7 - MSIE 9 on smart phones
	*
	* @see renderHeadElements()
	*/

	/**
	 * @param string $href      the links href URL
	 * @param string $relation  link relation, e.g. "stylesheet"
	 * @param mixed  $uagent
	 * @param array  $attribs   optional attributes as associative array
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHeadElements(), $links
	 */
	public function addHeadLink($href, $uagent=null, $attribs=array(), $rel='stylesheet')
	{
		$rel = strtolower($rel);
		if ( strpos($rel, 'stylesheet') !== false ) {
			$attribs['rel'] = $rel;
		}
		// make room
		if (!isset(self::$head["{$uagent}"])) self::$head["{$uagent}"] = array();
		settype(self::$head["{$uagent}"]['links'], 'array');

		// store
		self::$head["{$uagent}"]['links'][$href] = JArrayHelper::toString($attribs);

		return $this;
	}

	/**
	 * @param string $html   valid html element to be placed inside <head>
	 * @param mixed  $uagent
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHeadElements(), $custom
	 */
	public function addCustomTag($html, $uagent=null)
	{
		// make room
		if (!isset(self::$head["{$uagent}"])) self::$head["{$uagent}"] = array();
		settype(self::$head["{$uagent}"]['custom'], 'array');

		// store
		self::$head["{$uagent}"]['custom'][] = $html;

		return $this;
	}

	/**
	 * @param string $name     name attribute of the meta element
	 * @param string $content  content attribute
	 * @param mixed  $uagent
	 * @param bool   $http_equiv
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHeadElements(), $metaTags
	 */
	public function addMetaData($name, $content, $uagent=null, $http_equiv=false)
	{
		// make room
		if (!isset(self::$head["{$uagent}"])) self::$head["{$uagent}"] = array();
		settype(self::$head["{$uagent}"]['meta'], 'array');

		// store
		$type = $http_equiv ? 'http_equiv' : 'name';
		self::$head["{$uagent}"]['meta'][$name] = JArrayHelper::toString(array($type=>$name, 'content'=>$content));

		return $this;
	}

	/**
	 * @param string $url      a script URL
	 * @param mixed  $uagent
	 * @param bool   $defer    if true,adds the defer attribute
	 * @param array  $attribs  optional attributes as associative array
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHeadElements(), $script
	 */
	public function addScript($url, $uagent=null, $attribs=array())
	{
		// make room
		if (!isset(self::$head["{$uagent}"])) self::$head["{$uagent}"] = array();
		settype(self::$head["{$uagent}"]['scripts'], 'array');

		// store
		self::$head["{$uagent}"]['scripts'][$url] = JArrayHelper::toString($attribs);

		return $this;
	}

	/**
	 * @param string $content the script content
	 * @param mixed  $uagent
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHeadElements(), $scripts
	 */
	public function addScriptDeclaration($content, $uagent=null)
	{
		// make room
		if (!isset(self::$head["{$uagent}"])) self::$head["{$uagent}"] = array();
		settype(self::$head["{$uagent}"]['script'], 'array');

		// store
		self::$head["{$uagent}"]['script'][] = is_array($content) ? implode(PHP_EOL,$content) : $content;

		return $this;
	}

	/**
	 * @param string $content
	 * @param mixed  $uagent
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHeadElements(), $style
	 */
	public function addStyleDeclaration($content, $uagent=null)
	{
		// make room
		if (!isset(self::$head["{$uagent}"])) self::$head["{$uagent}"] = array();
		settype(self::$head["{$uagent}"]['style'], 'array');

		// store
		self::$head["{$uagent}"]['style'][] = str_replace(PHP_EOL, ' ', (is_array($content) ? implode(PHP_EOL,$content) : $content) );

		return $this;
	}
	/**@#- */

	/**
	 * Applies all supplemental, browser-specific head elements to the document.
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHeadElements(), sortScripts()
	 */
	public function buildHeadElements()
	{
		$groups = array('meta'=>'', 'links'=>'', 'style'=>'', 'scripts'=>'', 'script'=>'', 'custom'=>'');

		FB::group(__FUNCTION__, array('Collapsed'=>true, 'Color'=>'teal'));

		ksort(self::$head);
		$uastack = '';

		FB::log(self::$head, __FUNCTION__);

		foreach (self::$head as $ua => $stuff)
		{
			$ua = trim($ua);
			foreach (array_keys($groups) as $group)
			{

			}
		}

		return $this;
	}

	/**
	 * @see buildHeadElements(), sortScripts()
	 */
	public function renderHeadElements()
	{
		$head   = $this->tmpl->getHeadData();

		// cleanup non-standard stuff
		if (!empty($head['metaTags']['standard']['rights']))
		{
			$head['metaTags']['standard']['copyright'] = $head['metaTags']['standard']['rights'];
		}

		unset($head['metaTags']['standard']['rights']);
		unset($head['metaTags']['standard']['language']);
		unset($head['metaTags']['standard']['title']);

		// put everything back
		$this->tmpl->setHeadData($head);

		FB::log($head, __FUNCTION__);
		FB::groupEnd();
	}

	/**
	 * Attempts to order the script elements by pushing MooTools and jQuery
	 * up the stack to avoid conflicts among those libraries.
	 *
	 * Component views, plugins and modules might use optional jQuery plugins,
	 * but "our" jQuery loaded during the templare rendering phase will come
	 * too late for plugins to bind to jQuery.fn.
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see buildHeadElements(), renderHeadElements()
	 *
	 * @todo make this mess a decorator for other libs
	 * @todo handle scripts version conflicts loaded elsewhere
	 */
	public function sortScripts()
	{
		// matter of concerns
		static $libs = array(
					'jquery'	=> '#/(jquery\.)#',
					'mootools'	=> '#/(mootools\.)#',
		);

		// jQuery CDNs: http://docs.jquery.com/Downloading_jQuery#CDN_Hosted_jQuery
		static $CDN = array(
				'ajax.googleapis.com'	=> array('jquery'=>'/jquery/(\d\.\d\.\d)/', 'mootools'=>'/mootools/(\d\.\d\.\d)/'),
				'ajax.aspnetcdn.com'	=> array('jquery'=>'/jquery-(\d\.\d\.\d)' , ),
				'code.jquery.com' 		=> array('jquery'=>'/jquery-(\d\.\d\.\d)' , ),
				'cdnjs.cloudflare.com'	=> array('jquery'=>'/jquery/(\d\.\d\.\d)/', 'mootools'=>'/mootools/(\d\.\d\.\d)/'),
		);

		$head    = $this->tmpl->getHeadData();

		// CDNs take precendence
		$scripts = array('cdn'=>array(), 'system'=>array(), 'media'=>array(), 'template'=>array());

		// @todo bother with others than jquery as well, like... mootools
		$loaded  = preg_grep($libs['jquery'], array_keys($head['scripts']));
		foreach ((array) $loaded as $src)
		{
			foreach ($CDN as $host => $lib)
			{
				if (strpos($src, $host)) {
					$scripts['cdn'][$src] = $head['scripts'][$src];
					// nuke old entry
					unset($head['scripts'][$src]);
				}
			}
		}

		if (count($loaded) > 0) {
			$this->addScriptDeclaration('if (window.jQuery){jQuery.noConflict();}');
		}

		$head['scripts'] = $scripts['cdn']
		+ $scripts['system']
		+ $scripts['media']
		+ $scripts['template']
		+ $head['scripts'];

		// put everything back
		$this->tmpl->setHeadData($head);

		return $this;
	}

	/**
	 * Event handler "onBeforeCompileHead" to fix-up crappy head elements.
	 */
	public static function beforeCompileHead()
	{
		self::$helper
		->buildHeadElements()
		->sortScripts()
		->renderHeadElements()
		;
	}

	/**
	 * Returns the given date (or today) wrapped in HTML elements for individual formatting of
	 * each date fragent, day, month, year, via CSS.
	 *
	 * - $dateformat: supported date formater characters: l, d, F, Y or a DATE_FORMAT_XXX string
	 * - $date: a value JDate() considers a valid date value, 'now'|null|false result in current date
	 * - $elt: HTML element to wrap around the date parts
	 * To set the $elt only, but preseve (todays) defaults use
	 * <samp>$layoutOverride->dateContainer(null,null,'kbd')</samp>
	 *
	 * @param  number  $date 		defaults to 'now' (also if null or false are provided)
	 * @param  string  $dateformat	a Joomla date language string, default: DATE_FORMAT_LC3
	 * @param  string  $elt			defaults to 'span' as the date fragment wrapper element
	 * @return string
	 */
	public function dateContainer($date='now', $dateformat='DATE_FORMAT_LC3', $elt='span')
	{
		if (!$dateformat) $dateformat = 'DATE_FORMAT_LC3';
		if (!$date) $date = 'now';
		$now = new JDate($date);
		$placeholder = array(
					'/l([\.,\-])?/'=>'<@X@ class="date-weekday">'. $now->format('l') .'$1</@X@>',
					'/d([\.,\-])?/'=>'<@X@ class="date-month">'. $now->format('d') .'$1</@X@>',
					'/F([\.,\-])?/'=>'<@X@ class="date-day">'. $now->format('F') .'$1</@X@>',
					'/Y([\.,\-])?/'=>'<@X@ class="date-year">'. $now->format('Y') .'$1</@X@>',
		);

		$html = preg_replace(array_keys($placeholder), array_values($placeholder), JText::_($dateformat));
		return str_replace('@X@', $elt, $html);
	}

}
// JLoader would look for this; not workin' tho
// class ElementsHelper extends ConstructTemplateHelper {}
