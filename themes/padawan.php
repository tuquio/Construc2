<?php return; ?>
; -----
; Theme configuration
;
title		= "Padawan"
author		= "WebMechanic"
version		= "2.0.0"
copyright	= "(c)2012 WebMechanic.biz"
license		= "CC BY-NC-ND"
url			= "http://webmechanic.biz"

[cdn]
@default	= "ajax.googleapis.com"
ajax.aspnetcdn.com			= 0,jquery
cdn1.depository.de			= 1,mootols
cdn1.kyrhia-schindler.de	= 1,jquery,mootols

; -----
; All-time scripts if SSI powered .scripts don't work for you.
; Filenames are relative to the ./themes folder
; keyname=N,filename.js
;   N	meaning
;   0	disabled
;   1	all browsers
;  2-5	reserved
;	4	all IE
;   6	IE 6 only
;   7	IE 7 only
;   8	IE 8 only
;   9	IE 9 only

[scripts]
modernizr=4,modernizr.95863.js

; -----
; Page level layouts:
; unique_name=category|article|blog
; 	~ layouts/category.php		used for all category views
; 	~ layouts/article.php		used for all article views
; 	~ layouts/blog.php			used for all blog views
;
; Item level layouts:
; hitchhiker=42,article			used for article with ID 42
; legacy=62,category			used for category with ID 62

[layouts]
featured	= featured
category	= category
faq			= 62,article

; -----
; Replacing default switches
; Values are list labels found in 'xx-XX.override.ini'
[styleswitcher]
diagnostic=TPL_CONSTRUC2_STYLE_DIAGNOSTIC_MODE

; Color schemes
@default	= "Padawan Light"
contrast	= "High Contrast"
darker		= "Padawan Dark"

; -----
; Font scaling
[fontscaler]
larger		= "Huge Font"
large		= "Large Font"
@default	= "Default Font"
small		= "Small Font"
smaller		= "Tiny Font"
