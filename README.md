Construc2 4 J!1.7+
==================
check [original repo](https://github.com/betweenbrain/Construct-Community-1.6)
to learn more about "Joomla Engineering Construct Template Framework for Joomla! 1.6"

**This** is a clone to fix and change things that don't work for me.

== Ideas / To-do
*	fix l10n issues (almost done)
*	make webfont list "live" (cacheable)
*	make jQuery version list "live" (cacheable)
*	add (a set of?) "previz" params to render dummy modules at various
	module positions during layout development

Changes
-------
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
Updated to include jQuery 1.6+ using alternative CDN sources.

The list of jQuery version is now maintaind in an array, and looped over
to add them in bulk to the select list, rather than adding each single
release "manually" as a single option.

Google CDN URLs now use a relative protocol URL to work with both http
and https.

Cleanup modChrome / modules.php
-------------------------------
Removed styles marked as deprecated, 'cos they're deprecated.

Added "OOCSS compliant" styles: mod, complex, pop, bubble.

Changed test for module content presence to the top using
``` php
	if (empty($module->content)) return;
```
If there is no content, there's no need to waste time and memory testing the
parameter and assigning them to other variables to eventually wrap the whole
function body in an if-statement.. and eventually output nothing.
``` php
	if ( !empty($module->content) ) {
		// lots of stuff here
	}
```
The code is now only executed if content actually exists.

Refactored logic.php
--------------------
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

Refactored helper.php
---------------------
Implemented some logic into ConstructTemplateHelper which (as a helper) ought
to be a static class but is not. Won't change this however, thus:
-	added constructor that requires the template object
-	renamed `$includeFiles` to `$layouts`
-	renamed `getIncludeFile()` to `getLayout()`
-	added support for static HTML "chunks" (for prototyping layouts)
-	added script and link "smart ordering" in <HEAD> to addess library and css
	dependencies (implemented as a onBeforeCompileHead event listener)
-	added `addLayout($basename, $scope=null)` where `$basename` is the layout
	file w/o suffix and `$scope` one of 'index', 'component', 'section'.
-	support for fluid interface in setters.

The $basename is adjusted accordingly:
-	index: to work with the custom CSS from params = $basename-index.php
-	component: given the component name ($option) = /component/$basename.php
-	section: given a section id (numeric) = /section/section-$basename.php

Added `dateContainer()` to support the proper language setting for the current
date. Accepts one of Joomlas date language keys (`DATE_FORMAT_LCx`) for proper
formatting, an optional "timestamp" (defaults to 'now'), and an element name as
the wrapper element for the date parts (default = 'span').
This method now should allow to format any (?) date value using these individual
(span) elements for each date part. (the whole feature is questionable to me,
but I left this in.)

Implemented an option for `getLayout()` to test for the
**active Menu Item** in order to auto-locate a corresponding layout file.
This works for component overrides and section-ids.

Google Web Font
---------------------
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

@TODO: as of Oct 2011 Webfonts has an API! Yes! Added API key field placeholder
       (as a "mental note") in order to read and cache font list "live" to avoid
       maintaining that growing static options list. Not implemented yet.

Module Positions
---------------------
Like the first `$googleWebFont` parameter, loads of module positions helper
variables also used an un-numbered first `$fooBar` and continue counting from
`$fooBar2` to `$fooBar6`.

This inefficiency has been fixed using arrays to quickly loop through all
variables and results in much less repetitive code.
@TODO the max amount of "columns" ought to be configurable. It's currently
frozen to FOUR(!) columns using a class constant in the helper. (Who the hell
needs 6 columns??)

For example `$headerAboveCount[0]` holds to total amount of modules found in
all of "header-above-1" to "header-above-6", wheras `$headerAboveCount[1]` to
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

Replaced the oh so many `<jdoc:include type="modules"..>` with native PHP
`ConstructTemplateHelper::renderModules()` as a proxy to
`JModuleHelper::renderModule()`. This not only saves parsing time and memory,
but the HTML and PHP validation in the IDE will not complain any longer about
40-something invalid tags.
Literaly outsouced module groups into separate .php files included only if a
group contains modules at all.

Core Styles
---------------------
Moved "core" stylesheets to ./css/core reducing name conflicts and more
flexibility in using `customStyleSheet`. Reserved names remaining:
template, editor, ie* commonly used in plugins. Files starting with
`test`or `x~` and others are ignored, too. See also: "Template Params".

Removed all `@charset "utf-8"` from .css as they cause serious trouble if files
are concatenated and minified. Encoding ought to be handled by the text editor
and via .htaccess anyway (not included):
> ## force utf-8
> AddDefaultCharset utf-8
> AddCharset utf-8 .html .xml .rss
> AddCharset utf-8 .css .js .json

Added experimental .css and .js concatenation and compression based on Apache
Server Side Includes (SSI). Using a custom .styles and .scripts in .htaccess.
These files concatenate individual .js and .css into a single file that also
supports Apache's native output compression support.
@TODO: find a smart and save way to "build" the .styles and .scripts files
       based on current contents in <head>

Template Params
---------------------
Moving all "core" styles to a subfolder simplified the exclusion filter of
`customStyleSheet` to use a "black-list" regular expression to hide both
unwanted  or system stylesheets
```exclude="(template|editor|ie\d+|test.+|x~.+)\.css" ```

Stylesheets + logic.php
---------------------
Refactored repetitive module class code into semi-configurable loops and
external module group rendering "sublayouts".

Many selectors are utterly over-specified and there are way too many id-selectors
in charge **for styling only**, leading into dangerous "specificity wars"(tm).
To calm that down for areas like columns, wrapper, modules, and gutters, more
friendly and semantic CSS classes were and will be introduced (also) via logic.php
to pull back many style rules to a sane applicable specificity level.

Rathern than having ONLY the gazillion 'count-x' to rely on that ALL require a
brute-force ID selector to format module columns, more subtle "one-for-all"
classse were added to those module containers, e.g. 'header-above', 'header-below'
etc. Leveraging the Power Of OOCSS.

This allows to tackle all those elements at once were appropriate with a
simpler and single rule, rather than being required to write 6 or more.

Renamed the `count-x` classes for the alpha and beta colums to `colcount-x`.
Very unlikely those colums are meant to "flow" and squeezing the main-content.
@TODO: alignnames with other column-based classes found in core templates and
       add/replace all with smart column count detection and .sizeXofY classes.

Index Default Layout
---------------------
Switching to OOCSS for a lightweight "CSS Framework" saves lots of layout
conditions and simplifies columns and modules to a handvoll **cascading**
classes. http://oocss.org/

* replaced various Module styles using "jexhtml" with "mod".
* replaced loads of .clear and .clearfix with .line

2011-10-03
.eof
