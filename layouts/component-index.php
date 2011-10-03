<?php defined('_JEXEC') or die;
/**
 * @package		Templates
 * @subpackage  Construc2
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	(C) 2011 WebMechanic
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
</head>

<body class="<?php echo $columnLayout ?>">
<!--[if IE 6]><div class="ie6 ltie7 ltie8 ltie9 ltie10"><![endif]-->
<!--[if IE 7]><div class="ie7 ltie8 ltie9 ltie10"><![endif]-->
<!--[if IE 8]><div class="ie8 ltie9 ltie10"><![endif]-->
<!--[if IE 9]><div class="ie9 ltie10"><![endif]-->
	<jdoc:include type="component" />
<!--[if IE]></div><![endif]-->
	</body>
</html>