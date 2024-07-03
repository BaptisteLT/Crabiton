<?php

namespace App\Entity;

use App\Repository\UstensilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UstensilRepository::class)]
class Ustensil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, RecipeUstensils>
     */
    #[ORM\OneToMany(targetEntity: RecipeUstensils::class, mappedBy: 'ustensil', orphanRemoval: true)]
    private Collection $recipeUstensils;

    public function __construct()
    {
        $this->recipeUstensils = new ArrayCollection();
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
     * @return Collection<int, RecipeUstensils>
     */
    public function getRecipeUstensils(): Collection
    {
        return $this->recipeUstensils;
    }

    public function addRecipeUstensil(RecipeUstensils $recipeUstensil): static
    {
        if (!$this->recipeUstensils->contains($recipeUstensil)) {
            $this->recipeUstensils->add($recipeUstensil);
            $recipeUstensil->setUstensil($this);
        }

        return $this;
    }

    public function removeRecipeUstensil(RecipeUstensils $recipeUstensil): static
    {
        if ($this->recipeUstensils->removeElement($recipeUstensil)) {
            // set the owning side to null (unless already changed)
            if ($recipeUstensil->getUstensil() === $this) {
                $recipeUstensil->setUstensil(null);
            }
        }

        return $this;
    }
}
