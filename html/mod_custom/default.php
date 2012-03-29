<?php defined('_JEXEC') or die;

$attr = array('id="custom-'. $module->id .'"');
$cls  = array('custom');
if (!empty($moduleclass_sfx)) $cls[] = $moduleclass_sfx;

if ($params->get('backgroundimage')) {
	$cls[]  = 'has-image';
	$attr[] = 'style="background-image:url('. $params->get('backgroundimage') .')"';
}
$attr[] = ' class="'.implode(' ', $cls) .'"';

if (empty($module->content)) {
	$module->content = '&nbsp;';
}
FB::info($module, $moduleclass_sfx);
?>
<div <?php echo implode(' ', $attr) ?>><?php echo $module->content ?></div>