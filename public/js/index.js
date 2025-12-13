document.addEventListener('DOMContentLoaded', function () {
	const el = document.querySelector('.hero-swiper');
	if (!el || typeof Swiper === 'undefined') return;

	new Swiper(el, {
		slidesPerView: 1,
		loop: true,
		speed: 600,

		autoplay: {
			delay: 6000,
			disableOnInteraction: false,
		},

		pagination: {
			el: el.querySelector('.swiper-pagination'),
			clickable: true,
		},
	});
});
