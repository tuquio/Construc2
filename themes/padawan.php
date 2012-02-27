<?php return; ?>
; -----
; Theme configuration
;
name="Padawan"
author="WebMechanic.biz"
version="1.0.0"
copyright="(c)2011 WebMechanic.biz"

; -----
; Page level layouts:
; unique_name=category|article|blog
; 	~ layouts/category.php		used for all category views
; 	~ layouts/article.php		used for all article views
; 	~ layouts/blog.php			used for all blog views
;
; Item level layouts
; hitchhiker=42,article			used for article with ID 42
; legacy=62,category			used for category with ID 62

[layouts]
featured=featured
category=category
faq=62,article

; -----
; Replacing default switches
; Values are list labels found in 'xx-XX.override.ini'
[styleswitcher]
diagnostic=TPL_CONSTRUC2_STYLE_DIAGNOSTIC_MODE

; Color schemes
.default="Padawan Light"
contrast="High Contrast"
darker="Padawan Dark"

; -----
; Font scaling
[fontscaler]
larger="Huge Font"
large="Larger Font"
.default="Default Font"
small="Small Font"
smaller="Tiny Font"
