<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;

#[ApiResource(
    normalizationContext: ['groups' => ['recipe:read']],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            denormalizationContext: ['groups' => ['recipe:write']],
        ),
        new Patch(),
        new Delete(),
    ],
)]
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['recipe:read', 'recipe:write'])]
    #[ApiFilter(SearchFilter::class, strategy: SearchFilter::STRATEGY_IPARTIAL)]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[Groups(['recipe:read', 'recipe:write'])]
    #[Assert\NotBlank]
    #[ORM\Column(length: 1023)]
    private ?string $instructions = null;

    #[Groups(['recipe:read', 'recipe:write'])]
    #[ORM\Column]
    private ?int $preparationTime = null;

    #[Groups(['recipe:read', 'recipe:write'])]
    #[ORM\Column(length: 63)]
    #[Assert\Choice(choices: ['easy', 'medium', 'hard'])]
    private ?string $difficulty = null;

    #[Groups(['recipe:read', 'recipe:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $picture = null;

    #[Groups(['recipe:write'])]
    #[ORM\Column]
    private ?bool $public = true;

    #[Groups(['recipe:read', 'recipe:write'])]
    #[ORM\Column]
    #[CustomAssert\AtLeastThreeIngredients]
    private array $ingredients = [];

    #[Groups(['recipe:read', 'recipe:write'])]
    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Category $category = null;

    #[Groups(['recipe:read', 'recipe:write'])]
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Quantity::class)]
    private Collection $quantities;

    public function __construct()
    {
        $this->quantities = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['recipe:read'])]
    public function getName(): ?string
    {
        return $this->name;
    }

    #[Groups(['recipe:write'])]
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    #[Groups(['recipe:read'])]
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[Groups(['recipe:read'])]
    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    #[Groups(['recipe:write'])]
    public function setInstructions(string $instructions): static
    {
        $this->instructions = $instructions;

        return $this;
    }

    #[Groups(['recipe:read'])]
    public function getPreparationTime(): ?int
    {
        return $this->preparationTime;
    }

    #[Groups(['recipe:write'])]
    public function setPreparationTime(int $preparationTime): static
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    #[Groups(['recipe:read'])]
    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    #[Groups(['recipe:write'])]
    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    #[Groups(['recipe:read'])]
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    #[Groups(['recipe:write'])]
    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    #[Groups(['recipe:read'])]
    public function isPublic(): ?bool
    {
        return $this->public;
    }

    #[Groups(['recipe:write'])]
    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    #[Groups(['recipe:read'])]
    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    #[Groups(['recipe:write'])]
    public function setIngredients(object $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    #[Groups(['recipe:read'])]
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    #[Groups(['recipe:write'])]
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Quantity>
     */
    #[Groups(['recipe:read'])]
    public function getQuantities(): Collection
    {
        return $this->quantities;
    }

    public function addQuantity(Quantity $quantity): static
    {
        if (!$this->quantities->contains($quantity)) {
            $this->quantities->add($quantity);
            $quantity->setRecipe($this);
        }

        return $this;
    }

    public function removeQuantity(Quantity $quantity): static
    {
        if ($this->quantities->removeElement($quantity)) {
            // set the owning side to null (unless already changed)
            if ($quantity->getRecipe() === $this) {
                $quantity->setRecipe(null);
            }
        }

        return $this;
    }
}
