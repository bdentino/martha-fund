/**
 * Timeline entry animations.
 *
 * Watches each Cool Timeline child slide and reveals it with a fade + bounce
 * toward the vertical center axis when it scrolls into view. Items on the
 * right side of the timeline slide in from further right; items on the left
 * slide in from further left, so both meet at the center spine.
 *
 * Designed to be progressive: if IntersectionObserver is unavailable or the
 * user prefers reduced motion, the items are revealed immediately.
 */
(function () {
	'use strict';

	var SELECTOR = '.wp-block-cp-timeline-content-timeline-child';
	var READY_CLASS = 'martha-timeline-item';
	var VISIBLE_CLASS = 'is-visible';
	var SIDE_LEFT_CLASS = 'is-from-left';
	var SIDE_RIGHT_CLASS = 'is-from-right';

	function ready(fn) {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
	}

	function init() {
		var items = document.querySelectorAll(SELECTOR);
		if (!items.length) {
			return;
		}

		var prefersReducedMotion =
			window.matchMedia &&
			window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		// Tag each item with its entry side so CSS can pick the right
		// direction. The Cool Timeline plugin already marks each row with
		// `.position-left` / `.position-right`; mirror that on the slide
		// wrapper itself for easier targeting.
		Array.prototype.forEach.call(items, function (item, index) {
			var row = item.querySelector('.timeline-block-timeline');
			var fromRight = row && row.classList.contains('position-right');
			item.classList.add(READY_CLASS);
			item.classList.add(fromRight ? SIDE_RIGHT_CLASS : SIDE_LEFT_CLASS);

			// Stagger items slightly so a cluster entering together
			// cascades instead of popping in unison.
			item.style.setProperty('--martha-timeline-delay', (index % 4) * 80 + 'ms');
		});

		if (prefersReducedMotion || typeof IntersectionObserver === 'undefined') {
			Array.prototype.forEach.call(items, function (item) {
				item.classList.add(VISIBLE_CLASS);
			});
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add(VISIBLE_CLASS);
						observer.unobserve(entry.target);
					}
				});
			},
			{
				root: null,
				// Trigger a touch before the element is fully in view so the
				// animation feels responsive to the scroll.
				rootMargin: '0px 0px -10% 0px',
				threshold: 0.15,
			}
		);

		Array.prototype.forEach.call(items, function (item) {
			observer.observe(item);
		});
	}

	ready(init);
})();
