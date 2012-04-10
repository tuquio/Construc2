<?php
/**
 * Widgets Abstract Class.
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/** Register Standard Widget Classes */
JLoader::register('ElementWidgetContent', WMPATH_TEMPLATE . '/elements/widgets/content.php');

/**
 * Generic Widget Class.
 */
class ElementWidget extends ElementFeature
{
	static public function better($old, $new)
	{
		JHtml::unregister($old);
		JHtml::register($old, $new);
	}

	/**
	 * Loads the xml form and configuration.
	 * @see JModelForm::loadForm()
	 * @uses JForm::addFormPath(), JForm::addFieldPath()
	 */
	protected function loadForm()
	{
		// Get the form.
		JForm::addFormPath(WMPATH_TEMPLATE . '/elements/widgets/forms');
		JForm::addFieldPath(WMPATH_TEMPLATE . '/elements/widgets/fields');
	}
}
