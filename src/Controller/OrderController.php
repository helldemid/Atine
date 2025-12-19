<?php
namespace App\Controller;

use App\DTO\OrderRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Service\TelegramNotifier;
use App\Repository\ProductsRepository;

class OrderController extends AbstractController
{
	#[Route(
		path: '/{_locale}/order',
		name: 'order_create',
		requirements: ['_locale' => 'ua|ru|en'],
		defaults: ['_locale' => 'ua'],
		methods: ['POST']
	)]
	public function create(
		Request $request,
		ValidatorInterface $validator,
		CsrfTokenManagerInterface $csrf,
		TelegramNotifier $telegramNotifier,
		ProductsRepository $productsRepository
	): JsonResponse {
		$lang = $request->getLocale();

		$successHeader = match ($lang) {
			'ru' => 'Успех!',
			'en' => 'Success!',
			default => 'Успіх!',
		};

		$erHeader = match ($lang) {
			'ru' => 'Ошибка!',
			'en' => 'Error!',
			default => 'Помилка!',
		};


		if (!$request->isXmlHttpRequest()) {
			return new JsonResponse([
				'status' => 'error',
				'header' => $erHeader,
				'message' => match ($lang) {
					'ru' => 'Недопустимый тип запроса.',
					'en' => 'Invalid request type.',
					default => 'Недопустимий тип запиту.',
				}
			], 400);
		}

		$data = json_decode($request->getContent(), true);

		if (!empty($data['company'])) {
			return new JsonResponse([
				'status' => 'error',
				'header' => $erHeader,
				'message' => match ($lang) {
					'ru' => 'Ошибка валидации.',
					'en' => 'Validation error.',
					default => 'Помилка валідації.',
				}
			], 400);
		}

		// CSRF
		$token = new CsrfToken('order_form', $data['_csrf_token']);
		if (!$csrf->isTokenValid($token)) {
			return new JsonResponse([
				'status' => 'error',
				'header' => $erHeader,
				'message' => match ($lang) {
					'ru' => 'Что-то пошло не так. Попробуйте еще раз.',
					'en' => 'Something went wrong. Please try again.',
					default => 'Щось пішло не так. Спробуйте ще раз.',
				}
			], 400);
		}

		// Map request → DTO
		$dto = new OrderRequest();
		$dto->name = $data['name'] ?? null;
		$dto->email = (string) $data['email'];
		$dto->phone = (string) $data['phone'];
		$dto->productId = (int) $data['product_id'];
		// Validate
		$errors = $validator->validate($dto);
		if (count($errors) > 0) {
			return new JsonResponse([
				'status' => 'error',
				'header' => $erHeader,
				'message' => (string) $errors[0]->getMessage()
			], 422);
		}

		try {
			$item = $productsRepository->find($dto->productId);
			$itemName = $item ? $item->getName() : 'N/A';
			$price = $item ? $item->getPrice() : 'N/A';

			$message = match ($lang) {
				'ru' => sprintf(
					"🛒 <b>Новый заказ</b>\n\n%s📦 Товар: %s\n💰 Цена: %s\n📧 Email: %s\n📞 Телефон: %s\n🌍 Язык: RU",
					$dto->name ? "👤 Имя: {$dto->name}\n" : '',
					$itemName,
					$price,
					$dto->email,
					$dto->phone
				),
				'en' => sprintf(
					"🛒 <b>New order</b>\n\n%s📦 Product: %s\n💰 Price: %s\n📧 Email: %s\n📞 Phone: %s\n🌍 Language: EN",
					$dto->name ? "👤 Name: {$dto->name}\n" : '',
					$itemName,
					$price,
					$dto->email,
					$dto->phone
				),
				default => sprintf(
					"🛒 <b>Нове замовлення</b>\n\n%s📦 Товар: %s\n💰 Ціна: %s\n📧 Email: %s\n📞 Телефон: %s\n🌍 Мова: UA",
					$dto->name ? "👤 Імʼя: {$dto->name}\n" : '',
					$itemName,
					$price,
					$dto->email,
					$dto->phone
				),
			};

			$telegramNotifier->send($message);
		} catch (\Throwable $e) {
			return new JsonResponse([
				'status' => 'error',
				'header' => $erHeader,
				'additional_info' => $e->getMessage(),
				'message' => match ($lang) {
					'ru' => 'Не удалось отправить заказ. Пожалуйста, попробуйте еще раз позже.',
					'en' => 'Failed to send order. Please try again later.',
					default => 'Не вдалося надіслати замовлення. Будь ласка, спробуйте ще раз пізніше.',
				}
			], 500);
		}


		return new JsonResponse([
			'status' => 'ok',
			'header' => $successHeader,
			'message' => match ($lang) {
				'ru' => 'Спасибо за ваш заказ! Мы свяжемся с вами в ближайшее время.',
				'en' => 'Thank you for your order! We will contact you shortly.',
				default => 'Дякуємо за ваше замовлення! Ми зв\'яжемося з вами найближчим часом.',
			}
		], 200);
	}
}
