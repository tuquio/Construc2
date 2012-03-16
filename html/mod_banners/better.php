<?php defined('_JEXEC') or die;
/**
 * A better mod_banners override
 *
 * @package     Template
 * @subpackage  HTML
 * @copyright   (C)2011-2012 WebMechanic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';
$baseurl = JURI::base();

?><div class="bannergroup">
<?php
if ($headerText) { echo $headerText; }

foreach ($list as $item)
{ ?><div class="banneritem item-<?php echo $item->id ?>"><?php

	$link = JRoute::_('index.php?option=com_banners&task=click&id='. $item->id);

	if ($item->type == 1) {
		// Text based banners
		echo str_replace(array('{CLICKURL}', '{NAME}'), array($link, $item->name), $item->custombannercode);
	}
	else
	{
		$imageurl = $item->params->get('imageurl');
		$width    = $item->params->get('width');
		$height   = $item->params->get('height');

		if (!empty($width))  $width  = ' width="'. $width.'"';
		if (!empty($height)) $height = ' height="'. $height.'"';

		// Image based banner
		if (BannerHelper::isImage($imageurl))
		{
			$alt = $item->params->get('alt');
			if ($alt) {
				$alt = ' alt="'. $alt .'"';
			}
			else if ($item->name) {
				$alt = ' alt="'. $item->name .'"';
			}
			else {
				$alt = ' alt="'. JText::_('MOD_BANNERS_BANNER') .'"';
			}

			// Wrap the banner in a link
			if ($item->clickurl)
			{
				$target = $params->get('target', 1);

				// Open in a new window
				if ($target == 1)
				{
?><a href="<?php echo $link ?>"
	target="_blank" title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') ?>">
	<img src="<?php echo $baseurl, $imageurl ?>"<?php echo $alt, $width, $height ?> /></a><?php
				}
				// open in a popup window
				else if ($target == 2)
				{
?><a href="<?php echo $link ?>" class="modal" title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') ?>">
	<img src="<?php echo $baseurl, $imageurl ?>"<?php echo $alt, $width, $height ?> /></a><?php
				}
				// open in parent window
				else
				{
?><a href="<?php echo $link ?>" title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') ?>">
	<img src="<?php echo $baseurl, $imageurl ?>"<?php echo $alt, $width, $height ?> /></a><?php
				}
			// Just display the image if no link specified?>
			}
			else
			{
?><img src="<?php echo $baseurl, $imageurl ?>"<?php echo $alt, $width, $height ?> /><?php
			}
		}
		// Flash based banner
		else if (BannerHelper::isFlash($imageurl))
		{
?><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
	codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0"
	<?php echo $width, $height ?>>
	<param name="movie" value="<?php echo $imageurl ?>" />
	</object><?php
		}
	}
?>
	</div>
<?php
}

if ($footerText) { ?><div class="bannerfooter"><?php echo $footerText ?></div><?php }
?></div><?php

