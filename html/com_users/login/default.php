<?php defined('_JEXEC') or die;
/**
 * @package     Template
 * @subpackage  Overrides
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011-2012 WebMechanic. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

$app = JFactory::getApplication();

if ($this->user->get('guest')):
	// The user is not logged in
	if ($app->input->get('tmpl') == 'modal') {
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
