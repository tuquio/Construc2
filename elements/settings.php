;<?php return; ?>
	;------------------------------------------------
	;
	; Construc2 Main Configuration.
	;
	;------------------------------------------------
	;  DO NOT EDIT THIS FILE !!
	;
	;  To override or add entries use a
	;  custom Theme configuration file.
	;------------------------------------------------

[cdn]
@default	= "ajax.googleapis.com"
	; -----
	; "Content Delivery Networks" for script libraries
	; also used for parameter field ./elements/cdnlist.php
	; You can add/merge/replace entries with a theme config
ajax.googleapis.com			= 1,jquery,mootools
ajax.aspnetcdn.com			= 0,jquery
code.jquery.com				= 1,jquery
cdnjs.cloudflare.com		= 1,jquery,mootools

[subst]
	; -----
	; LINK url placeholders
	; DO NOT REMOVE OR CHANGE ORDERING ANY THESE ENTRIES!
	;
media		= "{root}/media/system"
images		= "{root}/images"
template	= "{themes}/construc2"
theme		= "{template}/themes/{name}"
tmpl.css	= "{template}/css"
tmpl.js		= "{template}/js"
system.css	= "{media}/css"
system.js	= "{media}/js"

[autocols]
	; Positions to receive the "autocols" attribute
	; causing modules to be evenly distributed
column-1 		= 0
column-2 		= 0
column-3 		= 0
column-4 		= 0
header-above 	= 1
header-below 	= 1
content-above 	= 1
content-below 	= 1
footer-above 	= 0
nav-below 		= 0

[allow_empty]
	; -----
	; module types
custom		= 1
banner		= 0
