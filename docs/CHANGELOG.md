
1.10.0-beta
=============
**API changes**

* ConstructTemplateHelper constants `MAX_COLUMS` and `MAX_MODULES` became static properties and
    are now configurable via settings.php
* `ConstructTemplateHelper::$positions` refactored from array to more informative `PositionGroup` object.
    The total number of modules formerly available as key '0' is now available as 'total'.
    <br>**BC** Numeric "keys" are still supported by casting the result of `getModulesCount()` to an array (see logic.php)
    <br>**Check your usage** of (older) global arrays like `$headerAboveCount`, `$headerBelowCount` or `$columnGroupXxxCount`
* `ConstructTemplateHelper::getModulesCount`: the unused (int) $max argument was replaced
    with (bool) $positions to return the group object in $positions

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


**HTML markup changes: you SHOULD REVIEW your CSS context selectors!**
layouts/mod_column_group_beta.php

* outermost container element changed from <div> to <aside> as page level "secondary" content.
	**BC NOTICE:** CSS selectors may need to be adjusted.

layouts/mod_xxx_yyy.php (all)

* Moved meta class names `count-N` and `colcount-N` to `data-modules="N"` attribute.
	Use the attribute selector instead, i.e. `.above[data-modules=N]` where N is the specific number to tackle.

mod_breadcrumb:

* items were wrongly nested and some parameters omitted or incorrectly applied
	**BC NOTICE:** CSS selectors may need to be adjusted.

