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

class JDocumentRendererToe extends ElementRendererAbstract implements IElementRenderer
{
	protected $name = 'toe';
	/** @var CustomTheme $theme */
	protected $theme;

	/**
	 * For API compliance with {@link JDocumentRenderer}
	 * @param string $name      The name of the element to render
	 * @param array  $params    Array of values
	 * @param null   $content   Override the output of the renderer
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

		@include dirname(__FILE__) .'/tictac.php';

		return '';
	}

	/**
	 * API compliance with {@link JDocumentRenderer}
	 * @param \JDocument|null $_document For the most part JDocumentHtml
	 */
	public function __construct(JDocument $_document)
	{
		parent::__construct($_document);

		$this->_doc  = $_document;
		$this->theme = ConstructTemplateHelper::getInstance()->getTheme();
	}

	/**
	 * @inherit
	 * @return JDocumentRendererToe
	 */
	public function build(array &$data, $options=null) {return $this;}

	/**
	 * @inherit
	 * @return JDocumentRendererToe
	 */
	public function set($key, $value, $ua=null) {return $this;}

}
