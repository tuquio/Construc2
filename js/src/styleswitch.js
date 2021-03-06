// http://www.alistapart.com/articles/relafont
// http://www.alistapart.com/articles/alternate
// brought into 2012 by WebMechanic.biz

// @link https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/forEach
[].forEach||(Array.prototype.forEach = function(fun, obj) {
	var T, k=0, O = Object(this), len = O.length >>> 0;
	if (obj){T=obj;} if (!(fun instanceof Function)){throw new TypeError();}
	while (k<len) {var v;if (k in O) {v=O[k];} fun.call(T, v, k, O); k+=1;}
});

(function (window, document, undefined)
{
	var styles = null, prev, current, forEach = Array.prototype.forEach;

	if (!window['StyleSwitcher']) {window['StyleSwitcher']={}}
	StyleSwitcher.buffer = [];

	function byName (n) {return document.getElementsByTagName(n);}
	function byId (n) {return document.getElementById(n);}

	function toggleStyle(elt) {
		current = elt.getAttribute('data-style');
		if (current !== prev) {
			byId(current).disabled = false;
			byId(prev).disabled = true;
		}
		prev = current;
	}

	function getPreferred() {}

	function createCookie(name, value, days) {
		var date, expires = '';
		if (days) {
			date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = '; expires=' + date.toGMTString();
		}
		document.cookie = name + '=' + value + expires + '; path=/';
	}

	function readCookie(name) {
		var nameEQ = name + '=', ca = document.cookie.split(';');
		for ( var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) === ' ') {
				c = c.substring(1, c.length);
			}
			if (c.indexOf(nameEQ) === 0) {
				return c.substring(nameEQ.length, c.length);
			}
		}
		return null;
	}

	var $ready = function () {
		current = readCookie('style') || getPreferred();

		var b = byName('script')[0];
		for (var link in StyleSwitcher) {
			StyleSwitcher.buffer.push('<li data-style="'+ StyleSwitcher[link]['id'] +'" class="switcher">'+ StyleSwitcher[link]['title'] +'</li>');

			if (StyleSwitcher[link]['href'] == '#') {
				// default ~ theme.css
				continue;
			}

			StyleSwitcher[link].node = document.createElement('LINK');
			StyleSwitcher[link].node.setAttribute('rel', 'alternate stylesheet');
			StyleSwitcher[link].node.disabled = true;

			['id','title','href'].forEach(function(prop) {
					StyleSwitcher[link].node.setAttribute(prop, StyleSwitcher[link][prop]);
				});
			b.parentNode.insertBefore(StyleSwitcher[link].node, b);
		}

		// try the DOM
		if (window.jQuery) {
			jQuery('#styleswitcher').html(StyleSwitcher.buffer.join('')).show();
			jQuery('#styleswitcher').live('click', function (evt) {toggleStyle(evt.target);});
		}
		else if (window.$$) {
	/*
			$$('#styleswitcher .switcher').each(function (elt) { });
	*/
		}
	},

	$unload = function() {
		createCookie("style", current, 365);
	};

	if (window.jQuery) {
		// assign via jQuery
		jQuery(document).ready($ready);
		jQuery(document).unload($unload);
	} else if (window.addEvent) {
		// assign via Mootools
		window.addEvent('domready', $ready);
		window.addEvent('unload', $unload);
	}

})(window, document);
