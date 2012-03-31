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
	protected $theme;

	public function render($name, $params = array (), $content = null)
	{
		if (!isset($params['tic'])) {
			return '';
		}
		if ('tac' != $params['tic']) {
			return '';
		}

		ob_start();

		echo '<xmp id="toe">', __METHOD__,
			PHP_EOL, 'name: ', $name,
			PHP_EOL, '$params: ', print_r($params, true), '</xmp>';
		echo '<xmp class="DbgPrint">$content: ', print_r($content, true), '</xmp>';

		echo '<xmp class="DbgPrint">getFeatures: ', print_r($this->theme->getFeatures(), true), '</xmp>';
		echo '<xmp class="DbgPrint">Renderers: ', print_r($this->theme->getChunk('meta'), true), '</xmp>';
/*
		echo '<xmp class="DbgPrint">Config: ', print_r(ConstructTemplateHelper::getInstance()->getConfig(null), true), '</xmp>';
		echo '<xmp class="DbgPrint">Theme Cfg: ', print_r($this->theme->getConfig(null), true), '</xmp>';
*/
		echo '<xmp class="DbgPrint">getHeadData: ', print_r($this->_doc->getHeadData(), true), '</xmp>';

		$toe = ob_get_contents();
		ob_end_clean();

		return $toe;
	}

	public function __construct(JDocument $document)
	{
		$this->_doc  = $document;
		$this->theme = ConstructTemplateHelper::getInstance()->getTheme();
	}

	public function build(array &$data, $options=null) {return $this;}
	public function set($key, $value, $ua=null) {return $this;}

}
