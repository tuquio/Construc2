<?php
/**
 * Custom Override for com_users login and logout.
 * Featuring ILLL™ "Instant Logout Login Layout" - by courtesy of Hannes Papenberg.
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

$app = JFactory::getApplication();

if ($this->user->get('guest')):
	// The user is not logged in
	if ($app->get('input')->get('tmpl') == 'modal') {
		// login as a modal box
		echo $this->loadTemplate('loginmodal');
	} else {
		// standard login layout
		echo $this->loadTemplate('login');
	}
else:
	// The user is already logged in: log her out
	$app->logout();
	$app->redirect('/');
endif;
