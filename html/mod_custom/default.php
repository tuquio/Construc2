<?php defined('_JEXEC') or die;

$attr = array('id="custom-'. $module->id .'"');
$cls  = array('custom');
if (!empty($moduleclass_sfx)) $css[] = $moduleclass_sfx;

if ($params->get('backgroundimage')) {
	$cls[]  = 'has-image';
	$attr[] = 'style="background-image:url('. $params->get('backgroundimage') .')"';
}
$attr[] = implode(' ', $cls);

if (empty($module->content)) {
	$module->content = '&nbsp;';
}
?>
<div <?php echo implode(' ', $attr) ?>><?php echo $module->content ?></div>