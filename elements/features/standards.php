<?php
/**
 * Welcome to 21st century with modern Web Standards Enhancements, incl.
 *
 * - html5:  HTML5 element support
 * - json:  JSON2 support
 * - prefixfree:  Lea Verou's -prefix-free
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

class ElementFeatureStandards extends ElementFeature
{
	protected $name = 'standards';

	// HTML5 element support
	public function html5()
	{
		$this->set('script', array('src'=>'{tmpl.js}/html5.js', 'async'=>true), 'lt IE 9');
	}

	// JSON2 support (also available as of J2.5.4 in /media/com_joomlaupdate)
	public function json()
	{
		$script = $this->loader('{tmpl.js}/json2.min.js', 'if (W.JSON) {return;}');
		$this->set('scripts', $script, 'lt IE 9');
	}

	// Lea Verou's -prefix-free
	public function prefixfree()
	{
		$this->set('script', array('src'=>'{tmpl.js}/prefixfree.min.js', 'async'=>true));
	}

}
