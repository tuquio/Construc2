<?php defined('_JEXEC') or die;
/**
* @package		Template Framework for Joomla! 1.6
* @author		Joomla Engineering http://joomlaengineering.com
* @copyright	Copyright (C) 2010 Matt Thomas | Joomla Engineering. All rights reserved.
* @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * JFormFieldGooglewebfont
 *
 * Provides list of Google Web Fonts
 *
 * @static
 * @package		Molajo
 * @subpackage  HTML
 * @since		1.6
 */
class JFormFieldJqueryversion extends JFormFieldList
{
    /**
     * Field Type
     *
     * @var		string
     * @since	1.6
     */
    public $type = 'Jqueryversion';

    /**
     * getOptions
     *
     * Generates list options
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    protected function getOptions()
    {
        static $versions = array(
        		'1.6.2','1.6.1','1.6.0',
        		'1.5.2','1.5.1','1.5.0',
        		'1.4.4','1.4.3','1.4.2','1.4.1','1.4.0',
        		'1.3.2','1.3.1','1.3.0',
        		'1.2.6','1.2.0',
        		);
        $options	= array();

		$options[]	= JHtml::_('select.option', '', '- Not Loaded -');
		/* using protocol relative URLs */
		$options[]	= JHtml::_('select.option', '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', JText::_('TPL_JE_CONSTRUCT_COMMUNITY_JQUERY_LATEST'));
		foreach ($versions as $v) {
			$options[]	= JHtml::_('select.option', '//ajax.googleapis.com/ajax/libs/jquery/'. $v .'/jquery.min.js', $v);
		}

		return $options;

    }
}