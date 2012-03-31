
1.10.0-beta
===========
** Parameter changes: YOU MUST REVIEW the template style parameters!**

* customStyleSheet:  theme -- Theme CSS file
* enableSwitcher:  styleswitch -- Enables the Style Switcher
* showDiagnostics:  this is part of the core 'styleswitch' widget
* showDateContainer:  widgetDate -- Display "date container"
* dateFormat:  widgetDateFormat -- Date format for "date container"
* loadGcf:  cfinstall -- bump oldIE users to install Google Chrome Frame

layouts/mod_xxx_yyy.php
	Moved meta class names `count-N` and `colcount-N` to `data-modules="N"` attribute.
	**BC NOTICE:** CSS selectors may need to be adjusted and use the attribute
	selector instead, i.e. `.above[data-modules=N]` where N is the  specific 
	number to tackle.

mod_breadcrumb:
	items were wrongly nested and some parameters omitted or incorrectly applied
	**BC NOTICE:** CSS selectors may need to be adjusted

