(function () {
	'use strict';

	var header = document.querySelector('[data-site-header]');
	if (!header) {
		return;
	}

	var config = window.marthaHeader || {};
	// Distance in pixels over which the background fades from 0 → 1 opacity.
	var fadeDistance = Math.max(1, parseInt(config.scrollThreshold, 10) || 120);

	var ticking = false;

	function update() {
		var y = window.scrollY || window.pageYOffset || 0;
		var progress = Math.min(1, Math.max(0, y / fadeDistance));

		header.style.setProperty('--header-bg-opacity', progress.toFixed(3));
		header.classList.toggle('is-scrolled', progress > 0.02);

		ticking = false;
	}

	function onScroll() {
		if (!ticking) {
			window.requestAnimationFrame(update);
			ticking = true;
		}
	}

	var toggle = header.querySelector('[data-nav-toggle]');
	var menu = header.querySelector('.site-header__menu');
	if (toggle && menu) {
		toggle.addEventListener('click', function () {
			var open = header.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}

	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', onScroll);
	update();
})();
