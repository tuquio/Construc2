Construc2 4 J!2.5
=================
Construc2 is a "HTML5 Template Framework" and as such it is neither a
"Design" nor does it ship with anything resembling a nice website.

Thanks to Joomla's template styles and Construc2's ability to create
"themes" as well as template "layouts" for almost any page the CMS
can produce, it's all up to your imagination and of course your PHP,
HTML(5), and CSS(3) skills.

**This** "Template" started as a fork of "Joomla Engineering Construct
Template Framework for Joomla! 1.6" primarily to add L10N support and
to tweak some of its great features that just didn't work for me.
However, Construc2 is **not compatible** with any former or present
flavor of JE's nice "Construct Framework"!

**Note:** The name will likely change (once more) as of Version 2.0 to
avoid any potential confusion. The new version will ship with a free
sample theme and is planned to be released in May 2012. At this point
the shared history with CTF will also vanish into oblivion.

Core Style Sheets
----------------
Construc2 ships with OOCSS from Nicole Sullivan for flexible grid and
"module" support.
You can learn more about OOCSS @ http://oocss.org or fork/download
the sources from https://github.com/stubbornella/oocss

`base.css` is a conglomerate of personal preferences and stuff stolen
from the Interwebs incl. but not limited to Eric Meyer, Yahoo!, Nicole
Sullivan, Dean Edwards, the CSS-WG, W3C, browser style sheets, and parts
of normalize.css for most HTML5 elements.
The "color scheme", if any, might be considered Black & White with a
hint of grey.

The `oocss.css` file that ships with Construc2 in a concatenation of
the original oocss.css (with more grid dividers) and mods.css incl.
the  `.complex` and `.pop` modules. It provides basic grid support
for RTL scripts and features the basic settings for (horizontal) menus.

jQuery support
--------------
Inclusion and `noConflict()` management of all jQuery versions with
support for alternative CDN sources via relative protocol URLs to work
with both http and https.

Optional Mootools removal
-------------------------
If you prefer jQuery for your DOM manipulation tricks, Ajax development,
or just happen to love throwing jQuery plugins onto your page, you can
easily and explicitely remove all traces of Mootools from your frontend;
or choose to force it.

**Note:** Mootools support cannot be disabled for frontend editing and will in fact be forced!

HEAD clean-up
-------------
More often than not, the ordering of styles and script added from plugins,
modules, components, the core, and the template may cause conflicts and
dependency failures. This ordering can be "optimized" to reduce (and potentially
avoid) any such issues.

This feature also removes various non-standard, non-validating meta elements.

MSIE CC grouping
----------------
ConstructTemplateHelper does the heavy lifting and amoth other this provides
"conditional comment grouping" for MSIE for all things going into the HEAD
element. Instead of cluttering the top of the markup, any hacks and scripts
to target a particular IE version are wrapped into a single CC and added
below the "good stuff".

Link and Script control
-----------------------
In addition to CC groups you gain control over attributes added to the <link>,
<meta> and <script> elements using ConstructTemplateHelper.

modChrome / modules.php
-----------------------
Module chrome features "OOCSS compliant" module styles via `<jdoc:include/>`
and explicit content plugin rendering in your custom layouts. This feature is
a left-over goodie from J!1.5/1.6. As of J!1.7 content plugins are also enabled
for modules. If you disable this module option, you can still overrule it using
the 'withevent' chrome style on a per theme and module position basis.

See also "Gone for good" below.

CSS Media "all"
---------------
All core styles are loaded using `media="all"`.
Despite reports to the contrary you won't save any bandwith separating styles
via `<link>` and different media attributes. Browsers will load those files
anyway although they're not applied if the media doesn't match. As a consequence
you may add the print rules to your "theme.css" right upfront and wrap them
inside `@media print {}`.
- Stoyan Stefanov: http://www.phpied.com/delay-loading-your-print-css/

A few rules for print media is already present in the core files, such as font
color and shadow resets, and `display:none` for navigation items.

The `print.css` is used (automatically) for the print version of the default
`component.php` layout. It provides some previz screen formatting and of
course some print rules if the page of the popup window is actually printed.

See "Apache SSI + Compression" below on how you can further improve performace
by letting Apache concatenate and compress those files for you on-the-fly.

Loads of classes
----------------
Construc2 gives you (and your designer) a myriad of very "smart" class names
out of the box to apply very fine grained styling for individual pages, blog
and list views, almost any "content items" incl. module type, menus, and
various navigation links.

Additional class names are derived fromtitles, component names, (parent-)
categories, content and menu aliases, module names, and the layout name itself.

Google Web Font
---------------
Google WebFonts (like all external ressources handled in Construc2) are loaded
using protocol relative URLs, so you can use them with either http or https.

As of Oct 2011 Google Webfonts has an API! Yes! The successor of Construc2
will then read and cache the official font list and to avoid the (hardcoded)
maintenance of the ever growing list of nice fonts.

Shims + Shivs + Switcher
------------------------
Construc2 ships with 'html5.js' and 'JSON2.js' to pimp older browsers.
It also brings you '-prefix-free' by Lea Verou to drop pesky vendor prefixes from your CSS3 files.
- https://github.com/aFarkas/html5shiv
- https://github.com/douglascrockford/JSON-js
- https://github.com/LeaVerou/prefixfree

The good ole Styleswitcher script published some 10 years ago at ALA needed
a facelist. Whilst functionality remains essentially the same, it's less
obstrusive and no longer polutes the global JS namespace.

Module Positions
----------------
The primary Module position groups that make part of the overall page layout
are rendered via external .php files located in the ./layouts folder rather
than `<jdoc:include type="modules" />`.

A "better" menu module layout
-----------------------------
If you love styling individual menu items, you may give this alternative layout
a shot. To use it head over to the Modul Manager, pick a menu module and select
"better" in the Advanced Options panel. Enjoy.
See "HTML5 Validation" section below.

This layout used the `<menu>` element (instead of `<ul>`) as the root container
to explicitely "force" you to redact or rework your style sheets. The `<menu>`
element has been part of HTML from the very, very early days and despite its
superior semantic meaning was dropped by the W3C as of HTML4 only to be revived
with additional "features" in HTML5.
Browsers always supported this yelde element, thus without any styling applied
to it `<menu>`  (erroneously) both looks and acts like the `<ul>` element --
the primary reason why the W3C considered it to be redundant.

- http://www.w3.org/TR/1999/REC-html401-19991224/struct/lists.html#h-10.4
- http://www.w3.org/TR/REC-html32-19970114

Gone for good
-------------
You won't find any notion of the `pageclass_sfx` in any page layout or override.
The 'C' in CSS stands for *cascading* and in it's standard use and implementation
`pageclass_sfx` essentially destroys this cascade easily.
As a site implementor or webmaster you may also dislike to give "a designer"
access to your backend and to the menu system in order to access this parameter.
As you dig into Construc2 and learn how the supplemental class names come into
existance and use, you or your designer won't miss this.

The `moduletable` class also vanished from modules rendered thru Construc2, that
is the main layout positions. Not only is the "table" a total misnomer and relic
from Mambo, the much more flexible `.mod` class allows for the same thing.
And it's shorter to type :)

Component Layout Overrides
--------------------------
Every "release" of Construc2 either adds or (attempts) to improve the output and
markup of the CMS core component layouts. The com_content overrides are ripped
from Angie Radtke's Beez5 (when J!1.6 was current) as a kickstart because of
their great and extensive use of classes.
The markup has since change a lot and the original XHTML/HTML5 switch was
"of course" removed. A few common markup fragments now reside in "sub layouts"
to be shared across diffent layouts, namely the author info or readmore.

Apache SSI + Compression
------------------------
One of the more "esoteric" features of Construc2 is the ability to let Apache
concatenate and optional compress (gzip) all your .js and .css.
Output compression might not be available on shared hosts, yet Apache should
support "Server Side Includes" in which case you should try this feature and
enable it via .htaccess (see htaccess.txt)

Using a custom `.styles` and `.scripts` to "load" all the required files, Apache
will concatenate each group into a single file resulting in a single HTTP request
instead of many. Native output compression will then be applied if supported.

**Note:** If you enable this feature **all** URLs in .css files used for background
images or @imports must be given by their absolute path.

Philosophy, sort of
-------------------
Construc2 aims to give you all the power of CSS(3) as much as it can and tries
to let your site perform a bit better, i.e. using SSI.
Some purists dislike this "class bloat" or call it "cruft". YMMW and you're free
to use or ignore them or change the overrides if you like.

In addition to the new HTML5 elements you also get a bunch of grips to pin your
CSS rules. Browsers won't care if this adds 200 or 300 bytes to your final page,
but you take control over a great many of page elements that you couldn't style
easily or would require lengthy selectors and repetitive code.
At the end, the markup might be langer, but your style sheets will be much smaller
nonetheless and rock solid.

Understanding the cascade and specificity of style selectors not only helps to
improve browser performance a tad, but also keeps maintenance time/costs low.

All core .css files are usually "grouped" into blocks, starting with the positions
and dimensions of things, followed by colors, fonts, and finally a pinch of CSS3
eye-candy and "hacks" if needed. Print rules appear last.


CSS Validation
--------------
If you try to run any of the .css you may find it doesn't "validate". These
warnings or errors are either causes by vendor prefixes or some IE hacks.

HTML5 Validation
----------------
If you're a Validator addict and believe the world would fall apart, and your
computer will explode if a Validator software tells your, the **grammar** of
the HTML sucks, then please move along and stick with XHTML.

HTML5 support still varies greatly even in most recent browsers, let alone
browser elumators from Redmond. Browsers do not "spell-check" HTML documents,
yet they can make perfect sense of almost everything you throw at them.

Because Construc2 features a bunch of layout overrides that generate HTML5
your page will very likely pass any validator without it complaining.
Most notably, browsers **do** act according to the HTML spec and just ignore
stuff they don't know. However, they also support any tag, element and attributes
that has ever been standardizes for decades, no matter what the DOCTYPE suggests
(unless you're managed to serve your perfect XHTML documents with its appropriate
MIME type `application/xml+xhtml`.)

validators are not browsers. End of story. They check your markup irregardless
of the fact that a real browser will indead understand the HTML just fine,
like a human would understand someone mumbling.

Ideas
-----
Mental notes for things that might come (in more or less the following order):
#	[WIP] add module position mapping to easy migration for sites using J's standard templates (Beez, Beez2, Beez5, Purity)
#	[WIP] "delegate" (more) parts of `login.php` into `ConstructTemplateHelper` to reduce variable clutter.
#	improve WAI-ARIA support
#	allow to exclude module positions from using the `.mod` class
#	add support for rel="canonical" URLs to main layouts
#	add backend (only) plugin for custom theme confguration
#	add API key support and caching of the Google WebFont list
#	make the jQuery version list "live" (cacheable) or easier configurable w/ hacking the code
#	add (a set of?) "previz" params to render dummy modules at various module positions during layout development
#	add 'apply to all' button to copy selected parameters from the edited template style to other
#	improve RTL support

2012-04-03
.eof
