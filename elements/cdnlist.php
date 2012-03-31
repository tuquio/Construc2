<?php defined('_JEXEC') or die;
/**
 * Provides a list of CDN Sources
 *
 * @package     Construc2
 * @subpackage  Elements
 * @copyright	(C)2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Provides a list of CDN Sources
 */
class JFormFieldCdnlist extends JFormFieldList
{
	/**
	 * @var string Field type
	 */
	public $type = 'Cdnlist';

	/**
	 * Generates list options
	 *
	 * @return	array	The field option objects.
	 */
	protected function getOptions()
	{
		$options = $config = array();

		if (is_file(WMPATH_ELEMENTS .'/settings.php')) {
			$config = parse_ini_file(WMPATH_ELEMENTS .'/settings.php', true);
		}
		settype($config['cdn'], 'array');

		$options[] = JHtml::_('select.option', '', '- Not Loaded -');
		$options[] = JHtml::_('select.option', '@default'	, JText::_('Cdnlist_Source_Default'));
		$options[] = JHtml::_('select.option', '@media'		, JText::_('Cdnlist_Source_Media'));
		$options[] = JHtml::_('select.option', '@template'	, JText::_('Cdnlist_Source_Template'));

		if (count($config['cdn']))
		{
			$members = (array)$this->element->attributes()->members;

			foreach ($config['cdn'] as $cdn => $data)
			{
				// "0,foo,..." vs "1,foo,..."
				if (!(int) $data) continue;

				$data = explode(',', $data);
				array_shift($data);
				foreach (array_intersect($members,$data) as $opt) {
					$options[]	= JHtml::_('select.option', $cdn, "$cdn ($opt)");
				}
			}
		}

		return $options;
	}
}
