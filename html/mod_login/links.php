<?php
/**
 * Custom layout for mod_login rendering support links only.
 * Links for "Password reset", "Password Reminder", and "Registration".
 *
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

$cuparams = JComponentHelper::getParams('com_users');
?>
<menu class="menu loginmenu">
<li class="mi reset"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=reset') ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD') ?></span></a></li>
<li class="mi remind"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=remind') ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME') ?></span></a></li>
<?php if ($cuparams->get('allowUserRegistration')) {
?><li class="mi register"><a class="mi" href="<?php echo JRoute::_('index.php?option=com_users&view=registration') ?>"><span class="mi"><?php echo JText::_('MOD_LOGIN_REGISTER') ?></span></a></li>
<?php } ?>
</menu>
