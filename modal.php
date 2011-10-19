<!DOCTYPE html>
<?php defined('_JEXEC') or die;
/**
 * @package     Templates
 * @subpackage  Construc2
 * @author      WebMechanic http://webmechanic.biz
 * @copyright   (C) 2011 WebMechanic
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
// Load template logic
require JPATH_THEMES.'/'.$this->template.'/elements/logic.php';
$this->addStyleSheet($tmpl_url.'/css/core/modal.css');

?><html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
</head>
<body>
<?php if ($this->getBuffer('message')) : ?>
<div class="line"><jdoc:include type="message" /></div>
<?php endif; ?>
<div class="line"><jdoc:include type="component" /></div>
</body>
</html>