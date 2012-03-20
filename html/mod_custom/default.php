<?php defined('_JEXEC') or die;

$attr = 'id="custom-'. $module->id .'"';
if ($params->get('backgroundimage')) {
	$attr .= ' style="display:inline-block;background-image:url('. $params->get('backgroundimage') .')"';
}
if ($moduleclass_sfx) {
	$attr .= ' class="custom '. $moduleclass_sfx .'"';
}

?>
<div <?php echo $attr ?>><?php echo $module->content ?></div>