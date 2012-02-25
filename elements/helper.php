<?php
/**
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @copyright	Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

/**
 * Proxy for the onBeforeCompileHead event because the Dispatcher only
 * allows function or class-based observers and insists on instatiating
 * the given 'class' for unknown reasons.
 */
function ConstructTemplateHelperCompileHead()
{
	ConstructTemplateHelper::$helper->beforeCompileHead();
}

function ConstructTemplateHelperCompileBody()
{
	ConstructTemplateHelper::$helper->afterCompileBody();
}

/**
 * ConstructTemplateHelper
 *
 * Helper functions for the Construc2 Template.
 * @since 1.0
 */
class ConstructTemplateHelper
{
	const MAX_MODULES  = 4;
	const MAX_COLUMNS  = 4;
	const MAX_WEBFONTS = 3;

	/** @var array List of template layout files */
	protected $layouts = array();

	protected $layoutpath;

	/** @var $tmpl JDocumentHTML template instance */
	protected $tmpl;

	/** @var $helper ConstructTemplateHelper instance of self
	 * @see getInstance() */
	public static $helper;

	protected $config = array('cdn'=>array());

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

	/**@#+
	 * Protected head and meta elemente for custom browser ressources
	* @var array
	*/
	static protected $head = array();
	/**@#- */

	/**
	 * Template Helper constructor expects the template object as its argument.
	 *
	 * @param JDocument $template an instance of JDocumentHtml for the most part
	 */
	public function __construct(JDocument $template)
	{
		$this->tmpl =& $template;
		$this->layoutpath = JPATH_THEMES .'/'. $this->tmpl->template .'/layouts';

		// fake ini file
		if (is_file(dirname(__FILE__) .'/settings.php'))
		{
			$this->config = @parse_ini_file(dirname(__FILE__) .'/settings.php', true);
		}

		// if the helper is instatiated within a template layout,
		// JDocument params are not yet available and most of this will break
		if (!$template->params) {
			return $this;
		}

		$this->addLayout('index');

		$theme = $template->params->get('customStyleSheet');
		if ((int) $theme != -1)
		{
			$theme = str_replace('.css', '', $theme);
			if (is_file(JPATH_THEMES .'/'. $this->tmpl->template .'/themes/'. $theme . '.ini'))
			{
				$overrides = @parse_ini_file(JPATH_THEMES .'/'. $this->tmpl->template .'/themes/'. $theme . '.ini', true);
				if ($overrides && isset($overrides['layouts']))
				{
					foreach ($overrides['layouts'] as $override) {
						list($basename, $scope) = explode(',', $override . ',');
						$this->addLayout($basename, $scope);
					}
				}
			}
		}

		// @see renderModules()
		$chunks = array(
			'unit_before' => '<div class="{class}">',
			'unit_after'  => '</div>'
		);
		$this->setChunks($chunks);

		// register event handler
		JDispatcher::getInstance()->register('onBeforeCompileHead', 'ConstructTemplateHelperCompileHead');
		JDispatcher::getInstance()->register('onAfterCompileBody', 'ConstructTemplateHelperCompileBody');
	}

	/**
	 *
	 * @param  JDocument $template an instance of JDocumentHtml for the most part
	 * @return ConstructTemplateHelper
	 */
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
	 * Returns true if the currentpage represents the default "homepage"
	 * of the website.
	 * @return bool
	 */
	static public function isHomePage()
	{
		static $front;
		if ($front !== null) return $front;
		$jmenu = JFactory::getApplication()->getMenu();
		$front = ( $jmenu->getActive() == $jmenu->getDefault() );
		return $front;
	}

	/**
	 * Liefert den Alias des aktiven Menüeintrag.
	 * Ist $parent true wird der Alias des aktiven "top level" Eintrags
	 * geliefert. Gibt es keinen aktiven Eintrag wird "Home" verwendet
	 * und der Kurzname der aktuellen Komponente ($option), z.B. 'search'.
	 * Dies ist der Fall bei Verwendung des Suchmoduls oder Querverweisen
	 * auf Komponenten deren Inhalt(e) nicht über ein Menü erreichbar sind
	 * (inkl. Kontakte).
	 *
	 * @param bool $parent Alias des Elternelements verwenden
	 * @see JApplication::getMenu() JMenu::getActive()
	 */
	static public function getPageAlias($parent = false)
	{
		static $alias = array(0=>false,1=>false);

		$parent = (int)$parent;

		if ($alias[$parent] !== false) return $alias[$parent];

		$app	= JFactory::getApplication();
		$jmenu	= $app->getMenu()->getActive();

		if (self::isHomePage()) {
			$alias[$parent] = 'home';
			return $alias[$parent];
		}

		$css = array();
		if (!$jmenu) {
			$jmenu  = $app->getMenu()->getDefault();
			$css[]	= 'home';
		}
		$option = $app->get('input')->getCmd('option');
		$css[]  = str_replace('com_', '', $option);

		if ($jmenu->type == 'component' && array_key_exists('view', $jmenu->query)) {
			$css[] = $jmenu->query['view'];

			if ($option == 'com_content' && $jmenu->query['view'] = 'category') {
				$css[] = self::_catSlug();
			}
		}

		if ($parent && $jmenu->parent_id > 1) {
			if ( ($jmenu = $app->getMenu()->getItem($jmenu->parent_id)) ) {
				if ($jmenu->type == 'component' && array_key_exists('view', $jmenu->query)) {
					$css[] = $jmenu->query['view'];
				}
			}
		}
		$css[]	= $jmenu->alias;
		$css	= array_unique($css);

		$alias[$parent] = implode(' ', $css);

		return $alias[$parent];
	}

	static protected function _catSlug()
	{
		$route = JRoute::_(ContentHelperRoute::getCategoryRoute( JRequest::getInt('id') ));
		preg_match('#/(?:\d+)\-(\w+)#', $route, $m);
		return isset($m[1]) ? $m[1] : null;
	}

	/**
	 * Registers a layout file for use with a specific component name,
	 * category id or article id.
	 *
	 * Use the second parameter $scope for fine grained overrides
	 * - 'index'     in  /layouts/            using  {$customStyle}-index.php
	 * - 'component' in  /layouts/component/  using  {$currentComponent}.php
	 * - 'category'  in  /layouts/category/   using  category-{$categoryId}.php
	 * - 'article'   in  /layouts/article/    using  article-{$articleId}.php
	 *
	 * @param string $basename required basename of the layout file (no suffix)
	 * @param string $scope    optional scope, 'component', 'category', or 'article'
	 * @return ConstructTemplateHelper for fluid interface
	 */
	public function addLayout($basename, $scope=null)
	{
		if ($scope) {
			$scope = trim($scope);
			$scope = strtolower($scope);
		}

		if ($basename == 'form') {
			return $this;
		}

		// could be either a category or article id
		if (is_numeric($basename)) {
			// default to category
			if (empty($scope)) {
				$scope = 'category';
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

		if (empty($scope) && empty($basename)) {
			return $this;
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
			case 'category':
				$basename = 'category-' . $basename;
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
	 * Determine if a layout file is available and return path or false condition
	 *
	 * Layout files in Construc2 are "overrides" for "overrides" based on the
	 * current menu item or request and allow for granular HTML markup specific
	 * for a given page.
	 *
	 * To introduce a new page layout populate you apply the conditions into
	 * a main template bootstrap, like index.php, component,php etc.
	 * (the .php suffix is added automatically).
	 * <code>
	 * $helper->addLayout('index')
	 *     ->addLayout($themeName, 'index')
	 *     ->addLayout($currentComponent, 'component')
	 *     ->addLayout($categoryId, 'category');
	 * </code>
	 * See {@link addLayout()} for more details on conditions and rules.
	 *
	 * The call the {@ink getIncludeFile()} method and load to include the
	 * filename being returned. If no file was found to match a condition, the
	 * standard "index.php" layout will load.
	 * <code>
	 * if ($alternateIndexFile = $layoutOverride->getIncludeFile()) {
	 *     include_once $alternateIndexFile;
	 *     return;
	 * 	}
	 * </code>
	 *
	 * @return string  filepath of layout or void if not found
	 *
	 * @todo implement additional magic based on the active menu item
	 */
	public function getLayout()
	{
		if (count($this->layouts) == 0) {
			return;
		}

		$jmenu	= JFactory::getApplication()->getMenu()->getActive();

		$req	= new JInput();
		$tmpl	= $req->getCmd('view');
		$layout	= $req->getCmd('layout');
		$key	= $tmpl . '.php';
		$file	= null;

		if (isset($this->layouts[$key]))
		{
			$file = $this->layouts[$key];
			$key  = $tmpl .'-'. $layout .'.php';
			if (isset($this->layouts[$key]))
			{
				$file = $this->layouts[$key];
			}
		}

		if (is_array($file) && JFile::exists($file['path']))
		{
			return $file;
		}

		foreach ($this->layouts as $file)
		{
			// return first file that exists
			if (JFile::exists($file['path'])) {
				return $file;
			}
		}
	}

	/**
	 * Returns the list of registered (and present) layout files.
	 *
	 * @return array
	 */
	public function getLayouts()
	{
		return $this->layouts;
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

		$this->groupcount[$group] = 0;

		$modules = array_fill(0, $max, 0);

		for ($i=1; $i<=$max; $i++) :
		$modules[$i] = $this->tmpl->countModules($group .'-'. $i);
		endfor;

		$i = array_sum($modules);
		if ($i == 0) {
			return null;
		}

		$this->groupcount[$group] = $modules[0] = $i;

		return $modules;
	}

	public function numModules($group) {
		return isset($this->groupcount[$group]) ? $this->groupcount[$group] : 0;
	}

	/**
	 * @param string $position
	 * @param string $style
	 * @param array  $attribs
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 */
	public function renderModules($position, $style=null, $attribs=null)
	{
		if ( !$attribs ) {
			$attribs = array();
		}
		$attribs['name'] = $position;
		($style) ? $attribs['style'] = $style : true;

		$css = array('mod');
		$prefixes = array(
				'before' => array('before'),
				'after'  => array('after')
				);
		if (isset($attribs['autocols']) && $attribs['autocols'])
		{
			$group = $position;
			$prefixes['before'][] = 'unit_before';
			$prefixes['after'][]  = 'unit_after';

			$this->getModulesCount($group);

			if ( $pos = strrpos($position, '-') ) {
				$group = substr($position, 0, $pos);
				$n = $this->numModules($group);
			} else {
				$n = $this->tmpl->countModules($group);
			}
			if ( $n > 0 ) {
				settype($attribs['oocss'], 'string');
				$css[] = 'unit size1of'.$n;
			}
			unset($attribs['autocols']);
		}
		$css = array_unique($css);

		foreach (JModuleHelper::getModules($position) as $_module)
		{
			$prefixes['before'][] = $_module->module;
			$prefixes['after'][]  = $_module->module;

			if ($chunk = self::getChunk('module', $prefixes['before']) ) {
				echo str_replace(
						array('{position}', '{class}'),
						array($position, implode(' ', $css)),
						$chunk
						);
			}
			echo JModuleHelper::renderModule($_module, $attribs);
			if ($chunk = self::getChunk('module', $prefixes['after']) ) {
				echo $chunk;
			}
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
	public function addLink($href, $uagent='all', $attribs=array(), $rel='stylesheet')
	{
		//	$this->tmpl->addHeadLink($href, $rel, 'rel', $attribs);
		if (!$uagent) $uagent = 'all';

		$rel = strtolower($rel);
		$attribs['rel'] = $rel;

		if (!isset($attribs['type'])) {
			if (strpos($rel, 'stylesheet') >= 0) {
				$attribs['type'] = 'text/css';
			}
		}
		krsort($attribs);

		$this->_makeRoom('links', $uagent);

		// store w/o that XML nonsense
		$href = str_replace('&amp;', '&', $href);
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
	public function addCustomTag($html, $uagent='all')
	{
		//	$this->tmpl->addCustomTag($html);
		if (!$uagent) $uagent = 'all';

		$this->_makeRoom('custom', $uagent);

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
	public function addMetaData($name, $content, $uagent='all', $http_equiv=false)
	{
		//	$this->tmpl->setMetaData($name, $content, $http_equiv);
		if (!$uagent) $uagent = 'all';

		$this->_makeRoom('meta', $uagent);

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
	public function addScript($url, $uagent='all', $attribs=array())
	{
		static $home;

		if (!isset($home)) {
			$home = JURI::root(false);
		}

		$this->_makeRoom('scripts', $uagent, array('cdn'=>array(), 'media'=>array(), 'templates'=>array(), 'scripts'=>array()));

		$url = str_replace(array($home, '&amp;'), array('/', '&'), $url);
		$location = 'scripts';

		if (strpos(" {$url}", 'http') >= 1 || strpos(" {$url}", '//') >= 1) {
			$location = 'cdn';
		}

		if (preg_match('#(media|templates)/system/#', $url, $match)) {
			$location = $match[1];
		}

		if (isset($attribs['defer']) && $attribs['defer'] == false) {
			unset($attribs['defer']);
		}
		if (isset($attribs['async']) && $attribs['async'] == false) {
			unset($attribs['async']);
		}

		// store
		self::$head["{$uagent}"]['scripts'][$location][$url] = JArrayHelper::toString($attribs);

		return $this;
	}

	/**
	 * @param string $content the script content
	 * @param mixed  $uagent
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHeadElements(), $scripts
	 */
	public function addScriptDeclaration($content, $uagent='all')
	{
		$this->_makeRoom('script', $uagent);

		// store
		self::$head["{$uagent}"]['script'][] = (is_array($content) ? implode(PHP_EOL, $content) : $content);

		return $this;
	}

	/**
	 * @param string $content
	 * @param mixed  $uagent
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHeadElements(), $style
	 */
	public function addStyle($content, $uagent='all')
	{
		$this->_makeRoom('style', $uagent);

		// store
		self::$head["{$uagent}"]['style'][] = str_replace(PHP_EOL, ' ', (is_array($content) ? implode(PHP_EOL, $content) : $content) );

		return $this;
	}
	/**@#- */

	/**
	 * Applies all supplemental, browser-specific head elements to the document,
	 * taking other items added else into Joomla's document into account.
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHead(), sortScripts()
	 */
	public function buildHead()
	{
		$groups = array('meta'=>'', 'links'=>'', 'style'=>'', 'scripts'=>'', 'script'=>'', 'custom'=>'');

		ksort(self::$head);
		$uastack = '';

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
	 * @return ConstructTemplateHelper for fluid interface
	 * @see buildHead(), sortScripts()
	 */
	public function renderHead()
	{
		$head = $this->tmpl->getHeadData();

		// scripts safe
		$head['script']['text/javascript'] = 'try {' . $head['script']['text/javascript'] . '} catch(e) { if (console) {console.log(e);} };';

		// cleanup non-standard stuff
		unset($head['metaTags']['standard']['copyright']);
		unset($head['metaTags']['standard']['rights']);
		unset($head['metaTags']['standard']['language']);
		unset($head['metaTags']['standard']['title']);

		// put back what's left
		$this->tmpl->setHeadData($head);

		return $this;
	}

	/**
	 * Attempts to order the script elements by pushing MooTools and jQuery
	 * up the stack to avoid conflicts among those libraries.
	 * Execution depends on the "Sort Styles and Scripts" (headCleanup)
	 * template parameter to be enabled.
	 *
	 * Component views, plugins and modules might use optional jQuery plugins,
	 * but "our" jQuery loaded during the templare rendering phase will come
	 * too late for plugins to bind to jQuery.fn.
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see buildHeadElements(), renderHeadElements()
	 *
	 * @static $libs array regexps to locate jquery and mootools libraries
	 * @static $CDN  array assoc array with CDN URLs and version regexps
	 *
	 * @todo move static vars and make this mess a decorator for other libs
	 * @todo handle scripts version conflicts loaded elsewhere
	 */
	public function sortScripts()
	{
		if (!$this->tmpl->params->get('headCleanup')) {
			return $this;
		}

		// matter of concerns
		static $libs = array(
					'jquery'	=> '#/(jquery\.)#',
					'mootools'	=> '#/(moo\w+[\.\-])#',
				);

		$head = $this->tmpl->getHeadData();

		// Remove MooTools if set to do so.
		// without MooTools we must drop all but core.js
		$loadModal	= (bool) $this->tmpl->params->get('loadModal');
		$loadMoo	= (bool) $this->tmpl->params->get('loadMoo', $loadModal);
		$loadJQuery	= (bool) $this->tmpl->params->get('loadjQuery');

		$moos = preg_grep('#/media/system/js(\/(?!core))#', array_keys($head['scripts']));
		if (count($moos) > 0) {
			foreach ($moos as $src) {
				unset($head['scripts'][$src]);
			}
		}

		// jQuery CDNs: http://docs.jquery.com/Downloading_jQuery#CDN_Hosted_jQuery
		static $CDN = array(
				'ajax.googleapis.com'	=> array('jquery'=>'/jquery/(\d\.\d\.\d)/', 'mootools'=>'/mootools/(\d\.\d\.\d)/'),
				'ajax.aspnetcdn.com'	=> array('jquery'=>'/jquery-(\d\.\d\.\d)' , ),
				'code.jquery.com' 		=> array('jquery'=>'/jquery-(\d\.\d\.\d)' , ),
				'cdnjs.cloudflare.com'	=> array('jquery'=>'/jquery/(\d\.\d\.\d)/', 'mootools'=>'/mootools/(\d\.\d\.\d)/'),
		);

		// @todo bother with others than jquery as well, like... mootools
		$jquery  = preg_grep($libs['jquery'], array_keys($head['scripts']));
		foreach ((array) $jquery as $src)
		{
			foreach ($CDN as $host => $lib)
			{
				if (strpos($src, $host)) {
					$this->addScript($src, 'all', $head['scripts'][$src]);
					// nuke old entry
					unset($head['scripts'][$src]);
				}
			}
		}

		// followed by media/system, templates/system
		foreach (preg_grep('#(media|templates)/system/#', array_keys($head['scripts'])) as $src)
		{
			$this->addScript($src, 'all', $head['scripts'][$src]);
			// nuke old entry
			unset($head['scripts'][$src]);
		}

		// jQuery compat
		if (count($jquery) > 0) {
			$noconflict = array('if(window.jQuery){jQuery.noConflict(); window.addEvent=function(n,f){$$=jQuery;if(n=="domready"||n=="load"){jQuery(document).ready(f);}};}');
			if ( isset($head['script']['text/javascript']) )
			{
				// replace present calls with empty functions
				if ( false !== strpos($head['script']['text/javascript'], 'jQuery.noConflict') ) {
					$head['script']['text/javascript'] = str_replace('jQuery.noConflict', 'new Function', $head['script']['text/javascript']);
				}
				$noconflict[] = trim($head['script']['text/javascript']);
			}
			$this->addScriptDeclaration($noconflict);
		}

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
			->buildHead()
			->sortScripts()
			->renderHead()
			;
	}

	public static function afterCompileBody()
	{
	}

	/**
	 * Returns the given date (or today) wrapped in HTML elements for individual
	 * formatting of each date fragent, day, month, year, via CSS.
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
	public function dateContainer($date='now', $dateformat='DATE_FORMAT_LC4', $elt='span')
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

	/**
	 * Renders a bunch of IE wrapper divs within conditional comments covering
	 * MSIE6 ($min) to MSIE9 ($max); IE10 dropped support for CC's.
	 * This method should be called TWICE: after the opening <body> and before
	 * the closing </body> tag respectively.
	 *
	 * The full blown opening output (1st call) looks like this:
	 * <xmp>
	 * <!--[if IE 6]><div class="msie ie6 ltie7 ltie8 ltie9 ltie10"><![endif]-->
	 * <!--[if IE 7]><div class="msie ie7 ltie8 ltie9 ltie10"><![endif]-->
	 * <!--[if IE 8]><div class="msie ie8 ltie9 ltie10"><![endif]-->
	 * <!--[if IE 9]><div class="msie ie9 ltie10"><![endif]-->';
	 * </xmp>
	 *
	 * @param int $min default 6, CC's start with MSIE 6
	 * @param int $max default 6, CC's end with MSIE 9
	 */
	static function msieSwatter($min=6, $max=9)
	{
		static $flap = 0;
		$flap++;
		if ($flap % 2 != 0) {
			($min < 5.0) ? $min = 5 : true;
			($max > 9.0) ? $max = 9 : true;
			$cc = array('<!-- "CSS handles" for Browser Emulators -->');
			for ($i = $min; $i <= $max; $i++)
			{
				$ltie = '';
				$l    = $i + 1;
				while ($l <= $max + 1) {
					$ltie .= ' ltie'.$l;
					$l++;
				}
				$cc[] = '<!--[if IE '.$i.']><div class="msie ie'. $i . $ltie .'"><![endif]-->';
			}
			echo implode(PHP_EOL, $cc), PHP_EOL;
		}
		if ($flap % 2 == 0) echo '<!--[if IE]></div><![endif]-->', PHP_EOL;
	}

	/**
	 * Generates CSS links for Google Webfonts and adds optional style selectors
	 * to the document head -- which IMHO is actually a pretty bad idea unless you
	 * toggle fonts like crazy on every single page. You'd better add those names
	 * to your theme stylesheet which is why the default is false.
	 *
	 * @param  ool $addSelectors Adds selectors to the document head
	 * @return ConstructTemplateHelper for fluid interface
	 */
	public function webFonts($addSelectors = false)
	{
		$params = $this->tmpl->params;

		$googleWebFont = $googleWebFontSize = $googleWebFontTargets = array();
		for ($i=1; $i <= self::MAX_WEBFONTS; $i++)
		{
			$font = $params->def('googleWebFont'.$i);
			if ($font)
			{
				$fontSize    = trim($params->def('googleWebFontSize'.$i));
				$fontTargets = trim($params->def('googleWebFontTargets'.$i));
				if (empty($fontSize) || empty($fontTargets)) {
					continue;
				}

				$this->addStyleSheet('//fonts.googleapis.com/css?family='.$font);
				if ($addSelectors) {
					// Fix Google Web Font name for CSS
					$this->addStyle(
								$fontTargets
								. '{font-family:'. str_replace(array('+',':bold',':italic'), ' ', $font) .', serif;'
								. (($fontSize>0) ? 'font-size:'.$fontSize.';' : '')
								. '}');
				}
			}
		}

		return $this;
	}

	/**
	 * Initializes the given $group array in the $head section for the $uagent
	 * (default = 'all') using the $filler data.
	 *
	 * @param  string $group
	 * @param  string $uagent
	 * @param  array  $filler
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 */
	protected function _makeRoom($group, &$uagent, $filler = array())
	{
		if (empty($uagent)) {
			$uagent = 'all';
		} else {
			$uagent = str_replace('ie ', 'IE ', strtolower($uagent));
		}

		if (!isset(self::$head["{$uagent}"])) {
			self::$head["{$uagent}"] = array();
		} else if (!isset(self::$head["{$uagent}"]['script'])) {
			self::$head["{$uagent}"][$group] = $filler;
		}

		return $this;
	}

}

