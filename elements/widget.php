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

	/**
	 * @see JModelForm::loadForm()
	 */
	protected function loadForm()
	{
		// Get the form.
		JForm::addFormPath(WMPATH_WIDGETS . '/forms');
		JForm::addFieldPath(WMPATH_WIDGETS . '/fields');
	}
}

class JFormFieldWidget extends JFormField
{
	protected $type = 'WIdget';

	public function __construct($form = null)
	{
		parent::__construct($form);
	}

	protected function getInput()
	{
		$html = '';

		return $html;
	}

	/**@#+
	 * Proxies for logging and debugging
	 * @ignore
	 */
	protected function getFieldName($fieldName)
	{
		return parent::getFieldName($fieldName);
	}

	protected function getId($fieldId, $fieldName)
	{
		return parent::getId($fieldId, $fieldName);
	}

	protected function getTitle()
	{
		return parent::getTitle();
	}

	protected function getLabel()
	{
		return parent::getLabel();
	}

	protected function getName($fieldName)
	{
		return parent::getName($fieldName);
	}
	/**@#- */
}
