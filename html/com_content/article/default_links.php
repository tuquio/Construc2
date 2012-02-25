<?php defined('_JEXEC') or die;

// Create shortcut
$urls = json_decode($this->item->urls);

if ( empty($urls->urla) && empty($urls->urlb) && empty($urls->urlc)) {
	return;
}

// Create shortcuts to some parameters.
$params = $this->item->params;
?>
<div class="content-links">
	<ul class="menu"><?php

$urlarray = array(
	array($urls->urla, $urls->urlatext, $urls->targeta, 'a'),
	array($urls->urlb, $urls->urlbtext, $urls->targetb, 'b'),
	array($urls->urlc, $urls->urlctext, $urls->targetc, 'c')
);

foreach($urlarray as $url)
{
	// if there's no link
	if (!$url[0]) {
		continue;
	}

	// some shortcutz
	$link   = htmlspecialchars($url[0]);
	$label  = htmlspecialchars($url[1] ? $url[1] : $link);
	$target = $url[2];
	$id     = $url[3];

	// If no target is present, use the default
	$target = $target ? $target : $params->get('target'.$id);

	//mixed options for popup and/or modal, a JS lib can pick its poison
	$jsopt = json_encode(array(
				'handler'=> 'iframe',	// for MT/SqueezeBox compat
				'x'      => 600,		// measures in pixels
				'y'      => 600,
				'width'  => 600,
				'height' => 600,
				'w'      => '60%',		// measures in CSS units
				'h'      => '60%',
				'name'   => addslashes($target),
				'target' => addslashes($target)
				));

?>
	<li class="mi content-links-<?php echo $id; ?>"><?php

	// Compute the correct link
	switch ($target)
	{
		case 1:
			// open in a new window
			echo '<a href="', $link, '" rel="nofollow" target="_blank">', $label, '</a>';
			break;

		case 2:
			// open in a popup window - given some JS lib is loaded to deal with this
			echo '<a class="popup" href="', $link, '" rel="nofollow" data-popup="">', $label, '</a>';
			break;

		case 3:
			// open in a modal window - given some JS lib is loaded to deal with this
			// The modal behavior ist NOT LOADED on purpose to allow the template using what it thinks is better suited
			echo '<a class="modal" href="', $link, '" rel="nofollow" data-modal="">', $label, '</a>';
			break;

		default:
			// open in parent window
			echo '<a href="', $link, '" rel="nofollow">', $label, '</a>';
			break;
	}
?>
		</li>
<?php
}
?>
	</ul>
</div>
