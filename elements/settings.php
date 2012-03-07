<?php return; ?>
; some "static" data to customize

; Content Delivery Networks for script libraries
[cdn]
ajax.googleapis.com=1
ajax.aspnetcdn.com=1
code.jquery.com=1
cdnjs.cloudflare.com=1

; LINK url placeholders
; addLink('{core}/print.css')
[link_subst]
core="templates/construc2/css/core"
template="templates/construc2/css"
theme="templates/construc2"
system="media/system/css"

;
; Defaults than can be REPLACED using a "theme.ini"
; Values are list labels.
; Add custom to xx-XX.override.ini for translation
[styleswitcher]
wireframe=TPL_CONSTRUC2_STYLE_WIREFRAME
diagnostic=TPL_CONSTRUC2_STYLE_DIAGNOSTIC_MODE
normal=TPL_CONSTRUC2_STYLE_NORMAL_MODE
