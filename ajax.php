<?php
/**
 * Ajax Request Application
 *
 * @version    0.4.0
 * @package    WebMechanic Joomla TuneUps
 * @copyright  Copyright (C)2011 webmechanic.biz. All rights reserved.
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
		global $mainframe;

		JFactory::getUser();

		$this->template = $mainframe->getTemplate();
	}

	public function execute()
	{
		global $mainframe;
		require dirname(__FILE__) . '/helpers/helper.php';
		jimport('joomla.application.module.helper');

		JPluginHelper::importPlugin('system');
		$mainframe->triggerEvent('onAfterInitialise');

		return $this;
	}

	public function render($identifier = 'com_content.article.default')
	{
		global $mainframe;
		list($option, $view, $layout) = explode('.', $identifier);
		$content = '';
		if ($option == 'com_content') {
		//	$content = $this->_renderArticle($option, $view, $layout)
		}

		$id     = JRequest::getInt('id', 0);
		$catid  = JRequest::getInt('catid', 0);
		$filter = JRequest::getString('filter'); // 23,1,13,24

		$module         = new stdClass;
		$module->module = 'mod_multidrilldown';
		$this->helper   = new DrilldownHelper($module, null);

		// will include the module path(!)
		$this->helper->baseurl = JURI::getInstance()->base(true);
		$this->helper->modurl  = $this->helper->baseurl;

		JResponse::allowCache(false);
		JResponse::setHeader('Content-Type', 'text/html; charset=utf-8');
		JResponse::setBody('');

		$content = '';

		// plain ajax template file available
		$plainthere = $this->checkTemplate();
		if ($plainthere == true) {
			if ($catid > 0) {
				$content = $this->_renderMulticategory($catid, $filter);
			}
		}
		else {
			$content = '<p class="error">Template nicht gefunden (plain)</p>'
					. implode('<br>', $plainthere);
		}

		JResponse::prependBody( $content );

		echo JResponse::toString();
	}

	protected function _renderMulticategory($catid, $filter)
	{
		global $mainframe;
		$menu      = $mainframe->getMenu();

		$config    = array('catid'=>$catid, 'filter'=>$filter);
		$this->helper->getTree($config);

		if ( !isset($this->helper->cats[$catid]) ) {
			return JText::_('Not found');
		}

		$category     = &$this->helper->cats[$catid];
		$catroute     = $this->helper->getMulticategoryRoute($catid);

		$this->items  = $this->helper->getArticles($category->articles);
		$num_articles = count($this->items);

		// for JRoute::_() pretend to be com_content
		JRequest::setVar('option',	'com_content');
		JRequest::setVar('view',	'article');
		JRequest::setVar('format',	'html');
		JRequest::setVar('layout',	'default');

		unset($config);

		ob_start();
		require JModuleHelper::getLayoutPath('mod_multidrilldown', 'drilldown');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	protected function _renderArticle($option, $view, $layout='default')
	{
		global $mainframe;
		// clean buffer
		while(@ob_end_clean());
		$Itemid = JRequest::getInt('Itemid');
		$jmenu  = $mainframe->getMenu()->getItem($Itemid);

		JPluginHelper::importPlugin('content');

		// pretend to be a component
		JRequest::setVar('option',	$option);
		JRequest::setVar('view',	$view);
		JRequest::setVar('format',	'html');
		// template override
		JRequest::setVar('layout',	$layout);
		// template startscript (stripped down component.php)
		JRequest::setVar('tmpl',	'plain');

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

