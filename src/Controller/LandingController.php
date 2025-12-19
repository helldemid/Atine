<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductsRepository;

final class LandingController extends AbstractController
{
	#[Route(
		path: '/{_locale}',
		name: 'landing',
		requirements: ['_locale' => 'ua|ru|en'],
		defaults: ['_locale' => 'ua'],
		methods: ['GET']
	)]
	public function index(ProductsRepository $productsRepository): Response
	{
		return $this->render('landing/home.html.twig', [
			'products' => $productsRepository->findForSlider()
		]);
	}
}
