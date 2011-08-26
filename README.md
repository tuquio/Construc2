= Construct 4 J!1.6
check [original repo](https://github.com/betweenbrain/Construct-Community-1.6)
to learn more about "Joomla Engineering Construct Template Framework for Joomla! 1.6"

**This** is a clone to fix and change things that don't work for me.

== Ideas / To-do
*	fix l10n issues (almost done)
*	make webfont list "live" (cacheable)
*	make jQuery version list "live" (cacheable)
*	add (a set of?) "previz" params to render dummy modules at various
	module positions during layout development

== Changes
Removed all `index.html`; have .htaccess for that.

Removed identical code from main `index.php` leaving `/layouts/index.php`
the default layout if none is provided or found to match the custom CSS
from template paramaters.

Both `logic.php` and `helper.php` are now loaded unconditionally.
There appears to be no reason to validate their filenames or check if they
exist, since none of the flexibility this template offers would work anyway
should these files be missing.

Added several l10n keys to replace the many hardcoded english strings in
`index.php`, `elements/*` and `templateDetails.xml` -- still WIP.

== Refactored jqueryversion.php
Updated to include jQuery 1.6.2.

The list of jQuery version is now maintaind in an array, and looped over
to add them in bulk to the select list, rather than adding each single
release "manually" as a single option.

Google CDN URLs now use a relative protocol URL to work with both http
and https.

== Cleanup modChrome / modules.php
Removed styles marked as deprecated, 'cos they're deprecated.

Added "OOCSS compliant" styles: mod, complex, pop, bubble.

Changed test for module content to the top using
``` php
	if (empty($module->content)) return;
```
If there is no content, there's no need to waste time and memory testing the
parameter and assigning them to other variables to eventually wrap the whole
function body in this if-statement.
``` php
	if ( !empty($module->content) ) {
		// lots of stuff here
	}
```
The code is now only executed if content actually exists.

== Refactored logic.php
Some param values are now forced to a proper type, e.g. boolean or number.

Setting `$loadModal` now forces `$loadMoo` to be true, as the former depends
on it. Updated calls of deprecated `JHtml::_('behavior.foo')` to
`JHtmlBehavior::foo()` were appropriate.
However if `$loadModal` becomes false, all scripts from `/media/system/js`
execpt core.js are removed, because the all depend on Mootools.

Added condition for the call of SmoothScroller() that woudn't work if
Mootools were disabled.

Put some of its "logic" on a diet and killed redundancy = ~30% less code.

Fixed some calls for adding extra (MSIE) styles and links happened unconditionally
causing issues on the client side.

Replaced all `$doc` with `$this` which in the given context is exactly the
same as `JFactory::getDocument()`.

== Refactored helper.php
Implemented some logic into ConstructTemplateHelper which (as a helper) ought
to be a static class but is not. Won't change this (for now), thus:
-	added constructor that requires the template object
-	renamed `$includeFiles` to `$layouts`
-	renamed `getIncludeFile()` to `getLayout()`
-	added `addLayout($basename, $scope=null)` where `$basename` is the layout
	file w/o suffix and `$scope` one of 'index', 'component', 'section'.

The $basename is adjusted accordingly:
-	index: to work with the custom CSS from params = $basename-index.php
-	component: given the component name ($option) = /component/$basename.php
-	section: given a section id (numeric) = /section/section-$basename.php

`addLayout()` supports fluid interface for bulk layout addition.

Added `dateContainer()` to support the proper language setting for the current
date. Accepts one of Joomlas date language keys (`DATE_FORMAT_LCx`) for proper
formatting, an optional "timestamp" (defaults to 'now'), and an element name as
the wrapper element for the date parts (default = 'span').
This method now should allow to format any (?) date value using these individual
(span) elements for each date part.

Implemented an option for `getLayout()` to test for the
**active Menu Item** in order to auto-locate a corresponding layout file.
This works for component overrides and section-ids.

=== Google Web Font
Changed wording of "html elements, CSS IDs, and classes to apply" to just
"CSS selectors", which is what they essentially are and what's required.

Google CDN URLs now use a relative protocol URL to work with both http
and https.

The `$googleWebFontSize` input fields caused a typical selector list such as
h1,h2,h3,h4,h5,h6 to render all headings in the same font-size.
Because these style rules apper within the document head they have a higher
"cascade" than external styles which makes them harder to override and a
need to enter into "specificity war".

The default values in XML now allow empty values.
If a value is empty the CSS property font-size: is ommited.

All font CSS URLs now use a relative protocol to work with both http and https.
All three font settings are now evaluated in a simple loop.
> 	**BC break**:
>	This required to rename the 1. param set from the un-numbered
> 	`$googleWebFont` to `$googleWebFont1` etc. thus: if the template
>	params are revisited in the backend, the first font will be "empty"
>	and deselected and needs to be reapplied.

=== Module Positions
Like the first `$googleWebFont` parameter, loads of module positions helper
variables also used an un-numbered first `$fooBar` and continue counting from
`$fooBar2` to `$fooBar6`.

This inefficiency has been fixed using arrays to quickly loop through all
variables and results in much less repetitive code.

For example `$headerAboveCount[0]` holds to total amount of a modules found
in "header-above-1" to "header-above-6", wheras `$headerAboveCount[1]` to
`$headerAboveCount[6]` contain the inividual amounts.
If no modules are found `$headerAboveCount` becomes `NULL`. This only affects
how individualpositions need to be tested in the template code:

Instead of:
  `if ($headerAboveCount1) :`
  `if ($headerAboveCount2) :`
now use:
  `if ($headerAboveCount[ 1 ]) :`
  `if ($headerAboveCount[ 2 ]) :`
and so forth.

Replaced the oh so many `<jdoc:include type="modules"..>` with a native
`ConstructTemplateHelper::renderModules()` as a proxies to
`JModuleHelper::renderModule()`. This not only saves parsing time and memory,
but the HTML and PHP validation in the IDE will no complain any longer about
40-some invalid tags.

== Core Styles
Moved "core" stylesheets to ./css/core reducing name conflicts and more
flexibility in using `customStyleSheet`. Reserved names remaining:
template, editor, ie* commonly used in plugins. Files starting with
`test`or `x~` are ignored. See also: "Template Params".

Removed all `@charset "utf-8"` as they cause serious trouble if files are
concatenated and minified. Encoding ought to be handled by the text editor
and via .htaccess anyway (not included):
> ## force utf-8
> AddDefaultCharset utf-8
> AddCharset utf-8 .html .xml .rss
> AddCharset utf-8 .css .js .json

== Template Params
Moving the "core" styles to a subfolder simplified the exclusion filter of
`customStyleSheet` to use a "black-list" regular expression to hide both
unwanted  or system stylesheets
```exclude="(template|editor|ie\d+|test.+|x~.+)\.css" ```

== Stylesheets + logic.php
Refactored repetitive module class code into semi-configurable loops.

Many selectors are utterly over-specified and there are way too many id-selectors
in charge **for styling only**, leading into dangerous "specificity wars"(tm).
To calm that down for areas like columns, wrapper, modules, and gutters, more
friendly and semantic CSS classes were and will be introduced (also) via logic.php
to pull back many style rules to a sane applicable specificity level.

Rathern than having ONLY a gazillion 'count-x' that ALL require a brute-force
ID selector to format module columns, more subtle "one-for-all" classse were
added to those module containers, e.g. 'header-above', 'header-below' etc.

This allows to tackle all those elements at once were appropriate with a
simpler and single rule, rather than being required to write 6 or more.

Renamed the `count-x` classes for the alpha and beta colums to `colcount-x`.
Very unlikely those colums are meant to "flow" and squeezing the main-content.

== Index Default Layout
Switching to OOCSS for a lightweight "CSS Framework" saves lots of layout
conditions and simplifies columns and modules to a handvoll **cascading**
classes. http://oocss.org/

* replaced various Module styles using "jexhtml" with "mod".
* replaced loads of .clear and .clearfix with .line


