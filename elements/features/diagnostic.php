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

	public function render()
	{
		$app   = JFactory::getApplication();
		$jmenu = $app->getMenu();
		$amenu = $jmenu->getActive();

		// get some numbers and add them somehow to the template :-)
		$catId = $itemId = $articleId = '';
		if ($amenu->component == 'com_content') {
			$itemId 	= $amenu->id;
			if (isset($amenu->query['id'])) {
				$articleId = $amenu->query['id'];
			}
		}

		$this->set('link', array('href'=>'{tmpl.css}/core/diagnostic.css', 'title'=>'Diagnostic', 'rel'=>'alternate stylesheet'));
	}
}
