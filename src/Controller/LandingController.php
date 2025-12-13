<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LandingController extends AbstractController
{
	#[Route(
		path: '/{_locale}',
		name: 'landing',
		requirements: ['_locale' => 'ua|en'],
		defaults: ['_locale' => 'ua'],
		methods: ['GET']
	)]
	public function index(): Response
	{
		return $this->render('landing/home.html.twig');
	}
}
