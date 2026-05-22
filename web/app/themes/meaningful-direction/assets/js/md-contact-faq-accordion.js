/**
 * Contact FAQ: keep only one <details> open at a time (accordions).
 * Augments browsers without support for named <details> groups.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var root = document.querySelector('.md-site-contact-faq .faq-list');
		if (!root) {
			return;
		}
		var items = root.querySelectorAll('details.faq-item');
		if (!items.length) {
			return;
		}
		items.forEach(function (el) {
			el.addEventListener('toggle', function () {
				if (!el.open) {
					return;
				}
				items.forEach(function (other) {
					if (other !== el) {
						other.open = false;
					}
				});
			});
		});
	});
})();
