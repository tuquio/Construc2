<?php return; ?>
; some "static" data to customize

; "Content Delivery Networks" for script libraries
; also used for parameter field ./elements/cdnlist.php
; You can add/merge/replace entries with a theme config
[cdn]
@default	= "ajax.googleapis.com"
@media		= "/media/system"
@template	= "/templates/padawan/js"
ajax.googleapis.com			= 1,jquery,mootols
ajax.aspnetcdn.com			= 0,jquery
code.jquery.com				= 1,jquery
cdnjs.cloudflare.com		= 1,jquery,mootols

; LINK url placeholders
; addLink('{core}/print.css')
[link_subst]
theme		= "templates/construc2"
template	= "{theme}/css"
core		= "{theme}/css/core"
system		= "media/system/css"

; Defaults than can be REPLACED with theme config files
; Values are list labels runthru JText for translation
; and best added to "xx-XX.override.ini"
[styleswitcher]
wireframe	= TPL_CONSTRUC2_STYLE_WIREFRAME
diagnostic	= TPL_CONSTRUC2_STYLE_DIAGNOSTIC_MODE
normal		= TPL_CONSTRUC2_STYLE_NORMAL_MODE