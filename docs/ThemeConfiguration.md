# Themes
A Theme in Kaskade (Construc2) consists at least of a single CSS file you drop
into the `./themes` folder. The basename of that file is then used as the Theme
name and supplemental files can be added to automagically enhance the theme.

# Theme Configuration Files
A Theme may provide its own optional configuration file as an alternative to the
equally optional form XML file or as a supplement to keep some settings "static".

The XML form file ("theme_name.xml") is excusively used in the Backend to provide
typical parameters that you can switch on or off, whereas the configuration file
("theme_name.php") is used in the Frontend as a supply for additional data or
options not convered by the parameter form.

Although the configuration file is essentially an INI file it MUST have the `.php`
file extension and it **MUST** contain a **leading PHP block** to prevent direct calls
via the browser with a bare minimum of:

	<?php return; ?>
	; INI configuration comes here!

Anything following the `?>` is considered *configuration data* and **MUST** comply
with PHP's INI formatting rules. While this may look unfamiliar, it's no different
than having HTML markup mixed with PHP code in the same file.

## Theme Class
The PHP block **MAY** contain an optional Theme class derived from `ConstructTemplateTheme`
to provide additional logic to your layouts and what-not, i.e.

	<?php
	class ExemplarTheme extends ConstructTemplateTheme
	{
	/* .... */
	}
	?>
	; --------------------------
	; Exemplar INI configuration
	; --------------------------
	name		= "Exemplar"
	title		= "Exemplar Theme"
	description	= "Example theme for Construc2"

	[layouts]
	; custom page layouts

	[scripts]
	; additional javascripts

## Configuration Sections (WIP)
For the most part, a custom configuration may extend and sometimes replace the
default configuration found in `elements/settings.php`. You may add additional
sections at will and implement the required login to deal with it in your custom
theme class.

Several core Features and Widgets don't have a GUI for their configuration although
they may be turned on/off in the backend.

### [layouts]

### [cdn]

### [scripts]

### [subst]

### [autocols]

### [allow_empty]

## Separating the class and configuration
If you feel uncomfortable to have a PHP class and plain INI data in the same file
you may safely ommit the data part and store it in a separate "true" .ini file.
It's then up to you to take care reading the file.

## XML Form Fieldsets (WIP)
Your XML files to add Features, Widgets and Theme parameters into the template
style configuration forms **SHOULD** structure their fields into the follwoing
sections.

Note that some of them may overlap in meaning and purpose, i.e "standards" and
"quirks", "features" and "widgets", "javascripts" and ... any of them. By the end
of the day most are made from a few lines of PHP, some simple HTML, CSS and a
bunch of JavaScript. This makes it hard to decide where to put it. Here are
some hints.

### theme
Anything that applies to the visual presentation of the theme in the Frontend,
i.e. colors, images, styling. That's where "regular" templates would put their
parameters to add and control background images, panel widths, gutter sizes,
what-have-you, to make Designers happy. It's the non-techy section so to speak.

If you happen to create additional Page layouts (./layouts) or have your own
set of Joomla! Layout Overrides (./html), that's where you probably want to use
these "theme" parameters.

Themes may also provide a less user friendly way of configuration using a .php
file in the ./themes folder. As a developer you may wish to keep some things
flexible w/o the need to login and configure a simple value or a path in the
backend. It's often much easier (and safer to process) a few lines of code
instead of parsing some fancy HTML form and to evaluate a gazilliion parameters.
See "Configuration Sections" about how several core Features and Widgets are
configurable only by editing the config file(s)).

### javascripts
Add or mess with (core) javascript files and **libraries**.
Here's where your jQuery, YUI, Dôjô, what-have-you code should go, yet all those
fancy clientside, scrit based gallery plugins, sliders, and Ajax toys are quit often
a very good candidate for a *Widget*.

### standards
Shims, Shivs, Polyfills, and other means to improve webstandards compatibility on
the client side. With browsers improving, anything in this section should become
obsolete in the bright future.
If your addon attemts to fix a bug or weird behavior of some client *OR* the CMS
consider adding it as a *quirk*.

The `-prefix-free` addon is part of this group as it simplifies CSS3 support
to make better use of (a) webstandard. Same goes for the HTML5shiv and JSON2
-- despite they're no longer required by the majority of modern browsers.
Most "features" that deal with MSIE exclusively deal with properietary M$ extensions
(X-UA-Compatible) and fix bugs or plain wrong behavior in older IE versions. Since
they fix browser specific *quirks* (even if the results improve on webstandards)
you should not add them into this group.

### features
The win and doubt group for almost anything else. Features primarily work in the
background (like system plugins) and unlike Widgets don't add "visual" output.
Things like the HTML5 manifest attribute, a CSS or JS compressor, script loader,
WAI utilities, and similar means that improve usability, performace, security,
accessibility, ...

### widgets
Visual extensions, elements, GUI enhancements. Widgets add visual content to the
page and are often dynamic by nature. Users are supposed to interact with a Widget.
Once you have jQuery enabled in the JavaScript section, here's where you can dump
and configure all your Plugins: galleries, sliders, and all the gazillion "Like"
and "Share" buttons.

### quirks
Stuff that tries to fix quirks, cotchas, and oddities in the client or CMS.
Almost all the fixed for MSIE reside here. Should you have a need to fix another
browser's issue: here's the perfect place to put it.
The CMS also has a tendency to do weired, stupid or anoying things (like missing
an *obvious parameter* in a Module etc.) and you may add your fix here instead
of writing a regular Plugin.

