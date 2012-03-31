<?php
/**
 * Apache SSI based .styles and .scripts
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

class ElementFeatureSsi extends ElementFeature
{
	protected $name = 'ssi';

	public function render()
	{
		$this->set('link', array('href'=>'{tmpl.css}/construc2.styles?v=2', 'rel'=>'stylesheet'));
		$this->set('link', array('href'=>'{theme}.styles?v=2', 'rel'=>'stylesheet'));
	}
}
