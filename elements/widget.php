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
	/**
	 * Allows to replace standard JHtml widgets and behaviors with
	 * better alternatives.
	 *
	 * @param string $key      dot-notation according to JHtml rules
	 * @param string $callback new handler
	 * @return void
	 */
	public static function better($key, $callback)
	{
		JHtml::unregister($key);
		JHtml::register($key, $callback);
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
