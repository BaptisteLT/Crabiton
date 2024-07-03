<?php

namespace App\Entity;

use App\Repository\RecipeUstensilsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeUstensilsRepository::class)]
class RecipeUstensils
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'recipeUstensils')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ustensil $ustensil = null;

    #[ORM\ManyToOne(inversedBy: 'recipeUstensils')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUstensil(): ?Ustensil
    {
        return $this->ustensil;
    }

    public function setUstensil(?Ustensil $ustensil): static
    {
        $this->ustensil = $ustensil;

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
