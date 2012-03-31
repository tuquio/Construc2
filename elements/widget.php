<?php
/**
 * Widgets Abstract Class.
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
define('WMPATH_WIDGETS' , WMPATH_ELEMENTS .'/widgets');

/** Register Standard Widget Classes */
JLoader::register('ElementWidgetContent', WMPATH_WIDGETS . '/content.php');

class ElementWidget extends ElementFeature
{
	static public function better($old, $new)
	{
		JHtml::unregister($old);
		JHtml::register($old, $new);
	}
}
