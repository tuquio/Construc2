<?php
/**
 * @package     Template Framework for Joomla! 1.6
 * @author      Joomla Engineering http://joomlaengineering.com
 * @author		WebMechanic http://webmechanic.biz
 * @copyright   Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

/**
 * ConstructTemplateHelper
 *
 * Helper functions for the Construct Template Framework
 *
 * @package	Molajo
 * @subpackage	Helper
 * @since	1.0
 */
class ConstructTemplateHelper
{
	/** @var array List of template layout files */
	protected $layouts = array();

	/** @var string Template foldername */
	protected $tmpl;

	/**
	 * Template Helper constructor expects the template object as its argument.
	 *
	 * @param JDocument $template
	 */
	public function __construct(JDocument $template)
	{
		$this->tmpl = $template;
	}

	/**
	 * Registers a layout file for use with a specific component or section-id.
	 *
	 * Use the second parameter $area for fine grained overrides
	 *    - 'index'     in  /layouts/            using  {$themename}-index.php
	 *    - 'component' in  /layouts/component/  using  {$currentComponent}.php
	 *    - 'section'   in  /layouts/sections/   using  section-{$sectionId}.php
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

		if (is_numeric($basename)) {
			$scope = 'section';
		} else {
			// catch 'html-foobar.css' for foobar.html
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
			case 'html':
				// static file for css tests
				$ext = '.html';
				break;
		}

	  	$layout     = trim($scope .'/'. basename($basename, $ext) . $ext, ' /_-');
		$layoutpath = JPATH_THEMES.'/'.$this->tmpl->template .'/layouts/' . $layout;

		if (JFile::exists($layoutpath)) {
			$this->layouts[md5($layout)] = $layoutpath;
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
	        return false;
	    }

        $jmenu = JFactory::getApplication()->getMenu()->getActive();

		foreach ($this->layouts as $path) {
			// return first file that exists
			if (JFile::exists(JPATH_THEMES.'/'.$this->tmpl->template .'/layouts/' . $path)) {
				break;
			}
			$path = null;
		}
		// not found
		return $path;
	}

	public function getModulesCount($group, $max = 6)
	{
		$modules = array();
		for ($i=1; $i<=6; $i++) :
			$modules[$i] = (int) ($this->tmpl->countModules($group .'-'. $i) > 0);
			$modules[0]  = $modules[$i];
		endfor;
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