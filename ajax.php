<?php
/**
 * Ajax Request Application
 *
 * @version    0.5.0
 * @package    WebMechanic Joomla TuneUps
 * @copyright  Copyright (C)2011-2012 webmechanic.biz. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

// Set flag that this is a parent file
define('_JEXEC', 1);

$path = dirname(__FILE__);
$path = substr($path, 0, strpos($path, DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR) );

ob_start();
if (file_exists($path . '/defines.php')) {
	include_once $path . '/defines.php';
}
if (!defined('_JDEFINES')) {
	define('JPATH_BASE', $path);
	require_once JPATH_BASE . '/includes/defines.php';
}
require_once JPATH_BASE . '/includes/framework.php';
ob_end_clean();

class Domestos
{
	protected $categories;
	protected $template;
	protected $helper;

	public function __construct()
	{
		$this->template = JFactory::getApplication()->getTemplate();
	}

	public function execute()
	{
		JPluginHelper::importPlugin('system');
		JFactory::getApplication()->triggerEvent('onAfterInitialise');

		return $this;
	}

	public function render($identifier = 'com_content.article.default')
	{
		$app = JFactory::getApplication();

		list($option, $view, $layout) = explode('.', $identifier);
		$content = '';

		// will include the module path(!)
		$this->helper->baseurl = JURI::getInstance()->base(true);
		$this->helper->modurl  = $this->helper->baseurl;

		JResponse::allowCache(false);
		JResponse::setHeader('Content-Type', 'text/html; charset=utf-8');
		JResponse::setBody('');

		/*
		 * find and include a template and render it
		 */
		if ($option == 'com_content') {
			//	$content = $this->_renderArticle($option, $view, $layout)
		}

		JResponse::prependBody( $content );

		echo JResponse::toString();
	}

	protected function _renderArticle($option, $view, $layout='default')
	{
		$app = JFactory::getApplication();

		// clean buffer
		while(@ob_end_clean());
		$Itemid = $app->input->getInt('Itemid');
		$jmenu  = $app->getMenu()->getItem($Itemid);

		JPluginHelper::importPlugin('content');

		// pretend to be a component
		$app->input->set('option',	$option);
		$app->input->set('view',	$view);
		$app->input->set('format',	'html');
		// template override
		$app->input->set('layout',	$layout);
		// template startscript (stripped down component.php)
		$app->input->set('tmpl',	'embed');

		return JComponentHelper::renderComponent($option);
	}

	protected function checkTemplate()
	{
		$src  = dirname(__FILE__).'/tmpl/plain.php';
		$dest = JPATH_THEMES .'/'.$this->template .'/plain.php';
		if (!file_exists(JPATH_THEMES .'/'.$this->template .'/plain.php')) {
			jimport('joomla.filesystem.file');
			return JFile::copy($src, $dest);
		}
		return array($src, $dest);
	}

}

// for the inner workings, we need this global
JFactory::getApplication('site')->initialise();

$domestos = new Domestos();
$domestos->execute()->render();

