<?php
/**
 * Some workarounds against and features for a family of browser emulator.
 *
 * - browserchoice:  BrowserChoice.com Redirect
 * - edge:  X-UA-Compatible  !! THIS BETTER GOES INTO .htaccess
 * - bho: kick imagetoolbar, MSThemeCompatible
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

class ElementFeatureMsie extends ElementRendererMeta
{
	protected $name = 'msie';
	protected $renderer = array(
				'bho'=>'meta',
				'browserchoice'=>'meta',
				'cfinstall'=>'script',
				'edge'=>'meta',
			);

	// kick "browser helper objects" imagetoolbar, MSThemeCompatible
	public function bho()
	{
		$this->httpEquiv('imagetoolbar', 'no');
		$this->httpEquiv('MSThemeCompatible', 'no');

	}

	// BrowserChoice.com Popup or Redirect
	public function browserchoice()
	{
		$this->set('meta', 'refresh', '2;url=http://browserchoice.eu/', 'lt IE 9');
	}

	// Google Chrome Frame Install for oldIEs
	public function cfinstall()
	{
		$version = '1.0.3';

		$href    = '//ajax.googleapis.com/ajax/libs/chrome-frame/'. $version .'/CFInstall.min.js';
		$attribs = array('src'=>$href, 'async'=>true, 'onload'=>'var e=document.createElement("DIV");if(e && CFInstall){e.id="gcf_placeholder";e.style.zIndex="9999";CFInstall.check({node:"gcf_placeholder"});}');

		$this->set('script', $attribs, 'lt IE 9');
	}

	// X-UA-Compatible E=Edge,chrome=1
	// @link http://h5bp.com/i/378  X-UA-Compatible
	public function edge()
	{
		//#FIXME pick ElementRendererHead and "inject" BEFORE data['base']
		$this->httpEquiv('X-UA-Compatible', 'IE=Edge,chrome=1');
	}

}
