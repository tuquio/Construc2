<?php
/**
 * Diagnostic stylesheets, meta data, and script tester.
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

class ElementFeatureDiagnostic extends ElementFeature
{
	protected $name = 'diagnostic';

	/**
	 * @todo "export" $catId, $itemId, $articleId to template
	 */
	public function render()
	{
		$app    = JFactory::getApplication();
		$menu   = $app->getMenu();
		$active = $menu->getActive();

		// get some numbers and add them somehow to the template :-)
		$catId = $itemId = $articleId = '';
		if ($active->component == 'com_content') {
			$itemId = $active->id;
			if (isset($active->query['id'])) {
				$articleId = $active->query['id'];
			}
		}

		$this->set('link', array('href'=>'{tmpl.css}/core/diagnostic.css', 'title'=>'Diagnostic', 'rel'=>'alternate stylesheet'));
	}
}
