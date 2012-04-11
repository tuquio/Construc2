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

// add some paths
JFormHelper::addFormPath(WMPATH_ELEMENTS . '/elements');
JFormHelper::addFieldPath(WMPATH_ELEMENTS . '/elements/features');
JFormHelper::addFieldPath(WMPATH_ELEMENTS . '/elements/widgets');

/**
 * Provides a list of Theme files
 */
class JFormFieldThemelist extends JFormFieldFileList
{
	/**
	 * @var string Field type
	 */
	public $type = 'Themelist';

	protected $_doc;

	/**
	 * Instatiate the theme select list.
	 *
	 * @param JForm|null $form
	 */
	public function __construct($form = null)
	{
		parent::__construct($form);

		$this->_doc = JFactory::getDocument();
//		if (defined('DEVELOPER_MACHINE')) {
//			$this->_doc->addScriptDeclaration('console.info( "Theme parameters initializing! " );');
//		}
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

		$theme = CustomTheme::getInstance($value)->setForm($this->form);
//		if (defined('DEVELOPER_MACHINE')) {
//			$this->_doc->addScriptDeclaration('console.info("'.__METHOD__ .'",'."$theme" .');');
//		}

		return $result;
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
		FB::log($data, __METHOD__);
	}

	/**
	 * Prepare data, fired on data preparation
	 * com_contact context: 'com_users.profile'
	 * com_user contexts: 'com_users.profile', 'com_users.registration'
	 * @param string $context
	 * @param object $data
	 */
	public function onContentPrepareData($context, $data)
	{
		FB::log($data, __METHOD__);
	}

}
