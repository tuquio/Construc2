/*!
 * StyleSwitcher - an ALA compliant replacement.
 * @version   0.1.0
 * @copyright (c)2012 WebMechanic. Some rights reserved.
 * @licence   CC BY-NC 2.0/de
 */
;(function (W, D, undefined) {
	"use strict";
	var Switcher = {
		// list container element
		elt: null,
		// list of style sheets
		styles: {'_default':'normal'},
		// CSS files location
		baseUrl: D.location.pathname.split('/').slice(0, -1).join('/'),
		// default list container id, can be set with StyleSwitcher.list
		LIST: 'styleswitcher',
		ITEM_CLASS: 'mi switcher {name}',
		// storage key name
		KEY_NAME: 'style',
		// properties to from from .styles
		exclude: /(default|href)/i,

		/*
		* initialize `elt_id` as a container for the Switcher.
		* look for 'data-styles', then 'LI', and return if neither exists.
		*
		* @param	elt_id	String the element id
		* @param	D		The document object
		*/
		init: function(elt_id) {
			this.elt = D.getElementById(this.LIST);
			this.elt.style.display = 'none';

			this.readStyles()
				.restore();

			this.elt.style.display = '';

			if (this.elt.addEventListener) {
				this.elt.addEventListener('click', this.set, false);
			} else if (this.elt.attachEvent) {
				this.elt.attachEvent('onclick', this.set);
			}
		},

		/* list item click handler
		* @param  evt  MouseEvent
		* @return bool
		*/
		set : function(evt) {
			evt = evt || window.event;
			evt.stopPropagation();
			evt.cancelBubble = true;
			var li = evt.srcElement||evt.target;
console.log('set', li.dataset, li);

			return false;
		},

		/* page load event handler
		* @param  evt  Event
		* @return boolean
		*/
		load: function(evt) {
			if ('StyleSwitcher' in W) {
				Switcher.LIST = W.StyleSwitcher.list || Switcher.LIST;
			}
			Switcher.init.call(Switcher);
			return true;
		},

		/* page unload event handler
		* @param  evt  Event
		* @return boolean
		*/
		unload: function(evt) {
			// stores the current style name
console.log('unload', this);
			evt = evt||window.event;
			return true;
		},

		/* apply the chosen stylesheet and disable the "old" if there is one
		* @return Switcher
		*/
		apply: function() {
console.log('apply', this, D);
			return this;
		},

		/* saves the name of the selected style
		* @return Switcher
		*/
		store: function () {
console.log('store', this);
			return this;
		},

		/* retrieves the name of the last used style
		* @return Switcher
		*/
		restore: function () {
console.log('restore', this);
			// read cookie || storage

			// show or build list items

			return this;
		},

		/* find style names from:
		* - "data-styles" attribute of list container
		* - OR existing LI elements in list container
		* @return Switcher
		*/
		readStyles: function() {
			var data = this.elt.getAttribute('data-styles');
			if (data) {
				this.styles = JSON.parse(data);
				return this.createList();
			}
			return this.readItems();
		},

		/* "classic": read and update existing LI from this.elt
		* @return Switcher
		*/
		readItems: function() {
			var items, i, l, name, title;
			items = this.elt.getElementsByTagName('LI');
			l = items.length;
			if (l === 0) {
				return this;
			}

			for (i=0; i < l; i += 1) {
				title = items[i].textContent;
				// sync missing attributes
				if (!items[i].title) {
					items[i].title = title;
				}
				// quick interface check
				if (items[i].dataset && items[i].dataset instanceof window.DOMStringMap) {
					name = items[i].dataset['style'];
				} else {
					// read as attribute
					name = items[i].getAttribute('data-style');
					// create a simple object for oldIE
					items[i].dataset = {style:name};
				}

				if (!name) {
					name = (items[i].title || title).toLowerCase();
				}
				items[i].dataset.style = name;
				this.styles[name] = title;
			}
			return this;
		},

		/* create LI items in this.elt for each "data-style"
		* @return Switcher
		*/
		createList: function() {
			var li, list = D.createDocumentFragment();
			// clear list
			this.elt.innerHTML = '';
			// build LI items for .styles
			for (var prop in this.styles) {
				if (this.styles.hasOwnProperty(prop)) {
					prop = prop.toString();
					if (prop.match(this.exclude)) {
						continue;
					}
					li = D.createElement('li');
					li.title = this.styles[prop];
					li.className = this.ITEM_CLASS.replace('{name}', prop);
					li.innerHTML = this.styles[prop];
					li['data-style'] = prop;
					list.appendChild(li);
				}
			}
			this.elt.appendChild(list);
			return this;
		},
		version: 0.1 // float!
	};

	if ( D.addEventListener ) {
		D.addEventListener('DOMContentLoaded', Switcher.load, false);
		W.addEventListener('unload', Switcher.unload, false);
	} else if ( D.attachEvent ) {
		D.attachEvent('onreadystatechange', Switcher.load);
		W.attachEvent('onunload', Switcher.unload);
	}

})(window, window.document);
