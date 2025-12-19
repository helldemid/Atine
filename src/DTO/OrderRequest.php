<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class OrderRequest
{
	#[Assert\Length(max: 100)]
	public ?string $name = null;

	#[Assert\NotBlank]
	#[Assert\Email]
	public string $email;

	#[Assert\NotBlank]
	#[Assert\Length(min: 8, max: 20)]
	#[Assert\Regex(
		pattern: '/^\+[1-9]\d{6,19}$/',
		message: 'Invalid international phone number'
	)]
	public string $phone;


	#[Assert\NotBlank]
	#[Assert\Positive]
	public int $productId;
}
