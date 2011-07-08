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
Added missing l10n keys to replace the many hardcoded english strings, still WIP.

Removed identical code from main `index.php` leaving `/layouts/index.php`
the default layout if none is provided or found to match the custom CSS in the 
template params.

Both `logic.php` and `helper.php` are now loaded unconditionally.
There appears to be no reason to validate their filenames or check if they
exist, since none of the stuff this template advertizes would work anyway
should the files be missing.

== Refactored logic.php
Put some of its "logic" on a diet and killed redundancy = ~30% less code.

Some param values are now forced to a proper type, e.g. boolean or number. 
Also some resulting "actions" to the document object happened unconditionally
causing issues on the client side.

Setting `$loadModal` now forces `$loadMoo` to be true, as the former depends
on it. Updated calls of deprecated `JHtml::_('behavior.foo')` to
`JHtmlBehavior::foo()` were appropriate.

Added support to use favicon.ico in case favicon.png does not exist.

=== Google Web Font
Changed wording of "html elements, CSS IDs, and classes to apply" to just 
"CSS selectors", which is what they essentially are and what's required.

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
Care was taken however to use `$googleWebFont` for `$googleWebFont1` at runtime
if the template params were **not** edited.

=== Module Positions
Like the first `$googleWebFont` parameter, loads of module positions helper 
variables also use an un-numbered first `$fooBar` to continue counting from 
`$fooBar2` to `$fooBar6`. 

This inefficiency has been fixed using `$fooBar1` as well, which now allows 
to quickly loop through all variables and results much less repetitive code.
 
In fact `$fooBar` et al became arrays `$fooBar[]` with element from 1 to 6 
(or whatever the original amount of variables was). Each array element holds
the number of modules in the given position. 

>	**BC break**:
>	The conditional statement in `index.php` to check for **any** modules  
>	in a set of module positions has changed!
Before:
 `if ($headerAboveCount)`
After:
 `if ( array_sum($headerAboveCount) )`

To test for a particular position rather than
  `if ($headerAboveCount1) :`
  `if ($headerAboveCount2) :`
now use:
  `if ($headerAboveCount[ 1 ]) :`
  `if ($headerAboveCount[ 2 ]) :`
etc.

$columnGroupAlphaCount

== Stylesheets + logic.php
Most selectors are utterly over-specified and there are way too many id-selectors 
in charge, leading into insane "specificity wars"(tm).
To reduce this for areas like columns, wrapper, modules, and gutters, more 
decent CSS classes were introduced (also) via logic.php to allow getting the 
stylesheet back to a sane level.

Rathern than having ONLY a gazillion 'count-xx' that ALL require a brute-force 
ID selector to format module columns, some useful "one-for-all" classse were
added to those elements (modules), e.g. 'header-above', 'header-below' etc.
This allows to tackle all those elements at once were appropriate with a 
simple single rule, rather than having to write 6 or more

== Refactored helper.php
Implemented some logic into ConstructTemplateHelper which (as a helper) ought
to be a static class, however:
-	added constructor, requires the template name (~ folder)
-	renamed includeFiles to layouts
-	renamed getIncludeFile() to getLayout()
-	added addLayout($basename, $scope=null) where $basename is the layout 
	file w/o suffix and $scope one of 'index', 'component', 'section'.

Supports fluid interface for bulk layout addition and little typing.

The $basename is adjusted accordingly:
-	index: to work with the custom CSS from params = $basename-index.php
-	component: given the component name ($option) = /component/$basename.php
-	section: given a section id (numeric) = /section/section-$basename.php

Also implemented some "magic" into getLayout() to test for the 
**active Menu Item** in order to auto-locate the "required" layout file. 
This works for component overrides and section-ids.

Added `dateContainer()` as a method of ConstructTemplateHelper to support the
proper language setting for the current date. Accepts one of Joomlas date
language keys (`DATE_FORMAT_LCx`) for proper formatting, an optional "timestamp" 
(defaults to 'now'), and an element name as the wrapper element for the
date parts (default = 'span').
This method now allows to format any (?) date value using individual elements
for each date part.


== Refactored jqueryversion.php
Updated to include 1.6.2.

The list of jQuery version is now maintaind in an array, and looped over
to add them in bulk to the select list, rather than adding each single
release "manually" as a single option.

Google CDN URLs now use a relative protocol URL to work with both http
and https.

== Cleanup modChrome / modules.php
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








