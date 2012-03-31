<?php
/**
 * Returns the given date (or today) wrapped in separate HTML elements to
 * apply dedicated formatting of each date fragent, day, month, year, via CSS.
 *
 * @package     Construc2
 * @subpackage  Widgets
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * JHtmlIcon clones as offerend and used by com_content.
 */
class ElementWidgetDate extends ElementWidget
{
	/**
	 * To set the $elt name only, but preseve (todays) default and date format use:
	 * <samp>datecontainer(null, null, 'kbd')</samp>
	 *
	 * @param  number  $date 	defaults to 'now' (also if null or false are provided)
	 * @param  string  $format	a valid DateTime format or language key, default: DATE_FORMAT_LC4
	 * @param  string  $elt		defaults to 'span' as the date fragment wrapper element
	 * @return string
	 */
	static public function container($date='now', $format='DATE_FORMAT_LC4', $elt='span')
	{
		// format keys for day and month numbers and names and their mapping to a $markup
		static
		$keylist = array('D'=>1,'l'=>1,'d'=>2,'j'=>2,'F'=>3,'M'=>3,'m'=>4,'n'=>4,'o'=>5,'Y'=>5,'y'=>5),
		$markup  = array(	0 => '',
							1 => '<%s class="date-day txt">%s</%s>',
							2 => '<%s class="date-day num">%s</%s>',
							3 => '<%s class="date-month txt">%s</%s>',
							4 => '<%s class="date-month num">%s</%s>',
							5 => '<%s class="date-year num">%s</%s>');

		if (!$format) $format = 'DATE_FORMAT_LC4';
		if (!$date) $date = 'now';
		$now  = new JDate($date);

		$html = array();
		$out  = (substr($format, 0, 10)=='DATE_FORMAT')
				? JText::_($format)
				: $format;

		$m = preg_split('/\W/', $out);
		if (count($m))
		{
			foreach ($m as $i => $k)
			{
				$char = "@{$k}{$i}@";
				$out = str_replace($k, $char, $out);
				if (isset($keylist[$k])) {
					$html[$char] = sprintf($markup[$keylist[$k]], $elt, $now->format($k, true, true), $elt);
				} else {
					$html[$char] = $now->format($k, true, true);
				}
			}
		}

		return str_replace(array_keys($html), array_values($html), $out);
	}

}
