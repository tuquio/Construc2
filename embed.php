<?php defined('_JEXEC') or die;
/**
 * Ajax/Embed layout used with &layout=<layout>&type=embed
 *
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

if ($this->getBuffer('message')) : ?><jdoc:include type="message" /><?php endif; ?>
<jdoc:include type="component" />
