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

	const swiperEl = document.querySelector('.products-swiper');
	const productInfos = document.querySelectorAll('.product-info');

	const productsSwiper = new Swiper(swiperEl, {
		slidesPerView: 1,
		loop: false,
		speed: 600,
		navigation: {
			nextEl: '.products__nav .next',
			prevEl: '.products__nav .prev',
		},
	});

	const setActiveProduct = (index) => {
		productInfos.forEach(el => el.classList.remove('is-active'));
		if (productInfos[index]) {
			productInfos[index].classList.add('is-active');
		}
	};

	// initial
	setActiveProduct(productsSwiper.activeIndex || 0);

	productsSwiper.on('slideChange', () => {
		setActiveProduct(productsSwiper.activeIndex);
	});

	const mediaSwiper = document.querySelector('.media-swiper');
	if (!mediaSwiper || typeof Swiper === 'undefined') return;

	const mediaSwiperInstance = new Swiper(mediaSwiper, {
		slidesPerView: 1,
		speed: 600,
		navigation: {
			nextEl: '.media__nav .next',
			prevEl: '.media__nav .prev',
		},
		pagination: {
			el: '.media__pagination',
			clickable: true,
		},
	});

	document.querySelectorAll('.media-video').forEach(video => {
		video.addEventListener('click', () => {
			const id = video.dataset.videoId;

			video.innerHTML = `
				<iframe
					src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0"
					frameborder="0"
					allow="autoplay; encrypted-media"
					allowfullscreen
				></iframe>
			`;
		});
	});

	document.querySelectorAll('.faq-item__header').forEach(btn => {
		btn.addEventListener('click', () => {
			const item = btn.closest('.faq-item');

			document.querySelectorAll('.faq-item').forEach(i => {
				if (i !== item) i.classList.remove('is-open');
			});

			item.classList.toggle('is-open');
		});
	});

});
