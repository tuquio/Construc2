<?php
/**
 * stub layout to previz all module positions in 'index.php'
 * @package     Templates
 * @subpackage  Layouts
 */
defined('_JEXEC') or die;
FB::log( __FILE__ ); #HACK FB log filename

$this->setTitle('Dummy Positions');

?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
<style type="text/css">
body.debug .names {display:block;float:none;clear:both;position:relative}
body.debug .names:after,
body.debug .names:before {
	font:normal 10px/1em monospace;
	padding:2px;
	display:block;
	color:gray;
	background:floralwhite;
	position:relative;
	widh:25%;
}
body.debug .names:before {text-align:left;  content:'Id: 'attr(id);}
body.debug .names:after  {text-align:right; content:'Classes: ' attr(class);}

</style>
</head>

<body class="debug <?php echo $columnLayout; if ($useStickyFooter) echo ' sticky-footer'; ?>">
<!--[if IE 6]><div class="ie6 ltie7 ltie8 ltie9 ltie10"><![endif]-->
<!--[if IE 7]><div class="ie7 ltie8 ltie9 ltie10"><![endif]-->
<!--[if IE 8]><div class="ie8 ltie9 ltie10"><![endif]-->
<!--[if IE 9]><div class="ie9 ltie10"><![endif]-->
<header class="line above">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_header_above.php' ?>
</header>

<div id="body-container">
<h1>Position Renderer (WIP)</h1>
<p>Use this layout to test style theming and chrome applied to the content located
within the scope of template positions as well as all modules assigned to the various
position groups. The structure only features the immediate container element surrounding
a position group. It is intentionally kept much simpler than the standard layout and
also excludes component rendering or menus. If your styles properly catch you did an
excellent job on <b>not</b> using overspecified location-dependant selectors and also
did great on leveraging the cascade! Your mmodules are then ready to be placed on any
available template position, which is the whole idea of using them in the first place.</p>

<header class="names line below">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_header_below.php' ?>
</header>

<div id="nav-below" class="names nav-below">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_nav_below.php' ?>
</div>

<div id="content" class="names load-first">
	<div class="names content-main">
		<div class="names line content-above">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_content_above.php' ?>
		</div>
		<div class="names line content-below">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_content_below.php' ?>
		</div>
	</div>

	<div class="names column-group group-alpha">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_column_group_alpha.php' ?>
	</div>
</div>

<div class="names column-group group-beta">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_column_group_beta.php' ?>
</div>

<div class="names line footer-above">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_footer_above.php' ?>
</div>

</div><!-- end body-container -->

<!--[if IE]></div><![endif]-->
</body>
</html>