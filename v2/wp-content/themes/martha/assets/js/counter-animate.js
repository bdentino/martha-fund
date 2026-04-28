/**
 * Counter count-up animation.
 *
 * Finds every `.counter` element, parses the integer out of its text content
 * (preserving any surrounding currency symbols or qualifier text), and counts
 * up from 0 to that integer over ~1s when the element scrolls into view.
 *
 * Falls back to showing the final value immediately when IntersectionObserver
 * is unavailable or the user prefers reduced motion.
 */
(function () {
	'use strict';

	var SELECTOR = '.counter';
	var DURATION_MS = 1000;

	function ready(fn) {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
	}

	/**
	 * Find the first run of digits (optionally with `,` thousands separators)
	 * inside `text`. Returns null if no integer is present.
	 *
	 * Returns: { prefix, suffix, value, hasCommas }
	 */
	function parseCounter(text) {
		var match = /[-+]?\d[\d,]*/.exec(text);
		if (!match) {
			return null;
		}
		var raw = match[0];
		var hasCommas = raw.indexOf(',') !== -1;
		var value = parseInt(raw.replace(/,/g, ''), 10);
		if (isNaN(value)) {
			return null;
		}
		return {
			prefix: text.slice(0, match.index),
			suffix: text.slice(match.index + raw.length),
			value: value,
			hasCommas: hasCommas,
		};
	}

	function formatNumber(n, withCommas) {
		if (!withCommas) {
			return String(n);
		}
		try {
			return n.toLocaleString('en-US');
		} catch (_e) {
			return String(n);
		}
	}

	// easeOutQuad: starts fast, settles softly at the target.
	function easeOut(t) {
		return 1 - (1 - t) * (1 - t);
	}

	function animate(el, parsed) {
		var start = null;
		var target = parsed.value;

		function step(timestamp) {
			if (start === null) {
				start = timestamp;
			}
			var elapsed = timestamp - start;
			var progress = Math.min(1, elapsed / DURATION_MS);
			var current = Math.round(target * easeOut(progress));
			el.textContent = parsed.prefix + formatNumber(current, parsed.hasCommas) + parsed.suffix;
			if (progress < 1) {
				window.requestAnimationFrame(step);
			}
		}

		window.requestAnimationFrame(step);
	}

	function init() {
		var counters = document.querySelectorAll(SELECTOR);
		if (!counters.length) {
			return;
		}

		var prefersReducedMotion =
			window.matchMedia &&
			window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		// Pre-parse and reset each counter to "0" so the user doesn't see the
		// final value flash before the animation starts.
		//
		// Before resetting, measure the rendered width of the final text and
		// lock the element to (at least) that width so its size doesn't jump
		// as digits are added during the count-up. We also force inline-block
		// when the element is otherwise inline, since `min-width` is ignored
		// on inline boxes, and prefer tabular numerals so individual digits
		// have a constant advance width within the final string too.
		var parsedMap = [];
		Array.prototype.forEach.call(counters, function (el) {
			var parsed = parseCounter(el.textContent);
			if (!parsed) {
				parsedMap.push(null);
				return;
			}
			parsedMap.push(parsed);

			if (!prefersReducedMotion) {
				var rect = el.getBoundingClientRect();
				if (rect && rect.width) {
					var computedDisplay = window.getComputedStyle(el).display;
					if (computedDisplay === 'inline') {
						el.style.display = 'inline-block';
					}
					el.style.minWidth = rect.width + 'px';
				}
				el.style.fontVariantNumeric = 'tabular-nums';

				el.textContent = parsed.prefix + formatNumber(0, parsed.hasCommas) + parsed.suffix;
			}
		});

		if (prefersReducedMotion || typeof IntersectionObserver === 'undefined') {
			Array.prototype.forEach.call(counters, function (el, i) {
				var parsed = parsedMap[i];
				if (parsed) {
					el.textContent = parsed.prefix + formatNumber(parsed.value, parsed.hasCommas) + parsed.suffix;
				}
			});
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}
					var el = entry.target;
					var parsed = parsedMap[Array.prototype.indexOf.call(counters, el)];
					if (parsed) {
						animate(el, parsed);
					}
					observer.unobserve(el);
				});
			},
			{
				root: null,
				rootMargin: '0px 0px -10% 0px',
				threshold: 0.25,
			}
		);

		Array.prototype.forEach.call(counters, function (el, i) {
			if (parsedMap[i]) {
				observer.observe(el);
			}
		});
	}

	ready(init);
})();
