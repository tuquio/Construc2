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
	 * Instatiate the theme files select list.
	 *
	 * @param JForm|null $form
	 */
	public function __construct($form = null)
	{
		parent::__construct($form);
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param  JForm  $form  The JForm object to attach to the form field.
	 *
	 * @return object The form field object so that the method can be used in a chain.
	public function setForm(JForm $form)
	{
		parent::setForm($form);
		return $this;
	}
	 */

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param	object	$element	The JXmlElement representing the <field />.
	 * @param	mixed	$value		The form field value to validate.
	 * @param	string	$group		The field name group control value.
	 * The $group acts as as an array container for the field. For example if
	 * the field has name="foo" and the group value is set to "bar" then the
	 * full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 */
	public function setup(JXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);
		if (defined('DEVELOPER_MACHINE')) {
			JFactory::getDocument()->addScriptDeclaration('console.info( "JFormFieldThemelist '. $this->name .' initializing! " );');
		}
		return $result;
	}

	/**
	 * Method to get the list of files for the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$this->element['directory'] = basename(JPATH_THEMES).'/construc2/themes';

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

	/**
	 * @return  string  The basename of the file (theme)
	 */
	public function getTheme()
	{
		$name = explode('.', $this->value .'.');
		return $name[0];
	}

	/**@#+
	 * Prepare form, fired on form preparation (before content plugins)
	 *
	 * $data feat. a record from the __extensions table.
	 * - xml: JXMLElement of the manifest
	 * - params: array of current params
	 *
	 * @param JForm  $form
	 * @param array|JObject $data array "after save", JObject "on read" %-/
	 */
	public function onContentPrepareForm(JForm $form, $data)
	{
		FB::log($data, $form->getName() .' @ '. __METHOD__);
	}

}
