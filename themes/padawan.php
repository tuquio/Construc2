<?php
/**
 * Padawan Theme.
 */
class PadawanTheme extends CustomTheme
{
}

return;
?>
; -----
; Custom Theme Configuration: Padawan
; -----
name		= "Padawan"
title		= "Padawan Theme"
description	= "Frontend Theme kyrhia.com"
homepage	= "http://webmechanic.biz"
license		= "CC BY-NC-ND"
authors		= "WebMechanic,ct@webmechanic.biz,http://joomla.webmechanic.biz"
version		= "2.0.0"
require		= ""

; -----
[layouts]
	; Page level template layouts:
	; unique_name = category|article|blog|featured
	;
	; 		~ layouts/category.php		used for all category views
	; 		~ layouts/article.php		used for all article views
	; 		~ layouts/blog.php			used for all blog views
	; 		~ layouts/featured.php		used for a view named 'featured'
	;
	; Item level template layouts:
	; hitchhiker= 42,article
	; legacy	= 62,category
	;
	; 		~ layouts/article-42.php	used for category with ID 62
	; 		~ layouts/category-62.php	used for article with ID 42

; -----
[cdn]
	; merge custom CDNs (req. Construc2 Backend Plugin!)
@default	= "ajax.googleapis.com"
ajax.aspnetcdn.com			= 0,jquery
cdn1.depository.de			= 1,mootols
cdn1.kyrhia-schindler.de	= 1,jquery,mootols

; -----
[scripts]
	; Scripts to be ADDED if SSI powered .scripts don't work
	; for your and für scripts targeting MSIE only.

	; File paths are relative to the ./themes folder!
	; keyname=N,filename.js
	;   N	meaning
	;   0	disabled
	;   1	all browsers (~ "enabled")
	;  2-5	reserved
	;	4	all IE
	;   6	IE 6 only
	;   7	IE 7 only
	;   8	IE 8 only
	;   9	IE 9 only

modernizr=4,modernizr.95863.js

; -----
[nuke_scripts]
	; Remove these scripts added by CMPT that you have
	; BETTER WORKING or updated or minified versions for
	; OR POSSIBLE drop-in replacements that suit your Theme
	;
	;	keyname = "/canonical/url/to/style.css"
	;
	; !! FOR MOOTOOLS AND JQUERY CONTROL USE THE TEMPLATE ADMIN !!
	;
caption.js = "/media/system/js/caption(.*).js"

; -----
[nuke_styles]
	; Remove these stylesheets added by CMPT that interfere with
	; your styles or that you have incorporated into your Theme
	;
	;	keyname = "/canonical/url/to/style.css"
	;
finder.css = "/media/com_finder/css/finder.css"

; -----
[styleswitch]
	; Replacing default stylesheet switches
	; Values are list labels found in 'xx-XX.override.ini'

diagnostic	= Widget_Styleswitch_Diagnostic
	; Color schemes
@default	= "Padawan Light"
contrast	= "High Contrast"
darker		= "Padawan Dark"

; -----
[fontscaler]
	; Font scaling
larger		= "Huge Font"
large		= "Large Font"
@default	= "Default Font"
small		= "Small Font"
smaller		= "Tiny Font"
