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

	/** @var string Template foldername */
	protected $tmpl;

	/** @var ConstructTemplateHelper instance of self
	 * @see getInstance() */
	protected static $helper;

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

	/**
	 * Template Helper constructor expects the template object as its argument.
	 *
	 * @param JDocument $template
	 */
	public function __construct(JDocument $template)
	{
		$this->tmpl = $template;
		$this->layoutpath = JPATH_THEMES .'/'. $this->tmpl->template .'/layouts';

		// Get the name of the extended template override group
		$override = $template->params->get('customStyleSheet');
		if ($override != '-1') {
			$override = str_replace('.css', '', $override);
			$this->addLayout($override);
		}

		$this->addLayout('index');
	}

	public static function getInstance(JDocument $template)
	{
		if (!self::$helper) {
			self::$helper = new ConstructTemplateHelper($template);
		}
		return self::$helper;
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
	public function &addLayout($basename, $scope=null)
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
	 * @return string
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

	public function getLayouts()
	{
		return $this->layouts;
	}

	/**
	 * Will load the static html file names and prepare its depended "chunks"
	 * for later inclusion in "static_html.php".
	 * @param array $layout
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
	 * @param array $chunks
	 */
	static public function &setChunks(array $chunks)
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
	 * Allows for individual formatting of each date fragent, day, month, year, via CSS.
	 *
	 * - $dateformat: upported date formater characters: l, d, F, Y or a DATE_FORMAT_XXX string
	 * - $date: a value JDate() considers a valid date value, 'now'|null|false result in current date
	 * - $elt: HTML element to wrap around the date parts
	 * To set the $elt only, but preseve (todays) defaults use
	 * <samp>$layoutOverride->dateContainer(null,null,'kbd')</samp>
	 *
	 * @param string $dateformat a Joomla date language string, default: DATE_FORMAT_LC3
	 * @param number $date defaults to 'now' (also if null or false are provided)
	 * @param string $elt defaults to 'span' as the date fragment wrapper element
	 * @return mixed
	 */
	public function dateContainer($dateformat='DATE_FORMAT_LC3', $date='now', $elt='span')
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
