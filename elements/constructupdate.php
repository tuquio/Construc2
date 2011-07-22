<?php defined('_JEXEC') or die;
/**
 * @package		Templates
 * @author		Joomla Engineering http://joomlaengineering.com
 * @copyright	Copyright (C) 2010, 2011 Matt Thomas | Joomla Engineering. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * @package    Templates
 * @subpackage Elements
 */
class JFormFieldConstructupdate extends JFormField {

	protected $_name	= 'Constructupdate';

	protected static $version	= '1.6.0';
	protected static $major	= '1.6';
	protected static $rev	    = '0';

	protected function getInput() { return ''; }

	protected function getLabel()
	{
		// check for cURL support before we do anything else
		if (!function_exists("curl_init")) {
			return JText::_('TPL_JE_CONSTRUCT_COMMUNITY_VERSION_CURL_ERROR');
		}

		// fetch version from xml manifest
		$params = TemplatesHelper::parseXMLTemplateFile(JPATH_SITE, 'construct');
		self::$version = $params->version;

		$construct = $this->_upgradeCheck();
		$webfonts  = $this->_webfontsCheck();
		return '<label style="max-width:100%;clear:both">'. $construct .'<br style="clear:both" />'. $webfonts . '</label>';
	}

	/**
	 * Upgrade check for Contruct
	 * @return string
	 */
	protected function _upgradeCheck()
	{

	// local devbox, httpd.conf: SetEnv DEVBOX yep
	if ( @getenv('ENTWICKLUNG') || @getenv('DEVBOX') ) {
		$str = self::$version;
	} else {
		$target = 'http://joomlaengineering.com/upgradecheck/je-construct-community-1-6';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $target);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		// build a PHP-ish version number
		$str = self::$major .'.'. (int)curl_exec($curl);
		curl_close($curl);
	}

		$message = JText::sprintf('TPL_JE_CONSTRUCT_COMMUNITY_VERSION_CHECK_USING', self::$version) .'<br />';

		// If the current version is out of date, notify the user and provide a download link.
		if ( version_compare(self::$version, $str, 'lt') ) {
			$message .= '<a href="http://joomlaengineering.com" target="_blank">'
					.	JText::sprintf('TPL_JE_CONSTRUCT_COMMUNITY_VERSION_CHECK_UPDATE', $str)
					.	'</a><br /><a href="http://joomlaengineering.com/construct-community-1-6-changelog" target="_blank">'
					.	JText::_('TPL_JE_CONSTRUCT_COMMUNITY_VERSION_CHECK_WHATSNEW')
					.	'</a></label>';
		}
		// If the current version is up to date, notify the user and provide a link to the change log
		else {
			$message .= JText::_('TPL_JE_CONSTRUCT_COMMUNITY_VERSION_CHECK_NOUPDATE')
					.	'<br /><a href="http://joomlaengineering.com/construct-community-1-6-changelog" target="_blank">'
					.	JText::_('TPL_JE_CONSTRUCT_COMMUNITY_VERSION_CHECK_CHANGELOG')
					.	'</a>';
		}

		return $message;
	}

	/**
	 * Upgrade check for Google Web Font list
	 * @return string
	 */
	protected function _webfontsCheck()
	{
		$message = JText::plural('TPL_JE_CONSTRUCT_COMMUNITY_WEBFONTS_CHECK_UPDATE', 0);

		return $message;
	}

}