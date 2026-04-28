(function ($) {
	'use strict';

	wp.customize('martha_hero_button_url', function (value) {
		value.bind(function (newValue) {
			$('.hero__button').attr('href', newValue);
		});
	});

	wp.customize('martha_hero_background', function (value) {
		value.bind(function (newValue) {
			document.documentElement.style.setProperty('--hero-bg', newValue);
			document.documentElement.style.backgroundColor = newValue;
		});
	});

	wp.customize('martha_hero_button_color', function (value) {
		value.bind(function (newValue) {
			document.documentElement.style.setProperty(
				'--hero-button-color',
				newValue
			);
		});
	});

	/**
	 * Convert "#rrggbb" / "#rgb" into [r, g, b] components in the 0..1 range
	 * used by <feFuncR/G/B tableValues>.
	 */
	function hexToNormalizedRgb(hex) {
		if (typeof hex !== 'string') {
			return [0, 0, 0];
		}
		var clean = hex.replace(/^#/, '');
		if (clean.length === 3) {
			clean = clean
				.split('')
				.map(function (c) {
					return c + c;
				})
				.join('');
		}
		if (!/^[0-9a-f]{6}$/i.test(clean)) {
			return [0, 0, 0];
		}
		return [
			parseInt(clean.substr(0, 2), 16) / 255,
			parseInt(clean.substr(2, 2), 16) / 255,
			parseInt(clean.substr(4, 2), 16) / 255,
		];
	}

	function updateDuotone() {
		var shadow = wp.customize('martha_hero_duotone_shadow')();
		var highlight = wp.customize('martha_hero_duotone_highlight')();
		var s = hexToNormalizedRgb(shadow);
		var h = hexToNormalizedRgb(highlight);

		var filter = document.getElementById('martha-hero-duotone');
		if (!filter) {
			return;
		}

		var transfer = filter.querySelector('feComponentTransfer');
		if (!transfer) {
			return;
		}

		var funcs = transfer.querySelectorAll('feFuncR, feFuncG, feFuncB');
		if (funcs.length < 3) {
			return;
		}

		funcs[0].setAttribute('tableValues', s[0] + ' ' + h[0]);
		funcs[1].setAttribute('tableValues', s[1] + ' ' + h[1]);
		funcs[2].setAttribute('tableValues', s[2] + ' ' + h[2]);
	}

	wp.customize('martha_hero_duotone_shadow', function (value) {
		value.bind(updateDuotone);
	});

	wp.customize('martha_hero_duotone_highlight', function (value) {
		value.bind(updateDuotone);
	});
})(jQuery);
