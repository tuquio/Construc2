<?php
/**
 * Various classes to render contents in the HTML <HEAD> element.
 *
 * @package     Construc2
 * @subpackage  Renderer
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

JLoader::register('ElementRendererAbstract', WMPATH_TEMPLATE . '/elements/renderer/abstract.php');
JLoader::register('JDocumentRendererToe', WMPATH_TEMPLATE . '/elements/renderer/toe.php');

/**
 * An API compliant clone of {@link JDocumentRenderer} to replace render()
 *
 * Also adds some magic to head to boost performance
 *
 * @link http://is.gd/RRg458 "Get your HEAD in order"; MSDN Blog on X-UA-Compatible
 * @link http://html5boilerplate.com/docs/head-Tips/
 * @link http://html5boilerplate.com/docs/DNS-Prefetching/  DNS Prefetching
 */
class ElementRendererHead extends ElementRendererAbstract implements IElementRenderer
{
	protected $name = 'head';
	protected $_doc = null;

	/** for API compliance with {@link JDocumentRenderer} */
	protected $_mime = 'text/html';

	/**
	 * For API compliance with {@link JDocumentRenderer}
	 * @param string $name      The name of the element to render
	 * @param array  $params    Array of values
	 * @param null   $content   Override the output of the renderer
	 * @return string
	 */
	public function render($name = null, $params = array(), $content = null)
	{
		JFactory::getApplication()->triggerEvent('onBeforeCompileHead');

		$theme = ConstructTemplateHelper::getInstance()->theme;
		$theme->build();

		return implode('', $theme->getChunk('meta'));
	}

	/**
	 * Sets charset, base and title to preceeed anything else.
	 *
	 * @todo  add "<link rel="dns-prefetch" href="//cdn.foo.bar">" if CDN are configured
	 */
	public function init()
	{
		if (!isset($this->_doc)) {
			$this->_doc = JFactory::getDocument();
		}

		// prevents refetching
		$this->data['charset'] = '<meta charset="utf-8">';

		$base  = $this->_doc->getBase();
		$trail = ((bool)JFactory::getConfig()->get('sef_suffix', 0)) ? '' : '/';
		if (!empty($base)) {
			$this->data['base'] = '<base href="'. rtrim($base,'/') . $trail .'">';
		} else {
			$this->_doc->setBase(JURI::current());
			$this->data['base'] = '<base href="'. rtrim(JURI::current(),'/') . $trail .'">';
		}

		$this->data['title'] = '<title>'. htmlspecialchars(strip_tags($this->_doc->getTitle()), ENT_COMPAT, 'UTF-8') .'</title>';

		return $this;
	}

	/**
	 * @inherit
	 * @return ElementRendererHead
	 */
	public function build(array &$data, $options=null)
	{
		FB::log($data, __METHOD__);

		return $this;
	}

	/**
	 * @inherit
	 * @return ElementRendererHead
	 */
	public function set($key, $value, $uagent=null) {return $this;}
}

/**
 * Renders the <title>, <base>, and all <meta> elements.
 *
 * Also adds/removes some "features" for some browsers if configured.
 *
 * @link http://msdn.microsoft.com/en-us/library/gg131029.aspx  IE9 Pinned Sites
 */
class ElementRendererMeta extends ElementRendererAbstract
{
	protected $name = 'meta';

	/**
	 * @param array $data
	 * @param null  $options
	 * @return ElementRendererMeta
	 */
	public function build(array &$data, $options=null)
	{
		FB::log($data, __METHOD__);

		$standard  = &$data['standard'];

		// remap to standards
		$this->set('author', @$standard['rights']);
		unset($standard['rights']);

		$this->set('description', @$standard['description']);
		unset($standard['description']);

		unset($standard['copyright']);
		unset($standard['title']);

		// and prevent it from blocking itself
		// @link http://webforscher.wordpress.com/2010/05/20/ie-6-slowing-down-ie-8/
		$this->data[] = '<!--[if IE]><![endif]-->';

		return $this;
	}

	/**
	 * @param      $name
	 * @param      $content
	 * @param null $ua
	 * @return ElementRendererMeta
	 */
	public function set($name, $content, $ua=null)
	{
		if (isset($this->data[$name])) return $this;

		if (is_array($content)) {
			$content = JArrayHelper::toString($content, '=', '"');
		}

		if ($name == null && $content) {
			$this->data[$name] = '<meta '. $content .'>';
		}
		else if ($content) {
			$this->data[$name] = '<meta name="'. $name .'" content="'. $content .'">';
		}

		return $this;
	}

	/**
	 * @param      $name
	 * @param      $content
	 * @param null $ua
	 * @return ElementRendererMeta
	 */
	public function httpEquiv($name, $content, $ua=null)
	{
		if (isset($this->data[$name])) return $this;

		if (is_array($content)) {
			$content = implode(' ', $content);
		}

		if ($content) {
			self::subst($content);
			$this->data[$name] = '<meta http-equiv="'. $name .'" content="'. $content .'">';
		}

		return $this;
	}
}

/**
 * Render <link> elements used for external stylesheets and
 * pointers to alternative formats such as feed links.
 *
 * Adds some featured for supporting browsers and spiders.
 *
 * @link http://html5boilerplate.com/docs/head-Tips/
 * @link http://microformats.org/wiki/rel-shortlink  Shortlinks
 * @link http://codex.wordpress.org/Introduction_to_Blogging#Pingbacks  Pingbacks
 * @link http://humanstxt.org/Standard.html  humans.txt
 */
class ElementRendererLink extends ElementRendererAbstract
{
	protected $name = 'link';

	/** default MIME type used for URLs */
	protected $_mime = 'text/css';

	protected function init()
	{
		// header('Link: <'. JURI::current() .'>; rel="canonical"');
		$trail = (JFactory::getConfig()->get('sef_suffix', 0)) ? '' : '/';
		$this->set(rtrim(JURI::current(),'/') . $trail, 'canonical');

		return $this;
	}

	public function build(array &$data, $mode=null)
	{
		// core stylesheets
		$this->set('{tmpl.css}/core/base.css');
		$this->set('{tmpl.css}/core/oocss.css');
		$this->set('{tmpl.css}/core/template.css');

		// theme style
		$this->set('{theme}.css');

		return $this;
	}

	public function set($href, $rel='stylesheet', $attribs = array(), $ua=null)
	{
		self::subst($href);

		if (!isset($this->data[$rel])) {
			$this->data[$rel] = array();
		}

		if (isset($this->data[$rel][$href])) return $this;

		$type = 'rel';
		if (empty($rel)) {
			$rel = isset($attribs['rel']) ? $attribs['rel'] : 'stylesheet';
		}
		else {
			$attribs['rel'] = $rel;
		}

		$this->data[$rel][$href] = '<link '. $type .'="'. $rel .'" href="'. $href .'">';

		return $this;
	}

	public function add($attribs, $ua=null)
	{

	}
}

/**
 * Render <style> elements for inline style sheets.
 */
class ElementRendererStyles extends ElementRendererAbstract
{
	protected $name = 'styles';

	public function build(array &$data, $options=null)
	{
		return $this;
	}

	public function set($key, $value, $ua=null)
	{
		return $this;
	}
}

/**
 * Render <script src> elements for external script files.
 */
class ElementRendererScript extends ElementRendererAbstract
{
	protected $name = 'script';

	public function build(array &$data, $options=null)
	{
		return $this;
	}

	public function set($src, $attribs=array(), $ua=null)
	{
		self::subst($src);

		if (isset($this->data[$src])) return $this;

		$this->data[$src] = '<script src="'. $src . '"></script>';

		return $this;
	}

	public function add($attribs, $ua=null)
	{

	}
}

/**
 * Render inlined <script> elements.
 *
 * @link Google CFInstall and defer http://is.gd/mEm4hQ
 */
class ElementRendererScripts extends ElementRendererAbstract
{
	protected $name = 'scripts';

	public $scripts;

	protected function init()
	{
		$this->scripts = 0;

		return $this;
	}

	public function build(array &$data, $options=null)
	{
		array_unshift($this->data, '<script type="text/javascript">');
		$this->data[]  = '</script>';

		return $this;
	}

	public function set($script, $reserved=null, $ua=null)
	{
		$this->scripts += 1;

		if (strpos($script, '[CDATA[') >= 2) {
			$this->_dexhtmlize($script);
		}

		$e = 'e'.$this->scripts;
		$this->data[$this->scripts] = 'try {'. $script .'} catch('.$e.') {if(\'console\' in window){console.log('.$e.');}}';

		return $this;
	}

	protected function _dexhtmlize(&$script)
	{
		// de-XHTMLize inline <script> created by modules
		$script = str_replace(array('<![CDATA[', ']]>',"//\r\n","//\n"), '', $script);
		$script = str_replace(array("\r\n\t","\n\t","\n\n"), "\n", $script);
	}
}

/**
 * Render custom markup.
 */
class ElementRendererCustom extends ElementRendererAbstract
{
	protected $name = 'custom';

	public function build(array &$data, $options=null)
	{
		return $this;
	}

	public function set($key, $value, $ua=null)
	{
		return $this;
	}
}

if ( !class_exists('JDocumentRendererHead', false))
{
	/**
	 * Stub implementing <jdoc:include type="head" />.
	 * The actual work load is performed in ElementRendererHead.
	 */
	class JDocumentRendererHead extends ElementRendererHead
	{
		/**
		 * @param JDocument $_document
		 */
		public function __construct(JDocument $_document)
		{
			// delegate to parent, as getInstance() won't cut it for this "override"
			parent::__construct();
		}

		/** for API compliance with {@link JDocumentRenderer} */
		public function getContentType() { return $this->_mime; }
	}
}
