<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: Quantity::class)]
    private Collection $quantities;

    public function __construct()
    {
        $this->quantities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Quantity>
     */
    public function getQuantities(): Collection
    {
        return $this->quantities;
    }

    public function addQuantities(Quantity $quantities): static
    {
        if (!$this->quantities->contains($quantities)) {
            $this->quantities->add($quantities);
            $quantities->setIngredient($this);
        }

        return $this;
    }

    public function removeQuantities(Quantity $quantities): static
    {
        if ($this->quantities->removeElement($quantities)) {
            // set the owning side to null (unless already changed)
            if ($quantities->getIngredient() === $this) {
                $quantities->setIngredient(null);
            }
        }

        return $this;
    }
}
