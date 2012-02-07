<?php
/**
 * Custom layout for mod_login rendering support links only.
 * Links for "Login", "Registration", and "Password reset".
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

$cuparams = JComponentHelper::getParams('com_users');
?>
<menu class="menu loginmenu">
<li class="mi login"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=login'); ?>"><span class="mi"><?php echo JText::_('Login'); ?></span></a></li>
<?php if ($cuparams->get('allowUserRegistration')) : ?>
<li class="mi register"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_REGISTER'); ?></span></a></li>
<?php endif; ?>
<li class="mi reset"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></span></a></li>
</menu>
