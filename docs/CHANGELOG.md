
1.10.0-beta
=============
**Parameter changes: you MUST REVIEW the template style parameters!**

* `customStyleSheet`:  *theme* -- Theme CSS file
* `enableSwitcher`:  *styleswitch* -- Enables the Style Switcher
* `showDiagnostics`:  this is part of the core 'styleswitch' widget
* `showDateContainer`:  *widgetDate* -- Display "date container"
* `dateFormat`:  *widgetDateFormat* -- Date format for "date container"
* `loadGcf`:  *cfinstall* -- bump oldIE users to install Google Chrome Frame

**CSS "alias" classes: you SHOULD REVIEW your context selectors!**
	**BC NOTICE:** CSS selectors may need to be adjusted.

Revised usage of $parent parameter in getCssAlias() to reduce the amount of class names.

* $item->type 'component' no longer appears,
* `$option` and `$view`  taken from menu items changed from 'com-content category' to
	'content-category'


**HTML markup changes: you SHOULD REVIEW your context selectors!**
layouts/mod_column_group_beta.php

* outermost container element changed from <div> to <aside> as  page level "secondary" content.
	**BC NOTICE:** CSS selectors may need to be adjusted.

layouts/mod_xxx_yyy.php (all)

* Moved meta class names `count-N` and `colcount-N` to `data-modules="N"` attribute.
	Use the attribute selector instead, i.e. `.above[data-modules=N]` where N is the specific number to tackle.

mod_breadcrumb:

* items were wrongly nested and some parameters omitted or incorrectly applied
	**BC NOTICE:** CSS selectors may need to be adjusted.

