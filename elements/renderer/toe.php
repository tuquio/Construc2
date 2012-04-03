<?php
/**
 * Demo mplementation of <jdoc:include type="toe" push="scripts" /> to push
 * analytics scripts and late bound stuff into so they don't block page rendering.
 *
 * @package     Construc2
 * @subpackage  Renderer
 * @copyright   (C)2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

class JDocumentRendererToe extends ElementRendererAbstract
{
	protected $name = 'toe';
	/** @var CustomTheme $theme */
	protected $theme;

	/**
	 * @param       $name
	 * @param array $params
	 * @param null  $content
	 * @return string
	 */
	public function render($name, $params = array (), $content = null)
	{
		if (!isset($params['tic'])) {
			return '';
		}
		if ('tac' != $params['tic']) {
			return '';
		}

		@include dirname() .'/tictac.php';

		return '';
	}

	/**
	 * @param \JDocument|null $document For the most part JDocumentHtml
	 */
	public function __construct(JDocument $document)
	{
		$this->_doc  = $document;
		$this->theme = ConstructTemplateHelper::getInstance()->getTheme();
	}

	/**
	 * @param array $data
	 * @param null  $options
	 * @return JDocumentRendererToe
	 */
	public function build(array &$data, $options=null) {return $this;}

	/**
	 * @param      $key
	 * @param      $value
	 * @param null $ua
	 * @return JDocumentRendererToe
	 */
	public function set($key, $value, $ua=null) {return $this;}

}
