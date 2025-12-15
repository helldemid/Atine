<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$products = [
			[
				'name' => 'ATINE® капсули',
				'subtitle' => '666 мг, 75 капсул',
				'description' =>
					'Стандартизована формула ATINE в зручному капсульному форматі.
					Підходить тим, кому важливо чітке дозування та простий щоденний прийом удома, у стаціонарі чи в дорозі.',
				'price' => 2800,
			],
			[
				'name' => 'ATINE® таблетки',
				'subtitle' => '450 мг, 50 таблеток',
				'description' =>
					'Таблетки дають можливість гнучко розподіляти дозу протягом дня. Формат для пацієнтів, яким зручно прив’язувати прийом до розкладу іншої терапії за погодженням із лікарем.',
				'price' => 1100,
			],
			[
				'name' => 'ATINE® гранульований чай',
				'subtitle' => '5 г × 36 саше',
				'description' =>
					'Гранульований чай розчиняється у теплій воді та п’ється невеликими порціями. Підходить, коли потрібна м’яка підтримка, гідратація й форма напою, а не додаткових таблеток.',
				'price' => 1100,
			],
			[
				'name' => 'ATINE® густий екстракт',
				'subtitle' => '100 мл',
				'description' =>
					'Найбільш концентрована форма ATINE. Дозу можна відміряти краплями або чайною ложкою, додаючи до води чи іншого напою. Зручний для точнішого налаштування добової кількості разом із лікарем.',
				'price' => 4400,
			],
		];


		foreach ($products as $i => $data) {
			$product = new Product();
			$product->setName($data['name']);
			$product->setSubtitle($data['subtitle']);
			$product->setDescription($data['description']);
			$product->setPrice($data['price']);
			$product->setPosition($i + 1);

			$manager->persist($product);
		}
		$manager->flush();
	}
}
