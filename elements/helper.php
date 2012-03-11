<?php defined('_JEXEC') or die;
/**
 * ConstructTemplateHelper
 * Helper functions for the Construc2 Template.
 *
 * @package     Construc2
 * @subpackage  Elements
 * @copyright	WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @author		(C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/* SearchHelper knows about the (enhanced) stop words list in xx_XXLocalise
 * and is misused to clean the alias for use as a class name of list items */
JLoader::register('SearchHelper', JPATH_ADMINISTRATOR .'/components/com_search/helpers/search.php');

/** Register the CustomTheme Class */
JLoader::register('CustomTheme', dirname(__FILE__) . '/theme.php');

/**
 * Proxy for the onBeforeCompileHead event because the Dispatcher only
 * allows function or class-based observers and insists on instatiating
 * the given 'class' for unknown reasons.
 */
function ConstructTemplateHelperCompileHead()
{
	ConstructTemplateHelper::getInstance()->beforeCompileHead();
}

function ConstructTemplateHelperCompileBody()
{
	ConstructTemplateHelper::getInstance()->afterCompileBody();
}

/**
 * ConstructTemplateHelper
 *
 * Helper functions for the Construc2 Template.
 * @since 1.0
 */
class ConstructTemplateHelper
{
	const NAME         = 'Construc2';

	const MAX_MODULES  = 4;
	const MAX_COLUMNS  = 4;
	const MAX_WEBFONTS = 3;
	const UA = 'ALL';

	/** @var array List of template layout files */
	protected $layouts = array();

	protected $layoutpath;
	protected $themespath;

	/** @var $tmpl JDocumentHTML template instance */
	protected $tmpl;

	protected $edit_mode = false;

	/**
	 * @var $helper ConstructTemplateHelper instance of self
	 * @see getInstance()
	 */
	public static $helper;

	protected $config = array('cdn'=>array('@default'=>''));

	protected $theme = null;

	/**@#+
	 * Protected head and meta elemente for custom browser ressources
	* @var array
	*/
	static protected $head = array();
	/**@#- */

	/**
	 * Use {@link getInstance()} to instantiate.
	 */
	protected function __construct()
	{
		$this->tmpl = JFactory::getDocument();

		// remove this nonsense
		$this->tmpl->setTab('');

		$this->layoutpath = JPATH_THEMES .'/'. $this->tmpl->template .'/layouts';

		// fake ini file
		if (is_file(dirname(__FILE__) .'/settings.php')) {
			$this->config = parse_ini_file(dirname(__FILE__) .'/settings.php', true);
		}

		// some edit form requested?
		// - needs refinement and maybe some config to enforce it
		$request  = new JInput();
		$this->edit_mode = in_array($request->get('layout'), array('edit','form'))
						|| in_array($request->get('view'), array('form'))
						|| in_array($request->get('option'), array('com_media'))
						;

		$this->addLayout('index')
			->addLayout('component')
			->addLayout('modal');

		// register event handler
		JDispatcher::getInstance()->register('onBeforeCompileHead', 'ConstructTemplateHelperCompileHead');
		JDispatcher::getInstance()->register('onAfterCompileBody', 'ConstructTemplateHelperCompileBody');
	}

	/**
	 * @return ConstructTemplateHelper
	 */
	public static function getInstance()
	{
		if (!self::$helper) {
			self::$helper = new ConstructTemplateHelper();
		}

		if (null === self::$helper->theme) {
			if (isset(self::$helper->tmpl->params) && (self::$helper->tmpl->params instanceof JRegistry)) {
				self::$helper->theme = new CustomTheme(self::$helper);
			}
		}

		return self::$helper;
	}

	/**
	 * Returns the view name if the currentpage represents the default "homepage"
	 * of the website.
	 * @return string  View name or empty string if NOT homepage
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
	public function getPageAlias($parent = false)
	{
		static $alias = array(0=>null,1=>null);

		$parent = (int)$parent;

		if ($alias[$parent] !== null) return $alias[$parent];

		if (($v = self::isHomePage()) ) {
			$alias[$parent] = trim('home '.$v, ' 01');
			return $alias[$parent];
		}

		$app = JFactory::getApplication();
		$jmenu = $app->getMenu()->getActive();
		if (!$jmenu) {
			$jmenu	= $app->getMenu()->getDefault();
		}

		$css = $this->getCssAlias($jmenu);
		$alias[$parent] = $css;

		return $alias[$parent];
	}

	/**
	 * Attempts to create a nice alias from the $item to use in the class
	 * attribute to apply item and category based styles.
	 * If $item is a menu[ish] item, also includes type, view and layout.
	 * If $item is an article, parent and category aliases (if available)
	 * will be included.
	 * Category and item IDs appear as cat-N and item-N respectively.
	 *
	 * @param  object $item  with an $alias and maybe more usefull things
	 * @return string The alias
	 */
	public function getCssAlias($item)
	{
		$d = array();
		// menu item?
		if (isset($item->type)) {
			$d['t'] = $item->type;
			if (isset($item->query['option'])) {
				$d['o'] = str_replace('_', '-', $item->query['option']);
			}
			if (isset($item->query['view'])) {
				$d['v'] = $item->query['view'];
			}
			if (isset($item->query['layout'])) {
				$d['l'] = $item->query['layout'];
			}
		}

		$d['A'] = array();
		if (isset($item->parent_alias)) {
			$d['A']['pa'] = $item->parent_alias;
		}
		if (isset($item->category_alias)) {
			$d['A']['ca'] = $item->category_alias;
		}
		if (isset($item->alias)) {
			$d['A']['ia'] = $item->alias;
		}

		if ($item instanceof JCategoryNode) {
			$d['id'] = 'cid-' . $item->id;
		} else {
			if (isset($item->catid)) {
				$d['cid'] = 'cid-'.$item->catid;
			}
			$d['id'] = 'item-' . $item->id;
		}

		$alias = '';
		foreach ($d['A'] as $k => $ali)
		{
			// single word
			if (strpos($ali, '-') === false) continue;
			// short enough
			if (strlen($ali) <= 20) continue;
			// split and sanitize
			$alias = JStringNormalise::toSpaceSeparated($ali);

			$words = explode(' ', $alias);
			if (count($words) > 1) {
				$ignore = JFactory::getLanguage()->getIgnoredSearchWords();
				$ali = array_diff($words, $ignore);
				if (isset($item->language)) {
					$alias = $this->_inflectAlias($ali, $item->language);
				} else {
					$alias = $this->_inflectAlias($ali);
				}
			}
		}
		unset($d['A']);

		$alias .= ' ' . implode(' ', $d);

		return trim($alias);
	}

	static protected function _catSlug()
	{
		$route = JRoute::_(ContentHelperRoute::getCategoryRoute( JRequest::getInt('id') ));
		preg_match('#/(?:\d+)\-(\w+)#', $route, $m);
		return isset($m[1]) ? $m[1] : null;
	}

	// @todo refactor to use JStringXXX if that comes available
	protected function _inflectAlias(&$aliases, $language = null)
	{
		static $locale;

		if (!isset($locale)) {
			// need this to find the default language
			$locale = JFactory::getLanguage()->get('tag');
		}

		if (empty($language) || $language = '*') {
			$language = $locale;
		}

		settype($aliases, 'array');

		// even if it proxies, singularization is fine for this
		if (($language == 'de-DE' || $locale == 'de-DE') && method_exists('de_DELocalise', 'singularise')) {
			foreach ($aliases as $i => $alias) {
				$aliases[$i] = de_DELocalise::inflect($alias, false);
			}
		}
		// fall back for english
		elseif (($language == 'en-GB' || $locale == 'en-GB') && method_exists('en_GBLocalise', 'singularise')) {
			foreach ($aliases as $i => $alias) {
				$aliases[$i] = en_GBLocalise::inflect($alias, false);
			}
		}
		else {
			// @todo check for other xx-XXLocalise classes
			if ( method_exists('en_GBLocalise', 'singularise') ) {
				foreach ($aliases as $i => $alias) {
					$aliases[$i] = en_GBLocalise::inflect($alias, false);
				}
			}
		}

		return implode('-', $aliases);
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

		$req	= new JInput();
		$tmpl	= $req->getCmd('tmpl');
		$view	= $req->getCmd('view');
		$file	= null;

		// override view? (category)
		if (isset($this->layouts[$view.'.php']))
		{
			$file = $this->layouts[$view.'.php'];
			// or a layout? (blog, list, form)
			$layout	= $req->getCmd('layout');
			$key  = $view .'-'. $layout .'.php';
			if (isset($this->layouts[$key]))
			{
				$file = $this->layouts[$key];
			}
		}
		else if (isset($this->layouts[$tmpl .'.php'])) {
			// alternative tmpl (component, modal)
			$file = $this->layouts[$tmpl .'.php'];
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

	public function numModules($group)
	{
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
		if ($this->edit_mode) {
			return $this;
		}

		if ( !$attribs ) {
			$attribs = array();
		}
		$attribs['name'] = $position;
		($style) ? $attribs['style'] = $style : true;

		$css = array();
		$prefixes = array(
				'before' => array('before'),
				'after'  => array('after')
				);

		// no layout override
		if (!isset($attribs['autocols'])) {
			$attribs['autocols'] = (bool) $this->tmpl->params->get('modOocss', 0);
		}

		if ($attribs['autocols'])
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
				settype($attribs['oocss'], 'int');
				$css[] = 'unit size1of'.$n;
			}
			unset($attribs['autocols']);
		}
		else {
			$css[] = 'mod';
		}

		$css = array_unique($css);

		foreach (JModuleHelper::getModules($position) as $_module)
		{
			$prefixes['before'][] = $_module->module;
			$prefixes['after'][]  = $_module->module;
			if ($chunk = $this->theme->getChunk('module', $prefixes['before']) ) {
				echo str_replace(
						array('{position}', '{class}'),
						array($position, implode(' ', $css)),
						$chunk
						);
			}
			echo JModuleHelper::renderModule($_module, $attribs);
			if ($chunk = $this->theme->getChunk('module', $prefixes['after']) ) {
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
	 * Most optional arguments in the JDOcument interface related to mime types
	 * have been removed and standardized because we're dealing with HTML only
	 * and mime types are limited anyway.
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
	 * Adds a <link> Element für stylesheets, feeds, favicons etc.
	 *
	 * The mime type for (alternative) styles and icons is enforced.
	 *
	 * @param string $href      the links href URL
	 * @param string $relation  link relation, e.g. "stylesheet"
	 * @param mixed  $uagent
	 * @param array  $attribs   optional attributes as associative array
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHeadElements(), $links
	 */
	public function addLink($href, $uagent=self::UA, $attribs=array(), $rel='stylesheet')
	{
		static $favicon;

		$this->_makeRoom('links', $uagent);

		$href = $this->_tuckUrl($href);
		$info = pathinfo($href);
		settype($info['extension'], 'string');

		$rel = strtolower($rel);
		$attribs['rel'] = $rel;

		// [alternate st]ylesheet
		if ($info['extension'] == 'css' || strpos($attribs['rel'], 'ylesheet') > 0) {
			$attribs['type'] = 'text/css';
			$attribs['id'] = basename($href, '.css') . '-css';
		}

		// [shortcut ]icon type="image/[x-icon|png]"
		if (strpos($attribs['rel'], 'icon') !== false) {
			if ($info['extension'] == 'ico') {
				$attribs['type'] = 'image/x-icon';
			}
			else if ($info['extension'] == 'png') {
				$attribs['type'] = 'image/png';
			}
			// a latter icon will replace a former
			if (isset($favicon)) {
				unset(self::$head["{$uagent}"]['links'][$favicon]);
			}
			$favicon = $href;
		}

		krsort($attribs);
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
	public function addCustomTag($html, $uagent=self::UA)
	{
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
	public function addMetaData($name, $content, $uagent=self::UA, $http_equiv=false)
	{
		$this->_makeRoom('meta', $uagent);

		// store
		$type = $http_equiv ? 'http-equiv' : 'name';
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
	public function addScript($url, $uagent=self::UA, $attribs=array())
	{
		$this->_makeRoom('scripts', $uagent, array('cdn'=>array(), 'media'=>array(), 'templates'=>array(), 'scripts'=>array()));

		$url = $this->_tuckUrl($url);
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
	public function addScriptDeclaration($content, $uagent=self::UA)
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
	public function addStyle($content, $uagent=self::UA)
	{
		$this->_makeRoom('style', $uagent);

		// store
		self::$head["{$uagent}"]['style'][] = str_replace(PHP_EOL, ' ', (is_array($content) ? implode(PHP_EOL, $content) : $content) );

		return $this;
	}

	/**@#- */

	/**
	 * Event handler "onBeforeCompileHead" to fix crappy head elements and
	 * standardize order. Also groups any UA-specific entries for browser
	 * emulators from Redmond to get them all into one place.
	 *
	 * @return bool true - since this is a "event handler"
	 *
	 * @uses buildHead(), sortScripts(), renderHead()
	 */
	public static function beforeCompileHead()
	{
		if (isset(self::$helper->theme)) {
			$theme = self::$helper->theme->build(self::$helper);
		}

		self::$helper->buildHead();
		self::$helper->sortScripts();
		self::$helper->renderHead();

		return true;
	}

	/**
	 * Applies all supplemental, browser-specific head elements to the document,
	 * taking other items added else into Joomla's document into account.
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHead(), sortScripts()
	 */
	public function buildHead()
	{
		$head = $this->tmpl->getHeadData();

		// flip and reorder entries
		$head['metaTags']['standard']['author'] = $head['metaTags']['standard']['rights'];

		// cleanup (non-standard) stuff
		unset($head['metaTags']['standard']['copyright']);
		unset($head['metaTags']['standard']['rights']);
		unset($head['metaTags']['standard']['title']);
		unset($head['metaTags']['standard']['description']);

		$this->addMetaData('X-UA-Compatible', 'IE=Edge,chrome=1', null, true);
		//$this->addMetaData('description', $desc);

		// Change generator tag
		$this->tmpl->setGenerator(null);
		$this->addMetaData('generator', trim($this->tmpl->params->get('setGeneratorTag', self::NAME)));

		if (!$this->edit_mode) {
			// if set, contains the version number, i.e '1.0.2'
			$cgf = $this->tmpl->params->get('loadGcf');
			if ((float)$cgf > 1.0) {
				$this->addScript('//ajax.googleapis.com/ajax/libs/chrome-frame/'. $cgf .'/CFInstall.min.js',
						'lt IE 9',
						array('defer'=>true, 'onload'=>'var e=document.createElement("DIV");if(e && CFInstall){e.id="gcf_placeholder";e.style.zIndex="9999";CFInstall.check({node:"gcf_placeholder"});}')
						);
			}
		}

		foreach ($head as $group => $stuff)
		{
			if (!is_array($stuff)) continue;

			switch ($group)
			{
				case 'metaTags':
					// let '' be but move "normal" away so it appears below <title>
					foreach ($stuff['standard'] as $key => $data) {
						$this->addMetaData($key, $data);
					}
					$head[$group]['standard'] = array();
					break;

				case 'links':
					foreach ($stuff as $key => $data) {
						$this->addLink($key, null, $data['attribs'], $data['relation']);
					}
					$head[$group] = array();
					break;

				case 'styleSheets':
					foreach ($stuff as $key => $data) {
						$this->addLink($key, null, $data);
					}
					$head[$group] = array();
					break;

				case 'style':
					foreach ($stuff as $key => $data) {
						$this->addStyle($data);
					}
					$head[$group] = array();
					break;

				case 'scripts':
					// cleanup, remove dupes, make rel. URLs
					$scripts = array();
					foreach ($stuff as $key => $data) {
						$url = parse_url($key);
						if (!isset($url['scheme'])) {
							$key = '/'.ltrim($key, '/');
						}
						$rel = str_replace(JURI::root(), '/', $key);
						$scripts[$rel] = $data;
					}
					$head[$group] = array();
					if (count($scripts)) {
						$head[$group] = $scripts;
					}
					break;

				case 'script':
					foreach ($stuff as $key => $data) {
						$this->addScriptDeclaration($data);
					}
					$head[$group] = array();
					break;
			}
		}

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
		// jQuery CDNs: http://docs.jquery.com/Downloading_jQuery#CDN_Hosted_jQuery
		static $CDN = array(
				'ajax.googleapis.com'	=> array('jquery'=>'/jquery/(\d\.\d\.\d)/', 'mootools'=>'/mootools/(\d\.\d\.\d)/'),
				'ajax.aspnetcdn.com'	=> array('jquery'=>'/jquery-(\d\.\d\.\d)' , ),
				'code.jquery.com' 		=> array('jquery'=>'/jquery-(\d\.\d\.\d)' , ),
				'cdnjs.cloudflare.com'	=> array('jquery'=>'/jquery/(\d\.\d\.\d)/', 'mootools'=>'/mootools/(\d\.\d\.\d)/'),
				);

		$head = $this->tmpl->getHeadData();

		// Remove MooTools if set to do so.
		// without MooTools we must drop all but core.js
		$loadModal	= (bool) $this->tmpl->params->get('loadModal');
		$loadMoo	= $this->tmpl->params->get('loadMoo', $loadModal);
		$loadJQuery	= $this->tmpl->params->get('loadjQuery');

		// however ...
		if ($this->edit_mode) {
			$loadMoo	= true;
			$loadJQuery	= false;
		}

		if ($loadMoo == false) {
			$moos = preg_grep('#/media/system/js(\/(?!core))#', array_keys($head['scripts']));
			if (count($moos) > 0) {
				foreach ($moos as $src) {
					unset($head['scripts'][$src]);
				}
			}
		}
		else {
			// Load the MooTools JavaScript Library
			JHtml::_('behavior.framework');
			if ($loadModal) {
				// Enable modal pop-ups
				JHtml::_('behavior.modal');
			}
		}


		if ($loadJQuery)
		{
			$loadjQuerySrc = $this->tmpl->params->get('loadjQuerySrc');
			if ($loadjQuerySrc == '@default') {
				$loadjQuerySrc = @$this->config['cdn']['@default'];
			}
			if (isset($CDN[$loadjQuerySrc])) {
				$loadjQuerySrc = '//ajax.googleapis.com/ajax/libs/jquery/'. $loadJQuery .'/jquery.min.js';
//FB::log($loadjQuerySrc, "loadJQuery $loadJQuery");
			}
		}

		// @todo bother with others than jquery as well
		$jquery  = preg_grep($libs['jquery'], array_keys($head['scripts']));
		foreach ((array) $jquery as $src) {
			foreach ($CDN as $host => $lib) {
				if (strpos($src, $host)) {
					$this->addScript($src, self::UA, $head['scripts'][$src]);
					// nuke old entry
					unset($head['scripts'][$src]);
				}
			}
		}

/*
		// followed by media/system, templates/system
		foreach (preg_grep('#(media|templates)/system/#', array_keys($head['scripts'])) as $src) {
			$this->addScript($src, self::UA, $head['scripts'][$src]);
			// nuke old entry
			unset($head['scripts'][$src]);
		}
*/
/*
		// jQuery compat
		if (count($jquery) > 0) {
			$noconflict = array('if(window.jQuery){jQuery.noConflict(); window.addEvent=function(n,f){$$=jQuery;if(n=="domready"||n=="load"){jQuery(document).ready(f);}};}');
			if ( isset($head['script']['text/javascript']) ) {
				// replace present calls with empty functions
				if ( false !== strpos($head['script']['text/javascript'], 'jQuery.noConflict') ) {
					$noconflict[] = str_replace('jQuery.noConflict', 'new Function', $head['script']['text/javascript']);
				}
			}
			$this->addScriptDeclaration($noconflict);
		}
*/
		// put everything back
		$this->tmpl->setHeadData($head);

		return $this;
	}

	/**
	 * @return ConstructTemplateHelper for fluid interface
	 * @see buildHead(), sortScripts()
	 */
	public function renderHead()
	{
		$head = $this->tmpl->getHeadData();

		$head['title' ]   = strip_tags($head['title']);
		$head['custom'][] = '<!-- Construc2 -->';

		ksort(self::$head);
		foreach (self::$head as $ua => $groups)
		{
			if ($ua != self::UA) {
				$head['custom'][] = '<!--[if '.$ua.']>';
			}

			// meta tags
			if (isset($groups['meta']) && count($groups['meta'])) {
				foreach ($groups['meta'] as $stuff) {
					$head['custom'][] =  '<meta '. $stuff . '>';
				}
			}

			// links and stylesheets
			if (isset($groups['links']) && count($groups['links'])) {
				foreach ($groups['links'] as $href => $stuff) {
					$head['custom'][] =  '<link '. $stuff . ' href="'. $href .'">';
				}
			}

			// scripts
			if (isset($groups['scripts']) && count($groups['scripts'])) {
				foreach (array('cdn', 'media', 'templates', 'scripts') as $sect) {
					if (!isset($groups['scripts'][$sect])) {
						continue;
					}
					if (count($groups['scripts'][$sect])) {
						foreach (array_keys($groups['scripts'][$sect]) as $href) {
							$head['custom'][] = '<script src="'. $href . '"></script>';
						}
					}
				}
			}

			// inline script
			if (isset($groups['script']) && count($groups['script'])) {
				$head['custom'][] = '<script type="text/javascript">';
				// scripts safe
				$head['custom'][] = 'try {';
				foreach ($groups['script'] as $stuff) {
					$head['custom'][] = $stuff;
				}
				$head['custom'][] = '} catch(e) {};';
				$head['custom'][] = '</script>';
			}

			// inline style
			if (isset($groups['style']) && count($groups['style'])) {
				$head['custom'][] = '<style type="text/css">';
				foreach ($groups['style'] as $stuff) {
				}
				$head['custom'][] = '</style>';
			}

			if ($ua != self::UA) $head['custom'][] = '<![endif]-->';
		}

		// put back what's left
		$this->tmpl->setHeadData($head);

		return $this;
	}

	public static function afterCompileBody()
	{
	}

	/**
	 * Returns the given date (or today) wrapped in HTML elements for individual
	 * formatting of each date fragent, day, month, year, via CSS.
	 *
	 * - $date  : a value JDate() considers a valid date value, 'now'|null|false result in current date
	 * - $format: supported date formater characters: l, d, F, Y or a DATE_FORMAT_XXX string
	 * - $elt   : HTML element to wrap around the date parts
	 *
	 * To set the $elt name only, but preseve (todays) default and date format use
	 * <samp>$templateHelper->dateContainer(null, null, 'kbd')</samp>
	 *
	 * @param  number  $date 	defaults to 'now' (also if null or false are provided)
	 * @param  string  $format	a Joomla date language string, default: DATE_FORMAT_LC4
	 * @param  string  $elt		defaults to 'span' as the date fragment wrapper element
	 * @return string
	 */
	public function dateContainer($date='now', $format='DATE_FORMAT_LC4', $elt='span')
	{
		// format keys for day and month numbers and names and their mapping to a $markup
		static
		$keylist = array('D'=>1,'l'=>1,'d'=>2,'j'=>2,'F'=>3,'M'=>3,'m'=>4,'n'=>4,'o'=>5,'Y'=>5,'y'=>5),
		$markup  = array(	0 => '',
							1 => '<%s class="date-day txt">%s</%s>',
							2 => '<%s class="date-day num">%s</%s>',
							3 => '<%s class="date-month txt">%s</%s>',
							4 => '<%s class="date-month num">%s</%s>',
							5 => '<%s class="date-year num">%s</%s>');

		if (!$format) $format = 'DATE_FORMAT_LC4';
		if (!$date) $date = 'now';
		$now  = new JDate($date);

		$html = array();
		$out  = JText::_($format);
		$m    = preg_split('/\W/', $out);
		if (count($m))
		{
			foreach ($m as $i => $k)
			{
				$char = "@{$k}{$i}@";
				$out = str_replace($k, $char, $out);
				if (isset($keylist[$k])) {
					$html[$char] = sprintf($markup[$keylist[$k]], $elt, $now->format($k, true, true), $elt);
				} else {
					$html[$char] = $now->format($k, true, true);
				}
			}
		}

		return str_replace(array_keys($html), array_values($html), $out);
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

				$this->addLink('//fonts.googleapis.com/css?family='.$font);
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
	 * Returns a config value.
	 *
	 * @param  string $key
	 * @param  string $default
	 * @return string
	 */
	public function getConfig($key, $default='')
	{
		if ( array_key_exists($key, $this->config) ) {
			return $this->config[$key];
		}
		return "$default";
	}

	/**
	 * Return the current theme instance.
	 *
	 * @return CustomTheme
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * Initializes the given $group array in the $head section for the $uagent
	 * (default = self::UA) using the $filler data.
	 *
	 * @param  string $group
	 * @param  string $uagent
	 * @param  array  $filler
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 */
	protected function _makeRoom($group, &$uagent, $filler=array())
	{
		settype($uagent, 'string');

		if (empty($uagent)) {
			$uagent = self::UA;
		} else {
			$uagent = str_replace('if ', '', strtolower($uagent));
			$uagent = str_replace('ie ', 'IE ', strtolower($uagent));
		}
		$uagent = strtoupper($uagent);

		if (!isset(self::$head["{$uagent}"])) {
			self::$head["{$uagent}"] = $filler;
		}

		return $this;
	}

	protected function _tuckUrl($url, $type='link')
	{
		static $root;

		if (empty($root)) {
			$root = JURI::root(true, '/');
		}
		if (strpos('{', $url) !== false) {

		}

		$data = parse_url($url);
		// make sure URLs w/o a scheme have an absolute path
		if (!isset($data['scheme'])) {
			// dealing with protocol relative URLs
			if (substr("$url /", 0, 2) == '//') {
				$uri = new JUri('tuck:' . $url);
				if ($uri->getScheme() == 'tuck') {
					$url = str_replace('tuck:', '', "{$uri}");
				}
			} else {
				$url = '/'.ltrim($url, '/');
			}
		}

		$url = str_replace($root, '/', $url);

		if ($type == 'link') {

		}

		//if ($type == 'icon') {
		//}


		return $url;
	}
}

