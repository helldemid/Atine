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
});
