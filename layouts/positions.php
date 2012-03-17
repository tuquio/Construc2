<!DOCTYPE html><?php defined('_JEXEC') or die;
/**
 * EXPERIMENTAL! Stub page layout to previz module positions.
 *
 * @package     Templates
 * @subpackage  Layouts
 * @author		WebMechanic http://webmechanic.biz
 * @copyright	Copyright (C) 2011 WebMechanic http://webmechanic.biz. All rights reserved.
 * @license		GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

$this->setTitle('Dummy Positions');

?>
<html lang="<?php echo $this->language ?>" dir="<?php echo $this->direction ?>">
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
body.debug .names:before {text-align:left;  content:'Id: ' attr(id);}
body.debug .names:after  {text-align:right; content:'Classes: ' attr(class);}

</style>
</head>
<body class="<?php echo $columnLayout ?>">
<?php ConstructTemplateHelper::msieSwatter() ?>
<header class="line above">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_header_above.php' ?>
</header>

<div id="body-container" class="line">
<h1>Position Renderer (WIP)</h1>
<p>
Use this Layout to test theming and Module chrome applied to Modules when they're loaded in different template positions or position groups.
The structure only features the immediate container element surrounding a position group.
It is intentionally kept much simpler than a regular page layout and excludes component rendering.
If your styles are smart and Modules appear correct no matter what position they're assigned to
you did an excellent job on leveraging the cascade!
You can now safely plumb Modules into any template position, which is the whole idea of using them in the first place.
</p>

<header class="names line below">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_header_below.php' ?>
</header>

<div id="nav-below" class="names line nav-below">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_nav_below.php' ?>
</div>

<div id="content" class="names line content-main">
	<div class="names line content-above">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_content_above.php' ?>
	</div>

	<section class="line component">
		<article class="line item-page">
		<header class="article"><h2 class="H2 title">Liber Primus!</h2></header>
		<p>Certe, inquam, pertinax non ero tibique, si mihi probabis ea, quae dices, libenter assentiar. Probabo, inquit, modo ista sis aequitate, quam ostendis. sed uti oratione perpetua malo quam interrogare aut interrogari. Ut placet, inquam. Tum dicere exorsus est. Primum igitur, inquit, sic agam, ut ipsi auctori huius disciplinae placet: constituam, quid et quale sit id, de quo quaerimus, non quo ignorare vos arbitrer, sed ut ratione et via procedat oratio. quaerimus igitur, quid sit extremum et ultimum bonorum, quod omnium philosophorum sententia tale debet esse, ut ad id omnia referri oporteat, ipsum autem nusquam. hoc Epicurus in voluptate ponit, quod summum bonum esse vult, summumque malum dolorem, idque instituit docere sic:</p>
		<blockquote><p>Omne animal, simul atque natum sit, voluptatem appetere eaque gaudere ut summo bono, dolorem aspernari ut summum malum et, quantum possit, a se repellere, idque facere nondum depravatum ipsa natura incorrupte atque integre iudicante. </p></blockquote>
		<p>itaque negat opus esse ratione neque disputatione, quam ob rem voluptas expetenda, fugiendus dolor sit. sentiri haec putat, ut calere ignem, nivem esse albam, dulce mel. quorum nihil oportere exquisitis rationibus confirmare, tantum satis esse admonere. interesse enim inter argumentum conclusionemque rationis et inter mediocrem animadversionem atque admonitionem. altera occulta quaedam et quasi involuta aperiri, altera prompta et aperta iudicari. etenim quoniam detractis de homine sensibus reliqui nihil est, necesse est, quid aut ad naturam aut contra sit, a natura ipsa iudicari. ea quid percipit aut quid iudicat, quo aut petat aut fugiat aliquid, praeter voluptatem et dolorem?</p>
		</article>

	<div class="names line content-below">
<?php include JPATH_THEMES .'/'. $this->template . '/layouts/mod_content_below.php' ?>
	</div>
	</section>

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
<?php ConstructTemplateHelper::msieSwatter() ?>
</body>
</html>