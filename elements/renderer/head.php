<?php
/**
 * Various classes to render contents in the HTML <HEAD> element.
 *
 * @package     Construc2
 * @subpackage  Renderer
 * @copyright	(C)2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

abstract class ElementRenderer
{
	const UA = 'ALL';

	protected $attribs = array();
	protected $name = 'head';
	protected $data = array();
	protected static $elements = array();

	/** API compliance with {@link JDocumentRenderer} */
	protected function __construct()
	{
		preg_match('/([A-Z]+[a-z]+)$/', get_class($this), $parts);
		$this->name  = strtolower($parts[1]);
	}

	public static function getInstance($type)
	{
		if (!isset(self::$elements[$type]))
		{
			$class = 'ElementRenderer'. ucfirst($type);
			self::$elements[$type] = new $class();
		}

		self::$elements[$type]->init();

		return self::$elements[$type];
	}

	public function __toString()
	{
		return implode(PHP_EOL, $this->data);
	}

	public function __toArray()
	{
		return $this->data;
	}

	/**
	 * Parse substitution {keys} into their full URL equivalent.
	 * Default keys: theme, template, core, system, module, plugin
	 *
	 * @param  string $url
	 */
	static public function subst(&$url)
	{
		//#FIXME implement
		$parsed = $url;

		return $parsed;
	}

	/**
	 * Allow descendent classes to initialize $data.
	 */
	protected function init() {}

	abstract public function build(array &$head, $options=null);
	abstract public function set($key, $value, $ua=null);
}

/**
 * An API compliant clone of {@link JDocumentRenderer} to replace render()
 *
 * Also adds some magic to head to boost performance
 *
 * @link http://is.gd/RRg458 "Get your HEAD in order"; MSDN Blog on X-UA-Compatible
 * @link http://html5boilerplate.com/docs/head-Tips/
 * @link http://html5boilerplate.com/docs/DNS-Prefetching/  DNS Prefetching
 */
class ElementRendererHead extends ElementRenderer
{
	protected $doc = null;

	/** for API compliance with {@link JDocumentRenderer} */
	protected $mime = 'text/html';

	/** for API compliance with {@link JDocumentRendererHead} */
	public function render($name = null, $attribs = array(), $content = null)
	{
		JFactory::getApplication()->triggerEvent('onBeforeCompileHead');

		$theme = ConstructTemplateHelper::getInstance()->theme;

		return $theme->build(JFactory::getDocument());
	}

	/** for API compliance with {@link JDocumentRenderer} */
	public function getContentType() { return $this->mime; }

	/**
	 * Sets charset, base and title to preceeed anything else.
	 *
	 *
	 * @todo  add "<link rel="dns-prefetch" href="//cdn.foo.bar">" if CDN are configured
	 */
	public function init()
	{
		$document = JFactory::getDocument();

		// prevents refetching
		$this->data['charset'] = '<meta charset="utf-8">';
		$this->data['X-UA-Compatible'] = '<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">';

		$base = $document->getBase();
		if (!empty($base)) {
			$this->data['base'] = '<base href="'. $base .'">';
		} else {
			$document->setBase(JURI::current());
			$this->data['base'] = '<base href="'. JURI::current() .'">';
		}

		$this->data['title'] = '<title>'. htmlspecialchars(strip_tags($document->getTitle()), ENT_COMPAT, 'UTF-8') .'</title>';

		return $this;
	}

	public function build(array &$head, $options=null) {return $this;}
	public function set($key, $value, $ua=null) {return $this;}
}

/**
 * Renders the <title>, <base>, and all <meta> elements.
 *
 * Also adds/removes some "features" for some browsers if configured.
 *
 * @link http://msdn.microsoft.com/en-us/library/gg131029.aspx  IE9 Pinned Sites
 */
class ElementRendererMeta extends ElementRenderer
{
	public function build(array &$head, $options=null)
	{
		$standard  = &$head['metaTags']['standard'];
		$httpEquiv = &$head['metaTags']['http-equiv'];

		// remap to standards
		$this->set('author', @$standard['rights']);
		unset($standard['rights']);

		$this->set('description', @$standard['description']);
		unset($standard['description']);

		unset($standard['copyright']);
		unset($standard['title']);

		// kick some MSIE weirdness
		$this->httpEquiv('imagetoolbar', 'no');
		$this->httpEquiv('MSThemeCompatible', 'no');
		// and prevent it from blocking itself
		// @link http://webforscher.wordpress.com/2010/05/20/ie-6-slowing-down-ie-8/
		$this->data[] = '<!--[if IE]><![endif]-->';

		return $this;
	}

	public function set($name, $content, $ua=null)
	{
		if (isset($this->data[$name])) return $this;

		if (is_array($content)) {
			$content = JArrayHelper::toString($array,'=', '"');
		}

		if ($name == null && $content) {
			$this->data[$name] = '<meta '. $content .'>';
		}
		else if ($content) {
			$this->data[$name] = '<meta name="'. $name .'" content="'. $content .'">';
		}

		return $this;
	}

	public function httpEquiv($name, $content, $ua=null)
	{
		if (isset($this->data[$name])) return $this;

		if (is_array($content)) {
			$content = implode(' ', $content);
		}

		if ($content) {
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
class ElementRendererLink extends ElementRenderer
{
	/** default MIME type used for URLs */
	protected $mime = 'text/css';

	protected function init()
	{
		// header('Link: <'. JURI::current() .'>; rel="canonical"');
		$this->set(JURI::current(), 'canonical');

		return $this;
	}

	public function build(array &$head, $mode=null)
	{
		$theme = ConstructTemplateHelper::getInstance()->theme;

		if (!$mode) {
			$mode = 'link';
		}

		if ('link' == $mode) {
			$this->_buildLink($head);
		}
		else if ('style' == $mode) {
			$this->_buildStyle($head);
		}

		$this->set($theme->tmpl_url.'/themes/'.$theme->get('name') .'.css');

		return $this;
	}

	protected function _buildLink(array &$head)
	{
	}

	protected function _buildStyle(array &$head)
	{
	}

	public function set($href, $rel='stylesheet', $attribs = array(), $ua=null)
	{
		if (isset($this->data[$href])) return $this;

		$type = 'rel';
		if (empty($rel)) {
			if (isset($attribs['rel'])) {
				$rel = $attribs['rel'];
			}
		}
		else {
			$attribs['rel'] = $rel;
		}

		$this->data[$href] = '<link '. $type .'="'. $rel .'" href="'. $href .'">';

		return $this;
	}
}

/**
 * Render <style> elements for inline style sheets.
 */
class ElementRendererStyles extends ElementRenderer
{
	public function build(array &$head, $options=null)
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
class ElementRendererScript extends ElementRenderer
{
	protected function init()
	{
		$href = ConstructTemplateHelper::getInstance()->theme->tmpl_url. '/js/html5.js';
		$this->set($href);

		return $this;
	}

	public function build(array &$head, $options=null)
	{
		return $this;
	}

	public function set($href, $attribs=array(), $ua=null)
	{
		if (isset($this->data[$href])) return $this;

		$this->data[$href] = '<script src="'. $href . '"></script>';

		return $this;
	}
}

/**
 * Render inlined <script> elements.
 *
 * @link Google CFInstall and defer http://is.gd/mEm4hQ
 */
class ElementRendererScripts extends ElementRenderer
{
	public $scripts;

	protected function init()
	{
		$this->scripts = 0;

		return $this;
	}

	public function build(array &$head, $options=null)
	{
		array_unshift($this->data, '<script type="text/javascript">');
		$this->data[]  = '</script>';

		return $this;
	}

	public function set($script, $reserved=null, $ua=null)
	{
		$this->scripts += 1;

		if (strpos($script, '[CDATA[') > 1) {
			$this->_dexhtmlize($script);
		}

		$e = 'e'.$this->scripts;
		$this->data[$this->scripts] = 'try {'. $script .'} catch('.$e.') {if("console" in window){console.log('.$e.');}}';

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
class ElementRendererCustom extends ElementRenderer
{
	public function build(array &$head, $options=null)
	{
		return $this;
	}

	public function set($key, $value, $ua=null)
	{
		return $this;
	}
}

/**
 * Stub implementing <jdoc:include type="head" />.
 * The actual work load is performed in ElementRendererHead.
 */
class JDocumentRendererHead extends ElementRendererHead
{
	/**
	 * @param JDocument $document
	 */
	public function __construct(JDocument $document)
	{
		// delegate to parent, as getInstance() won't cut it for this "override"
		parent::__construct();

		// and register this instance here
		parent::$elements['head'] = $this;
	}
}

/**
 * Experimental implementation of <jdoc:include type="foot" />
 * to push analytics scripts and late bound stuff into so they
 * don't block page rendering.
 */
class JDocumentRendererFoot extends ElementRenderer
{
	public function build(array &$head, $options=null) {return $this;}
	public function set($key, $value, $ua=null) {return $this;}
}
