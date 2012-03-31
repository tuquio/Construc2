<?php
/**
 * Implementation of <jdoc:include type="toe" name="foobar" foo="bar" />
 * to push analytics scripts and late bound stuff into so they
 * don't block page rendering.
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
		echo '<xmp>$content: ', print_r($content, true), '</xmp>';

		echo '<xmp>getFeatures: ', print_r($this->theme->getFeatures(), true), '</xmp>';
		echo '<xmp>Renderers: ', print_r($this->theme->getChunk('meta'), true), '</xmp>';
/*
		echo '<xmp>Config: ', print_r(ConstructTemplateHelper::getInstance()->getConfig(null), true), '</xmp>';
		echo '<xmp>Theme Cfg: ', print_r($this->theme->getConfig(null), true), '</xmp>';
*/
		echo '<xmp>getHeadData: ', print_r($this->_doc->getHeadData(), true), '</xmp>';

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
