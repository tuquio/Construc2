<?php
/**
 * ElementFeature parameter forms.
 *
 * @package     Construc2
 * @subpackage  Features
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Base class for ElementFeature parameter forms.
 */
class JFormFieldFeature extends JFormField
{
	protected $type = 'Feature';

	/**
	 * @internal high-jacking
	 * @param null|JForm $form
	 * @ignore
	 */
	public function __construct($form = null)
	{
		parent::__construct($form);
	}

	/**
	 * Method to get the field input markup.
	 * @return string
	 */
	protected function getInput()
	{
		$html = '';

		return $html;
	}

	/**@#+
	 * @internal Proxies for logging and debugging __get()
	 */
	protected function getFieldName($fieldName)
	{
		return parent::getFieldName($fieldName);
	}

	/**
	 * @return string
	 * @ignore
	 */
	protected function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 * @ignore
	 */
	protected function getLabel()
	{
		return parent::getLabel();
	}

	/**
	 * @return string
	 * @ignore
	 */
	protected function getName($fieldName)
	{
		return parent::getName($fieldName);
	}

	/**@#- */

}
