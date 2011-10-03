<?php
defined('_JEXEC') or die;

$raw = isset($attribs['style']) && (!$attribs['style'] || $attribs['style'] == 'raw');
?>

<?php if ($raw) : ?>
<div class="custom" <?php if ($params->get('backgroundimage')): ?> style="background-image:url(<?php echo $params->get('backgroundimage');?>)"<?php endif;?>>
<?php endif; ?>
<?php echo $module->content;?>
<?php if ($raw) : ?>
</div>
<?php endif; ?>
