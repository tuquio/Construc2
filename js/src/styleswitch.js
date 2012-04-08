// http://www.alistapart.com/articles/relafont
// http://www.alistapart.com/articles/alternate
// brought on par with 2012 practice by WebMechanic.biz

(function (window, document, undefined) {
	function toggleStylesheet(evt) {
		evt = evt || window.event;
	}

	function setActiveStyleSheet(title) {
		var i, a;
		console.log('switcher.js', 'setActiveStyleSheet', title);
		for (i = 0; (a = document.getElementsByTagName("link")[i]); i++) {
			if (parseInt(a.getAttribute("rel").indexOf("style"), 10) !== -1 && a.getAttribute("title")) {
				a.disabled = (a.getAttribute("title") === title);
			}
		}
	}

	function getActiveStyleSheet() {
		var i, a;
		for (i = 0; (a = document.getElementsByTagName("link")[i]); i++) {
			if (parseInt(a.getAttribute("rel").indexOf("style"), 10) !== -1 && a.getAttribute("title") && !a.disabled) {
				return a.getAttribute("title");
			}
		}
		return null;
	}

	function getPreferredStyleSheet() {
		var i, a;
		for (i = 0; (a = document.getElementsByTagName("link")[i]); i++) {
			if (parseInt(a.getAttribute("rel").indexOf("style"), 10) !== -1 && parseInt(a.getAttribute("rel").indexOf("alt"), 10) === -1 && a.getAttribute("title")) {
				return a.getAttribute("title");
			}
		}
		return null;
	}

	function createCookie(name, value, days) {
		var date, expires = "";
		if (days) {
			date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toGMTString();
		}
		document.cookie = name + "=" + value + expires + "; path=/";
	}

	function readCookie(name) {
		var nameEQ = name + "=", ca = document.cookie.split(';');
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

	var $ready = function () {
		var cookie = readCookie("style");
		var title = cookie ? cookie : getPreferredStyleSheet();
		console.log('switcher.js', title, cookie);
		setActiveStyleSheet(title);
		if (window.jQuery) {
			jQuery('#styleswitcher')
				.find('.switcher')
				.each(function (i, elt) {
					if ($('#' + $(elt).data().style + '-css').length === 0) {
						$(elt).remove();
					}
				})
				.end()
				.live('click', function (evt) {
					setActiveStyleSheet($(evt.target).data().style);
				})
				.toggle();
		}
		else if (window.$$) {
			$$('#styleswitcher .switcher').each(function (elt) { /* do sth. mooish */ });
		}
		else {
		}
	};

	var $unload = function() {
		var title = getActiveStyleSheet();
		createCookie("style", title, 365);
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
