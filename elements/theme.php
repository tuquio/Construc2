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
 * @param  string   $context content being passed to the plugin.
 * @param  object   $item    article object.  Note $article->text is also available
 * @param  object   $params  article params
 * @param  int      $limit   'page' number
 */
function CustomThemeContentEvent($context, $item, $params, $limit=0)
{
}

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
	protected $_config = array(
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
	 * @static array chunks from the static html file(s) *
	 */
	static $html;

	/**
	 * @see setChunks()
	 */
	static $chunks = array(
					'meta'          => '',
					'module_before'	=> '<div class="{class} {name}">',
					'module_after'	=> '</div>',
					'unit_before'	=> '<div class="{class}">',
					'unit_after'	=> '</div>'
				);

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
		$this->url  = JUri::root(true) .'/'. basename(JPATH_THEMES) .'/'. $tmpl->template .'/themes';

		$this->config = new JObject($this->_config);

		if (is_file($this->path))
		{
			// fake ini file
			$config = parse_ini_file($this->path, true);
			if ($config && count($config) > 0)
			{
				$this->title   = $config['title'];
				$this->version = $config['version'];
				$this->config->setProperties($config);
			}
		}
	}

	/**
	 * @param  ConstructTemplateHelper $helper
	 * @return CustomTheme
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

	public function get($property)
	{
		if ($property[0] != '_' && isset($this->{$property})) {
			return $this->{$property};
		}
	}

	/**
	 * Stores a piece of runtime generated content into a named buffer.
	 *
	 * @param  string  $name     buffer name, usually a template position or a "chunk" of the theme
	 * @param  string  $content  the content to store
	 * @param  array   $options  RESERVED
	 *
	 * @return CustomTheme
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
		if (isset(self::$html[$name])) {
			return ($checkonly == true) ? strlen(self::$html[$name]) : self::$html[$name];
		}
		return '';
	}

	/**
	 * @param  array  $chunks   assoc array with chunk names and their data
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
		return $this->config->get($name, $default);
	}

	/**
	 * @param string     $context    event originator
	 * @param object     $item       data to be deleted
	 * @param JRegistry  $params     item parameters
	 * @param int        $limitstart data offset or paginator
	 */
	static public function onContentAfterTitle($context, $item, $params, $limitstart=0)
	{
	}

	/**
	 * @param string     $context    event originator
	 * @param object     $item       data to be deleted
	 * @param JRegistry  $params     item parameters
	 * @param int        $limitstart data offset or paginator
	 */
	static public function onContentBeforeDisplay($context, $item, $params, $limitstart=0)
	{
	}

	/**
	 * @param string     $context    event originator
	 * @param object     $item       data to be deleted
	 * @param JRegistry  $params     item parameters
	 * @param int        $limitstart data offset or paginator
	 */
	static public function onContentAfterDisplay($context, $item, $params, $limitstart=0)
	{
	}

}
