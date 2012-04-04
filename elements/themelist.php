<?php defined('_JEXEC') or die;
/**
 * Provides a list of CDN Sources
 *
 * @package     Construc2
 * @subpackage  Elements
 * @copyright	(C)2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
require_once dirname(__FILE__) . '/theme.php';

JFormHelper::loadFieldClass('filelist');

/**
 * Provides a list of Theme files
 */
class JFormFieldThemeList extends JFormFieldFileList
{
	/**
	 * @var string Field type
	 */
	public $type = 'ThemeList';

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
		CustomTheme::getInstance()->setForm($form);

		// document.querySelector('p.tip')
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
			$this->element['exclude'] = '(template|editor|tinymce|codemirror|jce|ie\d+|test.+|x~.+)\.css';
		} else {
			$this->element['filter']  = '\.css';
			$this->element['exclude'] = '';
		}

		$options = parent::getOptions();

		return $options;
	}
}
