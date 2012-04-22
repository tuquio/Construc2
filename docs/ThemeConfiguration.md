= Theme Configuration Files
A Theme may provide its own optional configuration file as an alternative to the
equally optional form XML file or as a supplement to keep some settings "static".

The XML form file ("theme_name.xml") is excusively used in the Backend to provide
typical parameters that you can switch on or off, whereas the configuration file
is used in the Frontend as a supply for additional data or options not convered
by the parameter form, i.e. translation options or file locations.

Although the configuration file is essentially an INI file it MUST have the `.php`
file extension and it MUST contain a **leading PHP block** with a bare minimum of:
```php
<?php return; ?>
; INI configuration comes here!
```
to prevent direct calls via the browser.

Anything following the `?>` is considered configuration data and must comply with
PHP's INI formatting rules. While this may look unfamiliar, it's no different than
having HTML markup mixed with PHP code in the same file.

== Theme Class
The PHP block MAY contain an optional Theme class derived from `CustomTheme`
to provide additional logic to your layouts and what-not, i.e.
```php
<?php
class ExemplarTheme extends CustomTheme
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

```

== Configuration Sections (WIP)
For the most part, a custom configuration may extend or even replace the default
configuration found in `elements/settings.php`. You may add additional sections
at will and implement the required login to deal with it in your custom theme
class.

=== [layouts]

=== [cnd]

=== [scripts]

=== [subst]

=== [autocols]

=== [allow_empty]

== Separating the class and configuration
If you feel uncomfortable to have a PHP class and plain INI data in the same file
you may safely ommit the data part and store it in a separate "true" .ini file.
It's then up to you to take care reading the file.

