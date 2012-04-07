<?php defined('_JEXEC') or die;
/**
 * Content Layout Helper used in blog and list views.
 *
 * @package     Template
 * @subpackage  Overrides
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2012 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/** Register the ConstructTemplateHelper Class */
JLoader::register('ConstructTemplateHelper', JPATH_THEMES . '/construc2/elements/helper.php');

class ContentLayoutHelper
{
	/**
	 * Renders the "Read More" link and must be wrapped in a call such as
	 * <code>
	 * if ($params->get('show_readmore') && $this->item->readmore) {
	 *     echo ContentLayoutHelper::showReadMore($this->item, $params);
	 * }
	 * </code>
	 *
	 * @param  object    $item   Article item
	 * @param  JRegistry $params Layout parameters
	 * @return string
	 */
	static public function showReadMore($item, $params, $class='line readmore', $target='#content')
	{
		$access = (bool) $params->get('access-view');

		$html = '<p class="'. $class . ($access ? '' :  ' access-view').'">';
		$link  = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid));

		if ($access) {
			$html .= '<a href="'. $link . $target .'">';

			if ($params->get('show_readmore_title') && $item->alternative_readmore) {
				$html .= JHtml::_('string.truncate', $item->alternative_readmore, $params->get('readmore_limit', 50));
			}
			else {
				$html .= JText::sprintf('COM_CONTENT_READ_MORE_TITLE', $item->title);
			}
			$html .= '</a>';
		}
		else {
			$itemId  = JSite::getMenu()->getActive()->id;
			$uri  = new JURI(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId));
			$uri->setVar('return', base64_encode($link));

			$html .= '<a href="'. $link .'#content">' . JText::_('COM_CONTENT_REGISTER_TO_READ_MORE') . '</a>';
		}

		$html .= '</p>';

		return PHP_EOL."\t".$html;
	}

	/**
	 * Attempts to create nice list of class names from the $item data.
	 *
	 * State and meta classes:
	 * - images: article has images set in the parameter
	 * - system-unpublished: article is unpublished
	 * - no-access: login required to "read more"
	 *
	 * @param  object $item   an article object or sth. close
	 * @param  bool   $parent travel up the tree to fetch parent item aliases
	 *
	 * @return string some CSS classes
	 */
	static public function getCssAlias($item, $parent = true)
	{
		static $props = array('images','parent_alias','category_alias','alias','state','id','catid','params');

		$C = array('');
		$candidates = array_intersect( array_keys(get_object_vars($item)), $props );
		while ($str = array_shift($candidates))
		{
			switch ($str) {
				case 'images':
					if (preg_match('#\.(jpe?g|png|gif)#i',$item->images)) {
						$C[] = $str;
					}
					break;
				case 'state':
					if ($item->state == 0) {
						$C[] = 'system-unpublished';
					}
					break;
				case 'parent_alias':
					if ($parent && 'root' != $item->parent_alias) {
						$C[] = $item->parent_alias;
					}
					break;
				case 'id':
					$C[] = 'item-'. $item->id;
					break;
				case 'catid':
					$C[] = 'cat-'. $item->catid;
					break;
				case 'params':
					if ($item->params instanceof JRegistry) {
						if (0 == (int)$item->params->get('access-view')) {
							$C[] = 'no-access';
						}
					}
					break;
				default:
					$C[] = $item->{$str};
			}
		}
		$words = array_unique($C);
		$alias = implode(' ', $words);

		return trim($alias);
	}

	static public function isEmpty(&$content)
	{
		static $keepers = '<audio><canvas><embed><hr><iframe><img><math><noscript><object><param><svg><video><command><script><style>';
		// decode entities, keep meta + embeds, then remove "white-space"
		$blank = preg_replace('#[\r\n\s\t\h\v\f]+#', '', strip_tags(html_entity_decode($content), $keepers));
		return empty($blank);
	}

	/*@todo delegate to Widget Class */
	static public function widget($key, &$item, &$params, $attribs=array())
	{
		// list($class, $method) = explode('.', $key);
		return str_replace(
					array('&#160;','&nbsp;',JText::_('JGLOBAL_ICON_SEP')), '',
					JHtml::_($key, $item, $params, $attribs)
				);
	}

	/**
	 * Pimp both Article's Table of Contents and Page Navigation in place to make
	 * them better. Will replace the content of $item->toc and $item->pagination.
	 *
	 * @param  object  $item The article item object
	 *
	 * @return void (sets the toc property)
	 */
	static public function betterToc(&$item)
	{
		$item->pagenav = null;

		if (!isset($item->toc)) {
			return;
		}

		// no toc for "all pages" view
		if ((bool) JFactory::getApplication()->input->get('showall'))
		{
			$item->toc = null;
		}

		try {
			// let's be tough...
			$nav = self::_makePageToc($item);

			if ($nav) {
				// pick stored "HTML5" data attributes
				$pagenav = new JPagination((int)$nav['data-pages'], (int)$nav['data-page'], 1);
				$item->pagenav = $pagenav->getPagesLinks();
			}

			return;
		}
		catch (Exception $e) {
			// ok, 'twas worth trying
		}

		// replace tags and add better classes the old fashion way
		$toc = $item->toc;
		$toc = str_replace(
				array('<div', '/div>', '<h3>', '<ul>', '<ol>', '<li>'),
				array('<nav class="unit size2of5 page-toc rgt"', '/nav>', '<h3 class="H4 toc-title">', '<ol class="toc-items">', '</ol>', '<li class="mi">'),
				$toc);

		$item->toc = $toc;

	}

	/**
	 * Renders the page table of contents and creates a new pagination.
	 *
	 * @param  object  $item  article item
	 * @param  string  $class class attribute
	 *
	 * @return SimpleXMLElement
	 *
	 * @todo insert skiplink href="#fulltext"
	 */
	static protected function _makePageToc(&$item, $class='unit size1of3 rgt')
	{
		$shutup = libxml_use_internal_errors(false);

		$flags = LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG | LIBXML_NOCDATA;

		$div = new SimpleXMLElement($item->toc, $flags);

		// pagination list using pagination.php
		$i = 0;
		$p = (int)JFactory::getApplication()->input->get('start', 0);

		// rebuild as <nav>
		$nav = new SimpleXMLElement('<nav id="article-index" class="page-toc '. $class .'"/>', $flags);

		// save numbers as "HTML5" data attribute
		$nav['data-pages'] = count($div->ul->children()) - 1;
		$nav['data-page']  = $p;

		$nav->addChild('h3', JText::_('PLG_CONTENT_PAGEBREAK_ARTICLE_INDEX'));
		$nav->h3['class'] = 'H4 toc-title';

		// skiplinks here, please

		$nav->addChild('ol');
		$nav->ol['class'] = 'toc-items';

		// .. and use an awkward API
		foreach ($div->ul->children() as $elt)
		{
			$li = $nav->ol->addChild('li');

			if ($i == $p) {
				$li['class'] = 'mi current';

				$li->span = "$elt->a";
				$li->span['class'] = 'mi';
			} else {
				$li['class'] = 'mi';

				$li->a['href'] = $elt->a['href'];
				$li->a['class'] = 'mi toclink';
				$li->a->span = "$elt->a";
				$li->a->span['class'] = 'mi';
			}
			$i += 1;
		}

		$item->toc = str_replace('<?xml version="1.0"?>', '', $nav->asXml());

		libxml_use_internal_errors($shutup);

		return $nav;
	}

}
