// http://www.alistapart.com/articles/relafont
// http://www.alistapart.com/articles/alternate
// brought on par with 2012 practice by WebMechanic.biz

(function (window, document,undefined) {
	function toggleStylesheet(evt) {
		evt = evt || window.event;
	}

	function setActiveStyleSheet(title) {
		var i, a;
		for (i = 0; (a = document.getElementsByTagName("link")[i]); i++) {
			if (a.getAttribute("rel").indexOf("style") != -1
			    && a.getAttribute("title")) {
				a.disabled = true;
				if (a.getAttribute("title") == title) {
					a.disabled = false;
				}
			}
		}
	}

	function getActiveStyleSheet() {
		var i, a;
		for (i = 0; (a = document.getElementsByTagName("link")[i]); i++) {
			if (a.getAttribute("rel").indexOf("style") != -1
			    && a.getAttribute("title") && !a.disabled) {
				return a.getAttribute("title");
			}
		}
		return null;
	}

	function getPreferredStyleSheet() {
		var i, a;
		for (i = 0; (a = document.getElementsByTagName("link")[i]); i++) {
			if (a.getAttribute("rel").indexOf("style") != -1
			    && a.getAttribute("rel").indexOf("alt") == -1
			    && a.getAttribute("title")) {
				return a.getAttribute("title");
			}
		}
		return null;
	}

	function createCookie(name, value, days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			var expires = "; expires=" + date.toGMTString();
		} else {
			expires = "";
		}
		document.cookie = name + "=" + value + expires + "; path=/";
	}

	function readCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for ( var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1, c.length);
			}
			if (c.indexOf(nameEQ) == 0) {
				return c.substring(nameEQ.length, c.length);
			}
		}
		return null;
	}

	var $ready = function() {
		var cookie = readCookie("style");
		var title = cookie ? cookie : getPreferredStyleSheet();
		setActiveStyleSheet(title);
		if (window.$$) {
			$$('#styleswitcher .switcher').each(function(elt) {});
		}
		if (window.jQuery) {
			jQuery('#styleswitcher').each(function(i, elt) {
				console.log(elt);
				jQuery(elt).bind('click', function(evt) {
					console.log(evt.target.data);
				});
			});
		}
	};

	var $unload = function() {
		var title = getActiveStyleSheet();
		createCookie("style", title, 365);
	};

	if (window.jQuery) {
		// assign via jQuery
		jQuery('document').ready($ready);
		jQuery('document').unload($unload);
	} else if (window.addEvent) {
		console.log('mooish');
		return;
		// assign via Mootools
		window.addEvent('domready', $ready);
		window.addEvent('unload', $unload);
	}

})(window,document);
