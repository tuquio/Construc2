<?php
/**
 * WYSIWYG Editor styles
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

class ElementFeatureEdit extends ElementFeature
{
	protected $name = 'edit';

	public function render()
	{
		$this->set('link', array('href'=>'{tmpl.css}/core/forms.css', 'rel'=>'stylesheet'));
		$this->set('link', array('href'=>'{tmpl.css}/core/edit-form.css', 'rel'=>'stylesheet'));
	}
}
