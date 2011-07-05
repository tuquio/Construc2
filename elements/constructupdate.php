<?php defined('_JEXEC') or die;
/**
* @package		Template Framework for Joomla! 1.6
* @author		Joomla Engineering http://joomlaengineering.com
* @copyright	Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
* @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

class JFormFieldConstructupdate extends JFormField {

	protected $_name		= 'Constructupdate';

	protected static $version	= 656;

	protected function getInput()
	{
		return ' ';
	}

	protected function getLabel()
	{
		//check for cURL support before we do anyting esle.
		if (!function_exists("curl_init")) {
			return JText::_('TPL_JE_CONSTRUCT_COMMUNITY_VERSION_CURL_ERROR');
		}

		$construct = $this->_upgradeCheck();
		$webfonts  = $this->_webfontsCheck();
		return $construct . $webfonts;
	}

	/**
	 * Upgrade check for Contruct
	 * @return string
	 */
	protected function _upgradeCheck()
	{
		$target = 'http://joomlaengineering.com/upgradecheck/je-construct-community-1-6';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $target);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$str = curl_exec($curl);
		curl_close($curl);

		$message = '<label style="max-width:100%">You are using Construct Community version 1.6.'.self::$version.'. ';

		//If the current version is out of date, notify the user and provide a download link.
		if (self::$version < $str) {
			$message = $message . '<a href="http://joomlaengineering.com" target="_blank">Version 1.6.'.$str.' is now available.</a><br /><a href="http://joomlaengineering.com/construct-community-1-6-changelog" target="_blank">See what&rsquo;s new</a>.</label>';
		}
		//If the current version is up to date, notify the user.
		elseif ((self::$version == $str) || (self::$version > $str)) {
			$message = $message . 'There are no updates available at this time.<br /><a href="http://joomlaengineering.com/construct-community-1-6-changelog" target="_blank">View the change log</a>.</label>';
		}
		return $message;
	}

	/**
	 * Upgrade check for Google Web Font list
	 * @return string
	 */
	protected function _webfontsCheck()
	{
		$message = 'No new Web Fonts found.';
		return $message;
	}

}