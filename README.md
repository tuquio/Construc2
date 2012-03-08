# Construc2 4 J!2.5
Construc2 is a HTML5 "Template Framework" and as such it is neither a "Design" nor does it ship with anything resembling a nice website.

It's build for the Joomla!&trade; CMS (v2.5) and thanks to Joomla's template styles and Construc2's ability to create "themes" on top of that incl. "page layouts" for virtually any page the CMS can produce, it's all up to your imagination and of course your PHP, HTML, and CSS skills, what your website will look like.

**This** "Template" started as a fork of "Joomla Engineering Construct Template Framework for Joomla! 1.6" (CTF) primarily to kickstart the migration of some of my older template tools written for J!1.5. CTF also lacked L10N support and I had the urge to tweak some features that just didn't happen to be useful for me. That being said, Construc2 soon cut it's own path and is **not compatible** with any former or present flavor of JE's nice "Construct Framework"!

**Note:** The name will likely change (once more) with the release of Version 2.0 to avoid any potential confusion. The new version will also ship with a free sample theme and is planned to be released by May 2012. At that point the shared history with CTF will also fall into digital oblivion.

## Intended Audience
As mentioned in the introduction you should have a fair amount of skills editing HTML and writing efficient CSS. You don't have to program any PHP -- although you may. A good understanding of how J! processes the view layouts and template might help to create more page layout variations if you need to.

If you're a copy-and-paste coder, Construc2 is probably the wrong tool for you.

If you're a parameters junkie and love clicking backend panels to make your website shine, then Construc2 is definitely the wrong tool for you. There are next to none parameters that allow you to change colors, header image urls, or any such thing. That's what Cascading Style Sheets were invented for and you're supposed to use them.

Construc2 will allow you to make your template and frontend "design friendly" and potentially faster, giving you a couple of parameters and configuration files to add missing features (polyfills, shims) for antique browsers.

## Core Style Sheets
Construc2 ships with **OOCSS** by _Nicole Sullivan_ to bring you a flexible, solid grid system with great modularity. Flexibility includes anything from fixed pixel widths to fluid layouts with almost no limitation on grid "columns".

You can learn more about OOCSS @ http://oocss.org or fork/download the sources from https://github.com/stubbornella/oocss
You may definitly want to search for Nicole on YouTube and watch some of her talks.

### base.css
`base.css` is what others would label a "reset", but it isn't. The bad use of a typical "reset" style sheet usually causes more harm than good and more often than not results in monstrous .css files desperately trying to _de-reset_ and rebuild what was destroyed in the first place.

This base file is a conglomerate of subtle personal preferences and some of the Good Things "found" on the Interwebs incl. but not limited to things made by Eric Meyer, Yahoo!, Nicole Sullivan, Dean Edwards, the CSS-WG, W3C, browser style sheets, and a good chunk from `normalize.css`.

The "color scheme", if any, might be considered Black & White with a hint of grey.

### oocss.css
The `oocss.css` file that ships with Construc2 in a concatenation of the original oocss.css (but more dividers) and `mods.css` incl. the  `.complex` and `.pop` modules. It provides rudimental support for RTL scripts and features the basic settings for (horizontal) list-based navigation via ul, menu, and nav.

## Optional jQuery support
Inclusion and `noConflict()` management of all jQuery versions with support for alternative CDN sources.

## Optional Mootools removal
If you prefer jQuery for fancy DOM manipulation tricks, Ajax development, or just happen to love throwing jQuery plugins onto your page, you can easily and explicitely remove all traces of Mootools from your frontend; or choose to force loading it.

**Note:** Mootools support cannot be disabled for frontend editing and will in fact be forced!

## HEAD clean-up
More often than not, the ordering of styles and script added from plugins, modules, components, the core, and the template may cause conflicts and dependency issues. This default ordering can be "optimized" to reduce and potentially avoid such issues.

This feature also removes various non-standard, non-validating meta elements.

### MSIE CC grouping
ConstructTemplateHelper does the heavy lifting within Construc2 and amoth other things provides "conditional comment grouping" for MSIE for all things going into the HEAD element. Instead of cluttering the top of the markup, any hacks and scripts to target a particular IE version are wrapped into a single CC per version, and moved below the "good stuff".

### Link and Script control
In addition to CC groups you gain control over attributes added to the <link>, <meta> and <script> elements using ConstructTemplateHelper if you create your own page layouts.

## modChrome / modules.php
Module chrome features an "OOCSS compliant" module styles via `<jdoc:include/>` and allows for explicit content plugin rendering in your page layouts. As of J!1.7 content plugins are also enabled for modules. If you disable this module option, you can still overrule it using the 'withevent' chrome style on a per theme and module position basis.

See also "Gone for good" below.

## CSS Media "all"
All core styles are loaded using `media="all"`.
Despite reports to the contrary you won't save any bandwith separating styles with `<link>` and different media attributes. Browsers will load those files anyway although they're not applied if the media doesn't match. As a consequence you may as well add your print rules to your "theme.css" right upfront and wrap them inside `@media print {}` to save that extra HTTP request.
- Stoyan Stefanov: http://www.phpied.com/delay-loading-your-print-css/

See "Apache SSI + Compression" below on how you can further improve performace by letting Apache concatenate and compress those files for you on-the-fly.

### Print styles
A few rules for print media are already present in the core files, such as (font) color and shadow resets, and `display:none` for navigation items.

The `print.css` is used (automatically) for the print preview popup based on the `component.php` layout. It includes some simple previz screen formatting and of course some print rules if the page of the popup window is actually going to be printed.

## Loads of classes
Construc2 gives you (and your designer) a myriad of "smart" and semantic class names out of the box to apply very fine grained styling of the markup for individual pages, blog and list views, almost any "content items" incl. different modules, menus, and various navigation links.

Additional class names are derived from titles, component names, (parent-) categories, content and menu aliases, module names, and the layout name itself.

## Google Web Font
Google WebFonts (like all external ressources handled in Construc2) are loaded using protocol relative URLs, so you can use them with either http or https.

As of Oct 2011 Google Webfonts has an API! Yes! The successor of Construc2 will then read and cache the official font list and to avoid the (hardcoded) maintenance of the ever growing list of nice fonts.

## Shims + Shivs + Switcher
Construc2 ships with 'html5.js' and 'JSON2.js' to pimp older browsers.
It also brings you '-prefix-free' by Lea Verou to drop pesky vendor prefixes from your CSS3 files.
- https://github.com/aFarkas/html5shiv
- https://github.com/douglascrockford/JSON-js
- https://github.com/LeaVerou/prefixfree

The good ole Styleswitcher script published some 10 years ago at ALA needed a facelist. Whilst functionality remains essentially the same, it's less obstrusive and no longer polutes the global JS namespace.

## Module Positions
The primary Module position groups that make part of the overall page layout are rendered via external .php files located in the ./layouts folder rather than `<jdoc:include type="modules" />`.

## A "better" menu module layout
If you love styling individual menu items, you may give this alternative layout a shot. To use it head over to the Modul Manager, pick a menu module and select "better" in the Advanced Options panel. Enjoy.
See "HTML5 Validation" section below.

This layout used the `<menu>` element (instead of `<ul>`) as the root container to explicitely "force" you to redact or rework your style sheets. The `<menu>` element has been part of HTML from the very, very early days and despite its superior semantic meaning was dropped by the W3C as of HTML4 only to be revived with additional "features" in HTML5.
Browsers always supported this yelde element, thus without any styling applied to it `<menu>`  (erroneously) both looks and acts like the `<ul>` element -- the primary reason why the W3C considered it to be redundant.

- http://www.w3.org/TR/1999/REC-html401-19991224/struct/lists.html#h-10.4
- http://www.w3.org/TR/REC-html32-19970114

## Gone for good
You won't find any notion of the `pageclass_sfx` in any page layout or override. The 'C' in CSS stands for *cascading* and in it's standard use and implementation `pageclass_sfx` essentially destroys this cascade easily.
As a site implementor or webmaster you may also dislike to give "a designer" access to your backend and to the menu system in order to access this parameter.
As you dig into Construc2 and learn how the supplemental class names come into existance and use, you or your designer won't miss this.

The `moduletable` class also vanished from modules rendered thru Construc2, that is the main layout positions. Not only is the "table" a total misnomer and relic from Mambo, the much more flexible `.mod` class allows for the same thing.
And it's shorter to type :)

## Component Layout Overrides
Every "release" of Construc2 either adds or (attempts) to improve the output and markup of the CMS core component layouts. The com_content overrides are ripped from Angie Radtke's Beez5 (when J!1.6 was current) as a kickstart because of their great and extensive use of classes.
The markup has since change a lot and the original XHTML/HTML5 switch was "of course" removed. A few common markup fragments now reside in "sub layouts" to be shared across diffent layouts, namely the author info or readmore.

## Apache SSI + Compression
One of the more "esoteric" features of Construc2 is the ability to let Apache concatenate and optional compress (gzip) all your .js and .css.
Output compression might not be available on shared hosts, yet Apache should support "Server Side Includes" in which case you should try this feature and enable it via .htaccess (see htaccess.txt)

Using a custom `.styles` and `.scripts` to "load" all the required files, Apache will concatenate each group into a single file resulting in a single HTTP request instead of many. Native output compression will then be applied if supported.

**Note:** If you enable this feature **all** URLs in .css files used for background images or @imports must be given by their absolute path.

## Philosophy, sort of
If there's such thing then the Mantra would be: avoid Backend Parameters to style your website. J! provides a fair share of supplemental data while rendering the template and layouts, but few Template authors make any use of what can be found in "$this->item" or the current page/menu.
As you may might have guessed by now: Construc2 takes a much closer look at the things Joomla! throws at it and turn it into semantic HTML, seasoned with id and useful class attributes.

Some self-acclaimed purists dislike this "class bloat" or call it "cruft", but your mileage may vary.
Construc2 aims to give you all the power of CSS(3) as much as it can and tries to let your site perform a bit better, i.e. using SSI and CDNs for the external resources, and help you to eventually write **less CSS**.
Understanding the cascade and specificity of style selectors not only helps to improve browser performance a tad, but also keeps maintenance time/costs low.

In addition to modern and semantic HTML5 elements you also get a bunch of grips to pin your CSS rules on. Browsers won't care if this adds 200 or 300 bytes to your final page, but you take control over a great many semantic elements that you couldn't style easily or would require lengthy selectors and repetitive code.
At the end, the markup might be a bit longer (you do use output output compression, do you?), but your style sheets will be much
smaller nonetheless and rock solid.

All core .css files are usually "grouped" into blocks, starting with the positions and dimensions of things, followed by colors, fonts, and finally a pinch of CSS3 eye-candy and "hacks" if needed. Print rules appear last.

## CSS Validation
If you try to run any of the .css you may find it doesn't "validate". These warnings or errors are either causes by vendor prefixes or some IE hacks.

## HTML5 Validation
If you're a Validator addict and believe the world would fall apart, and your computer will explode if a Validator software tells your, the **grammar** of the HTML sucks, then please move along and stick with XHTML.

HTML5 support still varies greatly even in most recent browsers, let alone browser elumators from Redmond. Browsers do not "spell-check" HTML documents, yet they can make perfect sense of almost everything you throw at them.

Because Construc2 features a bunch of layout overrides that generate HTML5 your page will very likely pass any validator without it complaining. Most notably, browsers **do** act according to the HTML spec and just ignore stuff they don't know. However, they also support any tag, element and attributes that has ever been standardizes for decades, no matter what the DOCTYPE suggests (unless you're managed to serve your perfect XHTML documents with its appropriate MIME type `application/xml+xhtml`.)

Validators are no browsers. End of story. 
They spell-check your markup, they don't render it. Browser will indead understand HTML just fine, like a human would understand someone mumbling.

# Ideas
Mental notes for things that might come (in more or less the following order):

- [WIP] add module position mapping to easy migration for sites using J's standard templates (Beez, Beez2, Beez5, Purity)
- [WIP] "delegate" (more) parts of `login.php` into `ConstructTemplateHelper` to reduce variable clutter.
- improve WAI-ARIA support
- allow to exclude module positions from using the `.mod` class
- add support for rel="canonical" URLs to main layouts
- add backend (only) plugin for custom theme confguration
- add API key support and caching of the Google WebFont list
- make the jQuery version list "live" (cacheable) or easier configurable w/ hacking the code
- add (a set of?) "previz" params to render dummy modules at various module positions during layout development
- add 'apply to all' button to copy selected parameters from the edited template style to other
- improve RTL support

2012-04-03
.eof
