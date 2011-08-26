<?php defined('_JEXEC') or die;
/**
 * @package		Templates
 * @author		Joomla Engineering http://joomlaengineering.com
 * @copyright	Copyright (C) 2010 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Provides a list of jQuery versions
 *
 * @package    Templates
 * @subpackage Elements
 */
class JFormFieldJqueryversion extends JFormFieldList
{
	/**
	 * @var string Field type
	 */
	public $type = 'Jqueryversion';

	protected static $versions = array(
		'1.6.2','1.6.1','1.6.0',
		'1.5.2','1.5.1','1.5.0',
		'1.4.4','1.4.3','1.4.2','1.4.1','1.4.0',
		'1.3.2','1.3.1','1.3.0',
		'1.2.6','1.2.0',
	);

	/**
	 * Generates list options
	 *
	 * @return	array	The field option objects.
	 */
	protected function getOptions()
	{
		$options	= array();

		$options[]	= JHtml::_('select.option', '', '- Not Loaded -');
		/* using protocol relative URLs */
		$options[]	= JHtml::_('select.option', '1', JText::_('TPL_JE_CONSTRUCT_COMMUNITY_JQUERY_LATEST'));
		foreach (self::$versions as $v) {
			$options[]	= JHtml::_('select.option', $v, $v);
		}

		return $options;
	}
}