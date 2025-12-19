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


	const burgerBtn = document.getElementById('burgerBtn');
	const mobileMenu = document.getElementById('mobileMenu');
	const body = document.body;

	const menuLinks = mobileMenu.querySelectorAll('a');

	function toggleMenu() {
		burgerBtn.classList.toggle('is-active');
		mobileMenu.classList.toggle('is-open');
		body.classList.toggle('menu-open');
	}

	function closeMenu() {
		burgerBtn.classList.remove('is-active');
		mobileMenu.classList.remove('is-open');
		body.classList.remove('menu-open');
	}

	burgerBtn.addEventListener('click', toggleMenu);

	menuLinks.forEach(link => {
		link.addEventListener('click', closeMenu);
	});


	// International Telephone Input
	document.querySelectorAll('input[type="tel"]').forEach(input => {
		const iti = window.intlTelInput(input, {
			initialCountry: 'ua',
			separateDialCode: true,
			utilsScript:
				'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js'
		});

		input._iti = iti;
	});


	document.addEventListener('submit', async (e) => {
		e.preventDefault();
		const form = e.target;

		// убрать предыдущие подсветки ошибок
		form.querySelectorAll('.error').forEach(i => i.classList.remove('error'));

		const phoneInput = form.querySelector('input[type="tel"]');
		const emailInput = form.querySelector('input[type="email"]');
		const productSelect = form.querySelector('select[name="product_id"]') || null;
		const nameInput = form.querySelector('input[name="name"]') || null;
		const iti = phoneInput?._iti;

		if (!iti || !iti.isValidNumber()) {
			if (phoneInput) phoneInput.classList.add('error');
			AlertService.error('Некоректний номер телефону');
			return;
		}

		const formData = new FormData(form);
		formData.set('phone', iti.getNumber());

		// простая фронтовая валидация
		if (!formData.get('email')) {
			if (emailInput) emailInput.classList.add('error');
			AlertService.error('Заповніть всі поля');
			return;
		}

		if (parseInt(formData.get('product_id')) < 0) {
			if (productSelect) productSelect.classList.add('error');
			AlertService.error('Оберіть товар');
			return;
		}

		if (nameInput && !formData.get('name')) {
			nameInput.classList.add('error');
			AlertService.error('Заповніть всі поля');
			return;
		}

		try {
			ApiService.post(form.action, Object.fromEntries(formData)).then(async (response) => {
				if (response.status !== 'ok') {
					AlertService.error(response.message || 'Помилка');
					return;
				}

				AlertService.success(response.message || 'Дякуємо! Ваше замовлення прийнято.', response.header || 'Замовлення прийнято');
				form.reset();
			});
		} catch (err) {
			AlertService.error('Виникла помилка, спробуйте пізніше.');
		}
	});



});
