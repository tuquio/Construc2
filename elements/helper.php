<?php defined('_JEXEC') or die;
/**
 * Construc2 Template Main class.
 *
 * @package     Construc2
 * @subpackage  Engine
 * @copyright	(C)2012 WebMechanic. All rights reserved.
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
function ConstructHelperBeforeCompileHead()
{
	ConstructTemplateHelper::getInstance()->onBeforeCompileHead();
}

function ConstructHelperAfterRender()
{
	ConstructTemplateHelper::getInstance()->onAfterRender();
}

class ConstructWidgets
{
	static public function better($old, $new)
	{
		JHtml::unregister($old);
		JHtml::register($old, $new);
	}
}

/**
 * Construc2 Template Main class.
 * @since 1.0
 */
class ConstructTemplateHelper
{
	const NAME         = 'Construc2';
	const MAX_MODULES  = 4;
	const MAX_COLUMNS  = 4;
	const MAX_WEBFONTS = 3;
	const UA           = 'ALL';

	/** @var $layouts array List of template layout files */
	protected $layouts = array();

	/** @var $doc JDocumentHTML instance */
	protected $doc;

	/** @var $tmpl object Template name + params */
	protected $tmpl;

	/** @var $edit_mode boolean */
	protected $edit_mode = false;

	/** @var $helper ConstructTemplateHelper instance of self */
	public static $helper;

	/** @var $config array */
	protected $config = array('cdn'=>array('@default'=>''));

	/** @var $theme CustomTheme */
	protected $theme = null;

	/** @var $head array */
	static protected $head = array();

	/** @var $group_count array */
	static protected $group_count = array();

	/**
	 * Use {@link getInstance()} to instantiate.
	 */
	protected function __construct()
	{
		$this->doc  = JFactory::getDocument();
		$this->tmpl = JFactory::getApplication()->getTemplate(true);

		// remove this nonsense
		$this->doc->setTab('');

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
	}

	/**
	 * @return ConstructTemplateHelper
	 */
	public static function getInstance()
	{
		if (!self::$helper)
		{
			self::$helper = new self();

			// register as event handler (anything after onContentPrepare)
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->register('onBeforeCompileHead', 'ConstructHelperBeforeCompileHead');
			$dispatcher->register('onAfterRender', 'ConstructHelperAfterRender');
		}

		if (null === self::$helper->theme)
		{
			self::$helper->theme = CustomTheme::getInstance(self::$helper);
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
			$jmenu = $app->getMenu()->getDefault();
		}

		$css = $this->getCssAlias($jmenu, $parent);
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
	 * @param  object $item   with an $alias and maybe more useful things
	 * @param  bool   $parent travel up the tree to fetch parent item aliases
	 *
	 * @return string The alias
	 */
	public function getCssAlias($item, $parent = false)
	{
		$C = array();
		// menu item?
		if (isset($item->type) && $parent) {
			$C[] = $item->type;
			if (isset($item->query['option'])) {
				$C[] = str_replace('_', '-', $item->query['option']);
			}
			if (isset($item->query['view'])) {
				$C[] = $item->query['view'];
			}
			if (isset($item->query['layout'])) {
				$C[] = $item->query['layout'];
			}
		}

		if ($item instanceof JCategoryNode) {
			list($tmp, $C[]) = explode(':', $item->slug);
			$C[] = 'cid-' . $item->id;
		} else {
			if (isset($item->catid)) {
				$C[] = 'cid-'.$item->catid;
			}
			$C[] = 'item-' . $item->id;
		}

		$A = array();
		if (isset($item->parent_route)) {
			$A[] = substr($item->parent_route, 0, strpos($item->parent_route, '/'));
		}
		if (isset($item->parent_alias)) {
			$A[] = $item->parent_alias;
		}
		if (isset($item->category_alias)) {
			$A[] = $item->category_alias;
		}
		if (isset($item->alias)) {
			$A[] = $item->alias;
		}

		$alias = '';
		foreach ((array)$A as $k => $ali)
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
			$A[$k] = $alias;
		}

		$words = array_unique( array_merge($C, $A) );
		$alias = implode(' ', $words);

		return trim($alias);
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

		if (strpos('.', $scope, 1)) {
			list($scope, $theme) = explode('.', $scope);
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

		$layout = ltrim($scope .'/'. basename($basename, $ext) . $ext, ' /_-');

		if (isset($theme)) {
			$layoutpath = JPATH_THEMES .'/'. $this->tmpl->template .'/' . $theme;
		} else {
			$layoutpath = JPATH_THEMES .'/'. $this->tmpl->template .'/layouts/' . $layout;
		}

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
			return array();
		}

		$req	= new JInput();
		$tmpl	= $req->get('tmpl', 'index');
		$view	= $req->get('view', 'default');
		$file	= null;

		// override view? (category)
		if (isset($this->layouts[$view.'.php']))
		{
			$file = $this->layouts[$view.'.php'];
			// or a layout? (blog, list, form)
			$layout	= $req->get('layout');
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

		if (is_array($file))
		{
			if (!JFile::exists($file['path']))
			{
				$file = null;
				foreach ($this->layouts as $file)
				{
					// return first file that exists
					if (JFile::exists($file['path'])) {
						break;
					}
				}
			}
		}

		return $file;
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
	 * Counts and returns the amount of active Modules in the given position $group.
	 *
	 * @param string  $group
	 * @param integer $max   default self::MAX_MODULES
	 *
	 * @return array|null
	 * @see numModules(), renderModules()
	 * @uses JDocumentHTML::countModules();
	 * @todo  fund a more flexible way to count 'column-X' split into 'group-alpha/beta'
	 */
	public function getModulesCount($group, $max = self::MAX_MODULES)
	{
		if (isset(self::$group_count[$group])) {
			return self::$group_count[$group];
		}

		settype($max, 'int');
		// #FIXME colums are only 2 per group 'alpha' and 'beta'
		if ($group =='column') {
			$max = 4;
		}

		if ($max < 1) $max = 1;

		$modules = array_fill(0, $max, 0);

		for ($i = 1; $i <= $max; $i += 1) {
			$modules[$i] = $this->doc->countModules($group .'-'. $i);
	//		$modules[$i] = &JModuleHelper::getModules($group .'-'. $i);
		}

		$i = array_sum($modules);
		if ($i == 0) {
			return null;
		}

		$modules[0] = $i;
		self::$group_count[$group] = $modules;

		// #FIXME special treatment for alpha/beta groups if $group='column'
		if ($group =='column') {
			self::$group_count['group-alpha']	= $modules[1] + $modules[2];
			self::$group_count['column-1']		= $modules[1];
			self::$group_count['column-2']		= $modules[2];

			self::$group_count['group-beta']		= $modules[3] + $modules[4];
			self::$group_count['column-3']		= $modules[3];
			self::$group_count['column-4']		= $modules[4];
		}

		return $modules;
	}

	public function numModules($group)
	{
		return isset(self::$group_count[$group]) ? self::$group_count[$group] : 0;
	}

	/**
	 * @param string $position  Name of a module position
	 * @param string $style     The module chrome style to apply (see ./html/modules.php)
	 * @param array  $attribs   Optional attributes passed to the chrome rendering function
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 *
	 * @uses CustomTheme::setCapture()
	 */
	public function renderModules($position, $style=null, $attribs=array())
	{
		if ($this->edit_mode) {
			return $this;
		}

		$group = false;
		if ( ($pos = strrpos($position, '-')) ) {
			$group = substr($position, 0, $pos);
		}

		$attribs['name'] = $position;
		($style) ? $attribs['style'] = $style : true;

		$css = array();
		$prefixes = array(
				'before' => array('before'),
				'after'  => array('after')
				);

		// no layout override?
		if (!array_key_exists('autocols', $attribs)) {
			$attribs['autocols'] = $this->tmpl->params->get('modOocss', 0);
		}

		settype($attribs['autocols'], 'bool');

		if ($attribs['autocols'] !== false)
		{
			$prefixes['before'][] = 'unit_before';
			$prefixes['after'][]  = 'unit_after';


			if ( $group != false ) {
				$modules = $this->getModulesCount($group);
				$n = $modules[0];
			} else {
				$n = $this->doc->countModules($position);
			}

			if ( $n > 0 ) {
				$attribs['oocss'] = '';
				$css[] = 'unit size1of'.$n;
			}
			unset($attribs['autocols']);
		}
		else {
			$css[] = 'mod';
		}

		$css = array_unique($css);

		$html = array();
		foreach (JModuleHelper::getModules($position) as $module)
		{
			// find @stylename encoded in moduleclass_sfx
			$mparams = json_decode($module->params);
			if (isset($mparams->moduleclass_sfx) && strpos($mparams->moduleclass_sfx, '@') !== false)
			{
				$style = preg_grep('/^@([a-z]+)/', explode(' ', $mparams->moduleclass_sfx));
				$attribs['style'] = str_replace('@', '', implode(' ', $style));

				// put everything else back
				$mparams->moduleclass_sfx = trim(str_replace($style, '', $mparams->moduleclass_sfx));
				$module->params = json_encode($mparams);
			}

			$content = JModuleHelper::renderModule($module, $attribs);

			// this crap doesn't belong here
			$content = $this->_choppInlineCrap($content, $module->module);

			$prefixes['before'][] = $module->module;
			$prefixes['after'][]  = $module->module;
			if ($chunk = $this->theme->getChunk('module', $prefixes['before']) )
			{
				$html[] = str_replace(
							array('{position}', '{class}'),
							array($position, implode(' ', $css)),
							$chunk
							);
			}

			$html[] = $content;

			if ($chunk = $this->theme->getChunk('module', $prefixes['after']) ) {
				$html[] = $chunk;
			}
		}

		if (isset($attribs['capture']))
		{
			if ($attribs['capture'] === true) {
				$attribs['capture'] = $group;
			}
			$this->theme->setCapture($attribs['capture'], $html);
		} else {
			echo trim(implode('', $html));
		}

		return $this;
	}

	/**
	 * Proxy for {@link CustomTheme::getCapture()}.
	 *
	 * @param  string  $name
	 * @param  bool    $checkonly
	 *
	 * @uses CustomTheme::getCapture()
	 */
	public function getCapture($name, $checkonly = false)
	{
		return $this->theme->getCapture($name, $checkonly);
	}

	/**@#+
	 * Add browser specific resources, typically for MSIE in which case a
	 * conditional comment (CC) based on $uagent is added to group output.
	 *
	 * The interface is modeled after JDocument[Html] but not API compliant.
	 * Most optional arguments in the JDocument interface related to mime types
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
	 * @see renderHead()
	 */

	/**
	 * Adds a <link> Element für stylesheets, feeds, favicons etc.
	 *
	 * The mime type for (alternative) styles and icons is enforced.
	 *
	 * @param string $href      the links href URL
	 * @param mixed  $uagent
	 * @param array  $attribs   optional attributes as associative array
	 * @param string $relation  link relation, e.g. "stylesheet"
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHead(), $links
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
			unset($attribs['mime']);
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

		$attribs = array_filter($attribs);

		self::$head["{$uagent}"]['links'][$href] = JArrayHelper::toString($attribs);

		return $this;
	}

	/**
	 * @param string $html   valid html element to be placed inside <head>
	 * @param mixed  $uagent
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHead(), $custom
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
	 * @see renderHead(), $metaTags
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
	 * @param array  $attribs  optional attributes as associative array
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHead(), $script
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

		unset($attribs['src']);
		unset($attribs['mime']);
		$attribs['type'] = 'text/javascript';

		if (array_key_exists('defer', $attribs) && $attribs['defer'] == false) {
			unset($attribs['defer']);
		}
		if (array_key_exists('async', $attribs) && $attribs['async'] == false) {
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
	 * @see renderHead(), $scripts
	 */
	public function addScriptDeclaration($content, $uagent=self::UA)
	{
		$this->_makeRoom('script', $uagent);

		// flatten
		$script = (is_array($content) ? implode(PHP_EOL, $content) : $content);
		// de-XHTMLize inline <script> created by modules
		$script = str_replace(array('<![CDATA[', ']]>',"//\n"), '', $script);

		self::$head["{$uagent}"]['script'][] = $script;

		return $this;
	}

	/**
	 * @param string $content
	 * @param mixed  $uagent
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHead(), $style
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
	public function onBeforeCompileHead()
	{
		$this->buildHead();
		$this->sortScripts();
		$this->renderHead();

		return true;
	}

	/**
	 * Applies all supplemental, browser-specific head elements to the document,
	 * taking other items added else into Joomla's document into account.
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 * @see renderHead(), sortScripts()
	 *
	 * @todo move to "head renderer" class
	 */
	protected function buildHead()
	{
		$head = $this->doc->getHeadData();
		$tmpl_url = JURI::base(true) . '/templates/'. $this->tmpl->template;

		// flip and reorder entries
		$head['metaTags']['standard']['author'] = $head['metaTags']['standard']['rights'];

		// cleanup (non-standard) stuff
		unset($head['metaTags']['standard']['copyright']);
		unset($head['metaTags']['standard']['rights']);
		unset($head['metaTags']['standard']['title']);
		unset($head['metaTags']['standard']['description']);

		$this->addMetaData('X-UA-Compatible', 'IE=Edge,chrome=1', null, true);

		// Change generator tag
		$this->doc->setGenerator(null);
		$this->addMetaData('generator', trim($this->tmpl->params->get('setGeneratorTag', self::NAME)));

		// tell mobile devices to treat the viewport as being the same width as the
		// physical width of the device to make width work in media-queries as expected
		$this->addMetaData('viewport', 'width=device-width,initial-scale=1.0');

		// Google Chrome Frame
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

		// JSON shim
		$this->addScriptDeclaration('(function(W,D,src) {if (W.JSON) return;var a=D.createElement("script");var b=D.getElementsByTagName("script")[0];a.src=src;a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b);})(window,document,"'. $tmpl_url .'/js/json2.min.js");');

		// Remove MooTools if set to do so.
		$loadModal	= (bool) $this->tmpl->params->def('loadModal', 0);
		$loadMoo	= (int) $this->tmpl->params->def('loadMoo', $loadModal);

		// wrap already present noConflict() placed elsewhere
		if ((bool) $this->tmpl->params->def('loadjQuery', '')) {
			$noconflict = array();

			if ($loadMoo == 0) {
				$noconflict[] = 'if(window.jQuery){window.addEvent=function(n,f){console.log(\'addEvent \',n,f);var $$=jQuery;if(n=="domready"||n=="load"){jQuery(document).ready(f);}};}';
			}

			if (isset($head['script']['text/javascript'])) {
				// replace present calls with empty functions
				if ( false !== strpos($head['script']['text/javascript'], 'jQuery.noConflict') ) {
					$noconflict[] = str_replace('jQuery.noConflict', 'new Function', $head['script']['text/javascript']);
				}
			}
			$this->addScriptDeclaration($noconflict);
			unset($noconflict);
		}


		foreach ($head as $group => $stuff)
		{
			if (!is_array($stuff)) continue;

			switch ($group)
			{
				case 'metaTags':
					// let '' be but move "normal" away so it appears below <title>
					foreach ($stuff['standard'] as $key => $data) {
						unset($head[$group][$key]);
						if (!empty($data)) {
							$this->addMetaData($key, $data);
						}
					}
					break;

				case 'links':
					foreach ($stuff as $key => $data) {
						unset($head[$group][$key]);
						$this->addLink($key, null, $data['attribs'], $data['relation']);
					}
					break;

				case 'styleSheets':
					foreach ($stuff as $key => $data) {
						unset($head[$group][$key]);
						$this->addLink($key, null, $data);
					}
					break;

				case 'style':
					foreach ($stuff as $key => $data) {
						unset($head[$group][$key]);
						$this->addStyle($data);
					}
					break;

				case 'scripts':
					// cleanup, remove dupes, make rel. URLs
					foreach ($stuff as $key => $data) {
						if (strpos($key, '/caption') || ($loadMoo == 0)) {
							$head[$group][$key] = array('mime'=>'text/x-construc2','defer'=>true,'async'=>'');
							continue;
						}
						unset($head[$group][$key]);

						$url = parse_url($key);
						if (!isset($url['scheme'])) {
							$key = ltrim($key, '/');
						}
						$rel = str_replace(JURI::root(), '/', $key);
						$this->addScript($rel, self::UA, $data);
					}
					$head[$group] = array();
					break;

				case 'script':
					foreach ($stuff as $key => $data) {
						$head[$group][$key] = '//;';
						if (strpos($data, 'JCaption')) {
							continue;
						}
						$this->addScriptDeclaration($data);
					}
					break;
			}
		}

		// put back what's left
		$this->doc->setHeadData($head);

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
	 * @todo make this mess a decorator for other libs
	 * @todo handle scripts version conflicts loaded elsewhere
	 */
	protected function sortScripts()
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

		$head = $this->doc->getHeadData();

		// Remove MooTools if set to do so.
		$loadMoo	= (int)  $this->tmpl->params->get('loadMoo');
		$loadModal	= (bool) $this->tmpl->params->get('loadModal');
		$loadJQuery	= (bool) $this->tmpl->params->get('loadjQuery');

		// however ...
		if ($this->edit_mode)
		{
			$loadMoo = true;
			$loadJQuery	= false;
		}

		if ($loadMoo == 0)
		{
			// drop it all
			$moos = preg_grep('#media/system/js/(.*)$#', array_keys($head['scripts']));
			if (count($moos) > 0) {
				foreach ($moos as $src) {
					unset($head['scripts'][$src]);
				}
				if (count($head['scripts']) == 0) {
					$head['scripts'][JURI::root(true) . 'media/system/js/core.js'] = array('mime'=>'text/javascript','defer'=>'','async'=>'');
				}
			}
			$this->doc->setHeadData($head);
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
			}
			if ($loadjQuerySrc) {
				$attribs['onload'] = 'if(window.jQuery){jQuery.noConflict();}';
				$attribs['onreadystatechange'] = "if(elt.readyState == 'complete' && window.jQuery){jQuery.noConflict();}";
				$this->addScript($loadjQuerySrc, self::UA, $attribs);
			}
		}

		// followed by media/system, templates/system
		foreach (preg_grep('#(media|templates)/system/#', array_keys($head['scripts'])) as $src)
		{
			$this->addScript($src, self::UA, $head['scripts'][$src]);
			// nuke old entry
			unset($head['scripts'][$src]);
		}

		// put everything back
		$this->doc->setHeadData($head);

		return $this;
	}

	/**
	 * @return ConstructTemplateHelper for fluid interface
	 *
	 * @see buildHead(), sortScripts()
	 * @todo move to "head renderer" class
	 * @todo fix "IEMobile" "(IE 7)&!(IEMobile)" "(IE 8)&!(IEMobile)" "(gte IE 9)|(gt IEMobile 7)"
	 */
	protected function renderHead()
	{
		$head = $this->doc->getHeadData();

		$head['title' ]   = strip_tags($head['title']);
		$head['custom'][] = '<!-- Construc2 -->';

		ksort(self::$head);
		foreach (self::$head as $ua => $groups)
		{
			// collected crap from modules and plugins will go elsewhere
			if ($ua == 'BODY') {
				continue;
			}

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
				foreach ($groups['script'] as $stuff) {
					$head['custom'][] = 'try {'. $stuff. '} catch(e) {};';
				}
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
		$this->doc->setHeadData($head);

		return $this;
	}

	public static function onAfterRender()
	{
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
	static public function msieSwatter($min=6, $max=9)
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
	 * Generates CSS links for Google Webfonts.
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 */
	public function webFonts()
	{
		$params = $this->tmpl->params;

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

	public function getTemplate()
	{
		return $this->tmpl;
	}

	public function getLayoutpath()
	{
		return JPATH_THEMES .'/'. $this->tmpl->template .'/layouts';
	}

	public function isEditMode()
	{
		return $this->edit_mode;
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
	 *
	 * @todo fix "IEMobile" "(IE 7)&!(IEMobile)" "(IE 8)&!(IEMobile)" "(gte IE 9)|(gt IEMobile 7)"
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
			$root = JURI::root(true) . '/';
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
				$url =  '/'. ltrim($url, '/');
			}
		}

		$url = str_replace($root, '/', $url);

		if ($type == 'link') {

		}

		//if ($type == 'icon') {
		//}


		return $url;
	}

	/**
	 * Some Modules and Plugins, incl. the "Core" don't use the JDocumentHTML API
	 * to add scripts and styles to the HEAD element.
	 *
	 * @param  string  $content  Some markup
	 * @param  string  $culprit  A string that identifies the content originator
	 */
	protected function _choppInlineCrap($content, $culprit = '')
	{
		// <script></script> $script[1] = attribs $script[2] = code
		if (preg_match_all('#<script(.*)>(.*)</script>#siU', $content, $m))
		{
			for ($i = 0; $i < count($m[0]); $i += 1)
			{
				// some moron developer still uses document.write[ln]
				// instead proper DOM manipulation. Can't fix this BS!
				if (strpos($m[2][$i], 'document.write')) {
					JLog::add('Performance hog document.write() detected '.$culprit, JLog::WARNING, 'deprecated');
					continue;
				}

				// fails on "boolean" attribs w/ value ie defer async
				parse_str($m[1][$i], $arr);

				$found = false;
				if (isset($arr['src'])) {
					$this->addScript($arr['src'], 'BODY', $arr);
					$found = true;
				}
				// not type or a type pointing to a script language
				else if (!isset($arr['type']) || (isset($arr['type']) && strpos($arr['type'], 'script') )) {
					$this->addScriptDeclaration($m[2][$i], 'BODY');
					$found = true;
				}

				if ($found) {
					$content = str_replace($m[0][$i], '', $content);
				}
			}
		}

		return $content;
	}

}

