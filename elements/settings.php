<?php return; ?>
	;------------------------------------------------
	;
	; Some "static" data to extend in theme config.
	;
	;------------------------------------------------
	;  DO NOT EDIT THIS FILE !!
	;------------------------------------------------

[cdn]
	; -----
	; "Content Delivery Networks" for script libraries
	; also used for parameter field ./elements/cdnlist.php
	; You can add/merge/replace entries with a theme config
@default	= "ajax.googleapis.com"
@media		= "/media/system"
ajax.googleapis.com			= 1,jquery,mootols
ajax.aspnetcdn.com			= 0,jquery
code.jquery.com				= 1,jquery
cdnjs.cloudflare.com		= 1,jquery,mootols

[link_subst]
	; -----
	; LINK url placeholders
	; addLink('{core}/l10n.css')
theme		= "{root}/templates/construc2/themes/{name}"
template	= "{root}/templates/construc2/css"
core		= "{root}/templates/construc2/css/core"
system		= "{root}/media/system/css"
module		= "{root}/modules/{name}/css"
plugin		= "{root}/plugins/{type}/{name}/css"

[styleswitcher]
	; -----
	; Defaults than can be REPLACED with theme config files
	; Values are list labels runthru JText for translation
	; and best added to "xx-XX.override.ini"
wireframe	= TPL_CONSTRUC2_STYLE_WIREFRAME
diagnostic	= TPL_CONSTRUC2_STYLE_DIAGNOSTIC_MODE
normal		= TPL_CONSTRUC2_STYLE_NORMAL_MODE

[widgets]
	; -----
	; HTML Widgets supplied/activated by theme
date	= 1

[allow_empty]
	; -----
	; module types
custom	= 1
