<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

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
?>
<div <?php echo implode(' ', $attr) ?>><?php echo $module->content ?></div>