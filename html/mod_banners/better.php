<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

// apparently this happens...
if (count($list) == 0) {
	return '';
}

require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';
$baseurl = JURI::base(true);

?><div class="bannergroup"><?php
if ($headerText) { echo $headerText; }

foreach ($list as $item)
{
	$item->link = JRoute::_('index.php?option=com_banners&task=click&id='. $item->id);

?><div class="banneritem item-<?php echo $item->id, ($item->type == 1) ? ' textual' : ' visual' ?>"><?php


	if ($item->type == 1) {
		// Text based banners
		echo str_replace(array(
				'<span class="ext">{CLICKURL}</span>', '<span class="name">{NAME}</span>'),
				array($item->link, $item->name),
				$item->custombannercode
			);
	}
	else
	{
		$imageurl = $item->params->get('imageurl');
		$url      = $baseurl . $imageurl;
		$width    = $item->params->get('width');
		$height   = $item->params->get('height');

		if (!empty($width))  $width  = ' width="'. $width.'"';
		if (!empty($height)) $height = ' height="'. $height.'"';

		// Image based banner
		if (BannerHelper::isImage($imageurl))
		{
			$alt   = $item->params->get('alt');
			// this parameter is only availabe for ElementWidgets
			$title = $item->params->get('title', '');
			$css   = '';

			if ($alt) {
				$alt = ' alt="'.  htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') .'"';
			}
			elseif ($item->name) {
				$alt = ' alt="'.  htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') .'"';
			}
			else {
				$alt = ' alt="'. JText::_('MOD_BANNERS_BANNER') .'"';
			}

			// Wrap the banner in a link
			if ($item->clickurl)
			{
				switch ((int)$params->get('target', 1))
				{
					case 1;	// open in a new window
						$target = ' target="_blank"';
						if (empty($title)) $title = JText::_('JBROWSERTARGET_NEW');
						break;

					case 2;	// open in a modal window
						$target = '';
						if (empty($title)) $title = JText::_('JBROWSERTARGET_POPUP');
						$css    = ' class="modal"';
						break;

					default: // open in parent window
						$target = '';
						break;
				}

			?><a href="<?php echo $item->link ?>"<?php echo $target ?>><img src="<?php echo $url ?>"<?php echo $alt, $width, $height ?>></a><?php

			}
			else {
			// Just display the image if no link specified

			?><img src="<?php echo $url ?>"<?php echo $alt, $width, $height ?>><?php

			}
		}
		// Flash based banner
		elseif (BannerHelper::isFlash($imageurl)) {

	// @see http://kb2.adobe.com/cps/415/tn_4150.html http://kb2.adobe.com/cps/127/tn_12701.html
?>
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" <?php echo $width, $height ?> id="flmov_<?php echo $item->id ?>" align="middle">
	<param name="movie" value="<?php echo $url ?>">
	<param name="wmode" value="direct">
<!--[if !IE]>-->
	<object type="application/x-shockwave-flash" data="<?php echo $url ?>" <?php echo $width, $height ?> id="flmov_<?php echo $item->id ?>">
		<param name="movie" value="<?php echo $url ?>">
		<param name="wmode" value="direct">
<!--<![endif]-->
		<a href="http://www.adobe.com/go/getflash"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="<?php echo JText::_('Get Adobe Flash player') ?>"></a>
<!--[if !IE]>-->
	</object>
<!--<![endif]-->
</object>
<?php

		}
	}

?></div><?php

}

if ($footerText) { ?><div class="bannerfooter"><?php echo $footerText ?></div><?php }
?></div><?php
