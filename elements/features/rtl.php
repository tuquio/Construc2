<?php
/**
 * Right to left script support.
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

class ElementFeatureRtl extends ElementFeature
{
	protected $name = 'rtl';

	public function render()
	{
		$this->set('link', array('href'=>'{tmpl.css}/core/rtl.css', 'rel'=>'stylesheet'));
	}
}
