<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['recipe:read']]
        ),
    ],
)]
#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read'])]
    #[Assert\NotBlank]
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

    #[Groups(['recipe:read'])]
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
