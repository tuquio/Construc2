<?php defined('_JEXEC') or die;
/**
 * Construc2 Template Theme.
 *
 * @package     Construc2
 * @subpackage  Elements
 * @copyright   (C) 2011-2012 WebMechanic. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

class CustomTheme
{
	protected $name    = 'default';
	protected $title   = 'Default';
	protected $version = '';
	protected $author  = '';

	protected $path    = '';
	protected $url     = '';

	protected $styles  = array();
	protected $scripts = array();
	protected $layouts = array();

	public function __construct(ConstructTemplateHelper $helper, $options = array())
	{
		$doc = JFactory::getDocument();
		$ssi = (bool) $doc->params->get('ssiIncludes', 0);

		if ($ssi) {
			$theme = $doc->params->get('ssiTheme');
			$theme = basename($theme, '.styles');
		}
		else {
			$theme = $doc->params->get('customStyleSheet');
			$theme = basename($theme, '.css');
		}

		$this->name = $theme;

		// use "default" == 'template.css"
		if ((int) $theme == -1)
		{
			$helper->addLink('template.css');
		}

		$this->path = JPATH_THEMES .'/'. $doc->template .'/themes/'. $this->name . '.php';
		$this->url  = JUri::root(true) .'/templates/'. $doc->template .'/themes';

		if (is_file($this->path))
		{
			// fake ini file
			$config = @parse_ini_file($this->path, true);
			if (!$config || count($config) == 0) {
				break;
			}

			$this->styles  = (array) @$config['styles'];
			$this->scripts = (array) @$config['scripts'];
			$this->layouts = (array) @$config['layouts'];

			foreach ($this->layouts as $layout)
			{
				list ($basename, $scope) = explode(',', $layout . ',');
				$helper->addLayout($basename, $scope);
			}
		}
	}

	public function build(ConstructTemplateHelper $helper)
	{
		if (isset($this->scripts))
		{
			foreach ($this->scripts as $key => $line)
			{
				list($ua, $src) = explode(',', $line);
				settype($ua, 'int');

				if (0 == $ua) {
					continue;
				}
				else if ($ua == 4) {
					$ua = 'IE';
				}
				else if ($ua >= 6 && $ua <=9) {
					$ua = 'IE '.$ua;
				}
				else {
					$ua = '';
				}

				$helper->addScript($this->url .'/'. $src, $ua);
			}
		}
	}
}
