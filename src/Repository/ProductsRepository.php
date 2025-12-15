<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductsRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Product::class);
	}

	/**
	 * @return Product[]
	 */
	public function findForSlider(): array
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.isActive = :active')
			->setParameter('active', true)
			->orderBy('p.position', 'ASC')
			->getQuery()
			->getResult();
	}
}