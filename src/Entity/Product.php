<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product')]
class Product
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: 'integer')]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	private string $name;

	#[ORM\Column(length: 255)]
	private string $subtitle;

	#[ORM\Column(type: 'text')]
	private string $description;

	#[ORM\Column(type: 'integer')]
	private int $price;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $image = null;

	#[ORM\Column(type: 'integer')]
	private int $position = 0;

	#[ORM\Column(type: 'boolean')]
	private bool $isActive = true;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getSubtitle(): string
	{
		return $this->subtitle;
	}

	public function setSubtitle(string $subtitle): self
	{
		$this->subtitle = $subtitle;

		return $this;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setDescription(string $description): self
	{
		$this->description = $description;

		return $this;
	}

	public function getPrice(): int
	{
		return $this->price;
	}

	public function setPrice(int $price): self
	{
		$this->price = $price;

		return $this;
	}

	public function getImage(): ?string
	{
		return $this->image;
	}

	public function setImage(?string $image): self
	{
		$this->image = $image;

		return $this;
	}

	public function getPosition(): int
	{
		return $this->position;
	}

	public function setPosition(int $position): self
	{
		$this->position = $position;

		return $this;
	}

	public function isActive(): bool
	{
		return $this->isActive;
	}

	public function setIsActive(bool $isActive): self
	{
		$this->isActive = $isActive;

		return $this;
	}

}
