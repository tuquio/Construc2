<?php defined('_JEXEC') or die;
/**
 * Provides the list of CSS and SSI themes.
 * Also load the theme class which (among other things) defines some constants
 * use by other form elements.
 *
 * @package     Construc2
 * @subpackage  Elements
 * @copyright	(C)2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
require_once dirname(dirname(__FILE__)) . '/theme.php';

JFormHelper::loadFieldClass('filelist');

/**
 * Provides a list of Theme files
 */
class JFormFieldThemelist extends JFormFieldFileList
{
	/**
	 * @var string Field type
	 */
	public $type = 'Themelist';

	/**
	 * Instatiate the theme select list.
	 *
	 * @param JForm|null $form
	 */
	public function __construct($form = null)
	{
		parent::__construct($form);
		$doc = JFactory::getDocument();
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param  JForm  $form  The JForm object to attach to the form field.
	 *
	 * @return object The form field object so that the method can be used in a chain.
	 */
	public function setForm(JForm $form)
	{
		parent::setForm($form);
		$theme = CustomTheme::getInstance()->setForm($form);
		// document.querySelector('p.tip')
FB::log($theme, __METHOD__);
		return $this;
	}

	/**
	 * Method to get the list of files for the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$this->element['directory'] = 'templates/construc2/themes';

		if ('ssi' == (string) $this->element['themetype']) {
			$this->element['filter']  = '\.styles';
			$this->element['exclude'] = '';
		} else {
			$this->element['filter']  = '\.css';
			$this->element['exclude'] = '(template|editor|tinymce|codemirror|jce|ie\d+|test.+|x~.+)\.css';
		}

		$options = parent::getOptions();

		// remove "Use Default" - there is none
		unset($options[1]);

		return $options;
	}
}
