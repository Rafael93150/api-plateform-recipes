<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuantityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [],
)]
#[ORM\Entity(repositoryClass: QuantityRepository::class)]
class Quantity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quantities')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['recipe:read'])]
    private ?Ingredient $ingredient = null;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    #[Assert\NotNull(message: 'Please enter an amount')]
    private ?int $amount = null;

    #[ORM\Column(length: 63, nullable: true)]
    #[Groups(['recipe:read'])]
    #[Assert\Choice(choices: ['g', 'kg', 'tbsp', 'tsp', 'cup', 'ml', 'l', 'clove', 'can', 'bunch', 'pinch', 'slice', 'whole'])]
    private ?string $unit = null;

    #[ORM\ManyToOne(inversedBy: 'quantities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['recipe:read'])]
    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): static
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    #[Groups(['recipe:read'])]
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    #[Groups(['recipe:read'])]
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
