<?php defined('_JEXEC') or die;
/**
 * Construc2 Template Main class.
 *
 * @package     Construc2
 * @subpackage  Engine
 * @copyright	(C)2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
!defined('WMPATH_TEMPLATE') && define('WMPATH_TEMPLATE', dirname(dirname(__FILE__)));

require_once WMPATH_TEMPLATE . '/elements/renderer/abstract.php';
require_once WMPATH_TEMPLATE . '/elements/renderer/head.php';
JLoader::register('CustomTheme', WMPATH_TEMPLATE . '/elements/theme.php');

/* SearchHelper knows about the (enhanced) stop words list in xx_XXLocalise
 * and is misused to clean the alias for use as a class name of list items */
JLoader::register('SearchHelper', JPATH_ADMINISTRATOR .'/components/com_search/helpers/search.php');

/**
 * Construc2 Template Main class.
 * @since 1.0
 */
class ConstructTemplateHelper
{
	const MAX_MODULES  = 4;
	const MAX_COLUMNS  = 4;
	const UA           = 'ALL';

	/** @var $layouts array List of template layout files */
	protected $layouts = array();

	/** @var $doc JDocumentHTML instance */
	protected $doc;

	/** @var $tmpl object Template name + params */
	protected $tmpl;

	/**
	 * @var $states a list of request states
	 * @see hasState()
	 */
	static $states = array('debug'=>null,'edit'=>null,'print'=>null,'modal'=>null);

	/** @var $helper ConstructTemplateHelper instance of self */
	public static $helper;

	/** @var $config array */
	protected $config = array('cdn'=>array('@default'=>''), 'subst'=>array());

	/** @var $theme CustomTheme */
	public $theme;

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
		$this->getTemplate();

		// remove this nonsense
		$this->doc->setTab('');

		$this->loadConfig();

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
		}

		if (null === self::$helper->theme)
		{
			self::$helper->theme = CustomTheme::getInstance($this->tmpl);
			self::$helper->_applySubst('theme', self::$helper->theme->getName());
		}

		return self::$helper;
	}

	/**
	 * Returns the view name if the current page represents the
	 * default "homepage" of the website.
	 *
	 * @return string  View name or empty string if NOT homepage
	 * @static bool $front
	 */
	static public function isHomePage()
	{
		static $front = null;

		if ($front !== null) return $front;

		$jmenu = JFactory::getApplication()->getMenu();
		$front = ( $jmenu->getActive() == $jmenu->getDefault(JFactory::getLanguage()->getTag()) );
		if ($front) {
			$front = $jmenu->getActive()->query['view'];
		}

		return $front;
	}

	/**
	 * @param  bool  $parent  put parent alias into the mix
	 * @see JApplication::getMenu() JMenu::getActive()
	 */
	public function getPageAlias($parent = false)
	{
		static $alias = array(0=>null,1=>null);

		$parent = (int)$parent;

		if ($alias[$parent] !== null) return $alias[$parent];

		if ( ($v = self::isHomePage()) ) {
			$alias[$parent] = trim('home '.$v);
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
	 * If $item is an article its parent and category aliases (if available)
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
		$C = array('');
		// menu item?
		//	$C[] = $item->type;
		if (isset($item->query) && $parent) {
			if (isset($item->query['option'])) {
				$C[key($C)] .= substr($item->query['option'], strpos($item->query['option'], '_')+1);
			}
			if (isset($item->query['view'])) {
				$C[key($C)] .= '-'.$item->query['view'];
			}
		}
		if (isset($item->query['layout'])) {
			$C[] = $item->query['layout'];
		}

		$A = array();
		if ($parent) {
			if (isset($item->parent_alias)) {
				$A[] = $item->parent_alias;
			}
			else if (isset($item->parent_route)) {
				$A[] = substr($item->parent_route, 0, strpos($item->parent_route, '/'));
			}
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
			$ali = trim($ali, '-');

			// single word
			if (strpos($ali, '-') === false) continue;
			// short enough
			if (strlen($ali) <= 20) continue;

			// split and sanitize
			$alias = JStringNormalise::toSpaceSeparated($ali);
			$words = explode(' ', $alias);
			if (count($words) > 1) {
				$ali = array_diff($words, JFactory::getLanguage()->getIgnoredSearchWords());
				if (isset($item->language)) {
					$alias = $this->_inflectAlias($ali, $item->language);
				} else {
					$alias = $this->_inflectAlias($ali);
				}
			}
			$A[$k] = $alias;
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

		$words = array_unique( array_merge($C, $A) );
		$alias = implode(' ', $words);

		return trim($alias);
	}

	/**
	 * Tells whether the .mod class from OOCSS should be applied.
	 *
	 * @param  string  $position
	 * @return boolean
	 *
	 * @todo IMPLEMENT $position conditions
	 */
	public function moduleStyle($position)
	{
		return (bool) $this->tmpl->params->get('modOocss', 0);
	}

	// @todo refactor to use JStringXXX if that comes available
	protected function _inflectAlias(&$aliases, $language = null)
	{
		static $locale, $inflect = true;

		if (!$inflect) {
			return $aliases;
		}

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
			else {
				$inflect = false;
			}
		}

		return implode('-', $aliases);
	}

	/**
	 * Registers a layout file for use with a specific component name,
	 * category id or article id.
	 *
	 * Use the second parameter $scope for fine grained overrides
	 * - 'index'     in  /layouts/            using  {$themename}-index.php
	 * - 'component' in  /layouts/component/  using  {$option}.php
	 * - 'category'  in  /layouts/category/   using  category-{$cat_id}.php
	 * - 'article'   in  /layouts/article/    using  article-{$article_id}.php
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
	 *     ->addLayout($option, 'component')
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
	 * @return string  file path of layout or void if not found
	 *
	 * @todo implement additional magic based on the active menu item
	 */
	public function getLayout()
	{
		if (count($this->layouts) == 0) {
			return null;
		}

		$request	= new JInput();
		$tmpl	= $request->get('tmpl');
		$view	= $request->get('view');
		$file	= null;

		// override view? (category)
		if (isset($this->layouts[$view.'.php']))
		{
			$file = $this->layouts[$view.'.php'];
			// or a layout? (blog, list, form)
			$layout	= $request->get('layout');
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

	/**@#+
	 * Proxy for CustomTheme::setFeature() enable/disable a feature.
	 *
	 * <b>NOTE</b>: This interface will cast $data to a boolen, enabling
	 * the feature by default. Widgets and Features that requiren extra
	 * data should be assigned via {@link CustomTheme::setFeature()}.
	 */

	/**
	 * @param  string  $feature  A feature name
	 * @param  boolean $enable   enable/disable said $feature.
	 *
	 * @uses CustomTheme::setFeature()
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 */
	public function feature($feature, $enable = true)
	{
		return $this->theme->setFeature('feature.'. $feature, (bool) $enable);
	}

	/**
	 * @param  string  $widget   A feature name
	 * @param  boolean $enable   enable/disable said $feature.
	 *
	 * @uses CustomTheme::setFeature()
	 *
	 * @return ConstructTemplateHelper for fluid interface
	 */
	public function widget($widget, $enable = true)
	{
		return $this->theme->setFeature('widget.'. $widget, (bool) $enable);
	}

	/**
	 * @param  string  $type HEAD element name
	 * @return ElementRenderer instance of requested type.
	 */
	public function element($type)
	{
		try {
			return ElementRendererAbstract::getInstance('renderer.'. $type);
		}
		catch (Exception $e) {
		}

		return null;
	}


	/**@#- */

	/**
	 * Counts and returns the amount of active Modules in the given position $group.
	 *
	 * If "debug" mode, the number of modules and groups is equal to MAX_MODULES
	 * and MAX_COLUMNS
	 *
	 * @param string  $group
	 * @param integer $max   default self::MAX_MODULES
	 *
	 * @return array|null
	 * @see numModules(), renderModules()
	 * @uses JDocumentHTML::countModules();
	 *
	 * @todo find a more flexible way to count 'column-X' split into 'group-alpha/beta'
	 */
	public function getModulesCount($group, $max = self::MAX_MODULES)
	{
		settype($max, 'int');
		// #FIXME columns are only 2 per group 'alpha' and 'beta'
		if ($group =='column') {
			$max = self::MAX_COLUMNS;
		}

		if ($this->hasState('debug')) {
			self::$group_count[$group] = ($group =='column') ? self::MAX_COLUMNS : self::MAX_MODULES;
		}

		if (isset(self::$group_count[$group])) {
			return self::$group_count[$group];
		}

		if ($max < 1) $max = 1;

		$modules = array_fill(0, $max, 0);

		for ($i = 1; $i <= $max; $i += 1) {
			$modules[$i] = $this->doc->countModules($group .'-'. $i);
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
		if ($this->hasState('edit')) {
			return $this;
		}

		$group = false;
		if ( ($pos = strrpos($position, '-')) ) {
			$group = substr($position, 0, $pos);
		}

		if ( $group != false ) {
			$modules = $this->getModulesCount($group);
			$n = $modules[0];
		} else {
			$n = $this->doc->countModules($position);
		}

		/* nothing there? get outta here */
		if ( $n == 0 ) {
			return $this;
		}

		$attribs['name'] = $position;

		if (!array_key_exists('style', $attribs)) {
			$attribs['style'] = $style;
		}

		// disable ".unit" auto columns?
		if (!array_key_exists('autocols', $attribs)) {
			//#FIXME honor exclusion list for positions
			$attribs['autocols'] = $this->moduleStyle($position);
		}

		$css = array();
		settype($attribs['autocols'], 'bool');
		if ($attribs['autocols'] !== false)
		{
			if ( $n > 1 ) {
				unset($attribs['oocss']);
				$css[] = 'unit size1of'.$n;
			}
			unset($attribs['autocols']);
		}
		else {
			if ($this->moduleStyle($position)) {
				$css[] = 'mod';
			}
		}

		$css = array_unique($css);
		$html = array();
		foreach (JModuleHelper::getModules($position) as $module)
		{
			// find encoded @chrome style name in moduleclass_sfx
			$sfx = strpos(str_replace('"moduleclass_sfx":""', '', $module->params), '"moduleclass_sfx"');
			if ($sfx !== false && strpos($module->params, '@', $sfx) !== false)
			{
				$params = json_decode($module->params);
				$chrome  = preg_grep('/^@([a-z]+)/', explode(' ', $params->moduleclass_sfx));

				// per module setting takes precedence
				$attribs['style'] = str_replace('@', '', implode(' ', $chrome));

				// put everything else back
				$params->moduleclass_sfx = trim(str_replace($chrome, '', $params->moduleclass_sfx));
				$module->params = json_encode($params);
			}

			// is this as "widget" shortcut, i.e. using a custom module?
			if (isset($attribs['style']) && $this->theme->getFeature($attribs['style']))
			{
				$content = $this->theme->renderFeature($attribs['style'], array('module'=>&$module));
			}
			else {
				// render module
				$content = JModuleHelper::renderModule($module, $attribs);
			}

			// this crap doesn't belong here
			$content = $this->_choppInlineCrap($content, $module->module);

#	if (self::isEmpty($content)) {
#		continue;
#	}

			if ( ($chunk = $this->theme->getChunk('module', array('before', $module->name))) )
			{
				$name = '-'. $module->name;
				if ($position == $module->name) {
					$name  = '';
					$css[] = $position;
				}
				$html[] = str_replace(
							array('{position}', '{name}', '{class}'),
							array($position, $name, implode(' ', $css)),
							$chunk
							);
			}

			$html[] = $content;

			if ( ($chunk = $this->theme->getChunk('module', array('after', $module->name))) )
			{
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

	/**
	 * @deprecated 1.10.0
	 */
	public function buildHead() {return $this;}

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
	protected function sortScripts($head)
	{
		// jQuery CDNs: http://docs.jquery.com/Downloading_jQuery#CDN_Hosted_jQuery
		static $CDN = array(
					'ajax.googleapis.com'	=> array('jquery'=>'/jquery/(\d\.\d\.\d)/', 'mootools'=>'/mootools/(\d\.\d\.\d)/'),
					'ajax.aspnetcdn.com'	=> array('jquery'=>'/jquery-(\d\.\d\.\d)' , ),
					'code.jquery.com' 		=> array('jquery'=>'/jquery-(\d\.\d\.\d)' , ),
					'cdnjs.cloudflare.com'	=> array('jquery'=>'/jquery/(\d\.\d\.\d)/', 'mootools'=>'/mootools/(\d\.\d\.\d)/'),
				);

		// Remove MooTools if set to do so.
		$loadMoo	= $this->tmpl->params->get('loadMoo');
		$loadJQuery	= $this->tmpl->params->get('loadjQuery');

		// however ...
		if ($this->hasState('edit'))
		{
			$loadMoo = true;
			$loadJQuery	= false;
		}

		if ($loadMoo == false)
		{
			// without MooTools we must drop all but core.js
			$moos = preg_grep('#media/system/js(\/(?!core))#', array_keys($head['scripts']));
			if (count($moos) > 0) {
				foreach ($moos as $src) {
					unset($head['scripts'][$src]);
				}
			}
		}
		else {
			// Load the MooTools JavaScript Library
			JHtml::_('behavior.framework');
			if ((bool)$this->tmpl->params->get('loadModal', 0)) {
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

		return $this;
	}

	/**
	 * Optimize the order of styles and scripts.

The following external CSS files were included after an external JavaScript file in the document head.
To ensure CSS files are downloaded in parallel, always include external CSS before external JavaScript.
Inline script block was found in the head between an external CSS file and another resource.
To allow parallel downloading, move the inline script before the external CSS file, or after the next resource.

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

			// inline style
			if (isset($groups['style']) && count($groups['style'])) {
				$head['custom'][] = '<style type="text/css">';
				foreach ($groups['style'] as $stuff) {
				}
				$head['custom'][] = '</style>';
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

			if ($ua != self::UA) $head['custom'][] = '<![endif]-->';
		}

		// put back what's left
		$this->doc->setHeadData($head);

		return $this;
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
	 * @param int $max default 9, CC's end with MSIE 9
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
	 * Find if the $markup contains "content".
	 * Allows the Module Chrome to decide if it's worth adding more stuff to nothing.
	 * Allows the Layouts to avoid spitting out empty container.
	 *
	 * The following elements are considered "non empty" content:
	 * audio, canvas, embed, iframe, img, math, object, svg, video, command, script, style
	 *
	 * @param  string  $markup
	 * @return bool
	 */
	static public function isEmpty(&$markup, $by='')
	{
		#FIXME
		return false;
/*
		static $keepers = '<audio><canvas><embed><hr><iframe><img><math><noscript><object><param><svg><video><command><script><style>';
		// decode entities, keep meta + embeds, then remove "white-space"
		$blank = preg_replace('#[\r\n\s\t\h\v\f]+#', '', strip_tags(html_entity_decode($markup), $keepers) );
		return empty($blank);
*/
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
		if (null === $key) {
			return $this->config;
		}

		if ( array_key_exists($key, $this->config) ) {
			return $this->config[$key];
		}

		return $default;
	}

	protected function loadConfig()
	{
		$default = array();

		// fake ini file
		$config  = parse_ini_file(WMPATH_TEMPLATE . '/elements/settings.php', true);
		$default = array_merge_recursive($default, $config);

		foreach ($default['subst'] as $k => $v)
		{
			settype($default['subst'][$k], 'string');
			$default['subst'][$k] = str_replace(
							array('{root}','{template}','{theme}','{media}'),
							array(JURI::root(true),
									$default['subst']['template'],
									$default['subst']['theme'],
									$default['subst']['media'],
								),
							$v);
		}

		$this->config = $default;
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
	 * Return Template meta data and parameters.
	 * @return object
	 */
	public function getTemplate()
	{
		if (!isset($this->tmpl)) {
			$this->tmpl = JFactory::getApplication()->getTemplate(true);
		}

		return $this->tmpl;
	}

	/**
	 * Tells the current document request state to add/suppress external
	 * resources such as print or edit stylesheets.
	 *
	 * @param  string $state a document/request state: edit, print, modal, preview...
	 * @return bool
	 */
	public function hasState($state, $default = false)
	{
		$state = strtolower($state);
		if (null === self::$states[$state])
		{
			self::$states[$state] = $default;
			$request = new JInput();

			switch ($state)
			{
				case 'edit':
					self::$states[$state] = (bool) $this->_editState($request);
					break;
				case 'print':
					self::$states[$state] = (bool) $this->_printState($request);
					break;
				case 'modal':
					break;
				case 'debug':
					self::$states[$state] = (bool) $this->_debugState($request);
					break;
			}
		}

		return self::$states[$state];
	}

	/**
	 * @return bool true if the document/template is in "edit mode"
	 */
	protected function _editState(JInput $request)
	{
		return in_array($request->get('layout'), array('edit','form','pagebreak'))
				|| in_array($request->get('view'), array('form'))
				|| in_array($request->get('option'), array('com_media'))
				|| $request->get('e_name');
	}
	/**
	 * @return bool true if the document/template is in "print mode"
	 */
	protected function _printState(JInput $request)
	{

	}
	/**
	 * @return bool true if the document/template is in "modal window mode"
	 */
	protected function _modalState(JInput $request)
	{

	}
	/**
	 * @return bool true if the document/template is in "debug mode"
	 */
	protected function _debugState(JInput $request)
	{
		$app = JFactory::getApplication();
		return $app->getCfg('debug') && $request->get('tpos', 0, 'bool');
	}

	private function _applySubst($key, $name, $type=null)
	{
		if (!isset($this->config['subst'][$key])) {
			return $this;
		}
		$from = array('{name}');
		$to   = array($name);
		if (!empty($type)) {
			$from = array('{type}');
			$to   = array($type);
		}

		$this->config['subst'][$key] = str_replace($from, $to, $this->config['subst'][$key]);
	}

	/**
	 * Some Modules and Plugins, incl. the "Core" don't use the JDocumentHTML API
	 * to add scripts and styles to the HEAD element.
	 *
	 * @param  string  $content  Some markup
	 * @param  string  $culprit  A string that identifies the content originator
	 */
	private function _choppInlineCrap($content, $culprit = '')
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
					$this->element('script')->set($arr['src'], $arr, 'BODY');
					$found = true;
				}
				// not type or a type pointing to a script language
				else if (!isset($arr['type']) || (isset($arr['type']) && strpos($arr['type'], 'script') )) {
					$this->element('scripts')->set($m[2][$i], null, 'BODY');
					$found = true;
				}

				if ($found) {
					$content = str_replace($m[0][$i], '', $content);
				}
			}
		}

		return trim($content);
	}

}
