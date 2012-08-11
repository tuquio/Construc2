<?php
/**
 * Some workarounds against and features for a family of browser emulator.
 *
 * - bho: kick imagetoolbar, MSThemeCompatible
 * - browserchoice:  BrowserChoice.com Redirect
 * - cfinstall:  Google Chrome Frame Install for oldIEs
 * - edge:  X-UA-Compatible  !! THIS BETTER GOES INTO .htaccess
 * - metroActivex:  X-UA-Compatible  !! THIS BETTER GOES INTO .htaccess
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * @link http://blogs.msdn.com/b/ie/  IEBlog
 * @link http://blogs.msdn.com/b/ieinternals/  IE-Internals
 * @link http://h5bp.com/i/378  X-UA-Compatible issues
 * @link http://wiki.whatwg.org/wiki/PragmaExtensions
 * @link http://blogs.msdn.com/b/ie/archive/2012/01/31/web-sites-and-a-plug-in-free-web.aspx
 */
class ElementFeatureMsie extends ElementRendererMeta
{
	protected $name = 'msie';
	protected $renderer = array(
				'meta'=>'meta',
				'browserchoice'=>'meta',
				'cfinstall'=>'script',
				'edge'=>'meta',
			);

	/**
	 * Kick imagetoolbar and MSThemeCompatible.
	 *
	 * <b>It's highly recommended to add a proper "http-equiv" server
	 * response header instead</b> i.e. .htaccess
	 * <code>Header set imagetoolbar "no"</code>
	 * <code>Header set MSThemeCompatible "no"</code>
	 *
	 * @return ElementFeatureMsie
	 */
	public function meta()
	{
		$this->httpEquiv('imagetoolbar', 'no');
		$this->httpEquiv('MSThemeCompatible', 'no');

		return $this;
	}

	/**
	 * BrowserChoice.com Popup or Redirect
	 *
	 * @return ElementFeatureMsie
	 */
	public function browserchoice()
	{
		return $this->set('refresh', '2;url=http://browserchoice.eu/', 'lt IE 9');
	}

	/**
	 * Google Chrome Frame Install for oldIEs
	 *
	 * @return ElementFeatureMsie
	 * @see edge()
	 */
	public function cfinstall($version = '1.0.3')
	{
		$href    = '//ajax.googleapis.com/ajax/libs/chrome-frame/'. $version .'/CFInstall.min.js';
		$attribs = array(
					'src'=>$href,
		            'async'=>true,
		            'onload'=>'var e=document.createElement("DIV");if(e && CFInstall){e.id="gcf_placeholder";e.style.zIndex="9999";CFInstall.check({node:"gcf_placeholder"});}'
					);

		return $this->set('script', $attribs, 'lt IE 9');
	}

	/**
	 * Puts IE7+ in standards document mode and trigger Google Chrome frame
	 * rendering if installed.
	 *
	 * <b>It's highly recommended to add a proper "http-equiv" server
	 * response header instead</b>, i.e. .htaccess to prevent the 
	 * "compatibility view button" to appear.
	 * <code>Header set X-UA-Compatible "IE=Edge,chrome=1"</code>
	 *
	 * @link http://h5bp.com/i/378  X-UA-Compatible issues
	 * @link http://wiki.whatwg.org/wiki/PragmaExtensions
	 *
	 * @return ElementFeatureMsie
	 * @see metroActivex(), cfinstall()
	 *
	 * @todo call ElementRendererHead and "inject" BEFORE data['base']
	 */
	public function edge()
	{
		return $this->httpEquiv('X-UA-Compatible', 'IE=Edge,chrome=1');
	}

	/**
	 * Metro style IE10 detects these flags, and provides a one-touch 
	 * option to switch to IE10 on the desktop.
	 *
	 * <b>IMPORTANT!</b> This currently includes edge() to handle IE8+ ChromeFrame!
	 * <b>It's highly recommended to add a proper "http-equiv" server response header instead.</b>
	 *
	 * <code>Header set X-UA-Compatible "requiresActiveX=true"</code>
	 * <code><meta http-equiv="X-UA-Compatible" content="requiresActiveX=true" /></code>
	 *
	 * @link http://blogs.msdn.com/b/ie/archive/2012/01/31/web-sites-and-a-plug-in-free-web.aspx
	 * @see edge()
	 */
	public function metroActivex()
	{
		return $this->httpEquiv('X-UA-Compatible', 'IE=Edge,chrome=1,requiresActiveX=true');
	}

}
