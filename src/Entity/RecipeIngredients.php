<?php

namespace App\Entity;

use App\Repository\RecipeIngredientsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeIngredientsRepository::class)]
class RecipeIngredients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $quantityNumber = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantityMiligram = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $ingredient = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantityNumber(): ?float
    {
        return $this->quantityNumber;
    }

    public function setQuantityNumber(?float $quantityNumber): static
    {
        $this->quantityNumber = $quantityNumber;

        return $this;
    }

    public function getQuantityMiligram(): ?int
    {
        return $this->quantityMiligram;
    }

    public function setQuantityMiligram(?int $quantityMiligram): static
    {
        $this->quantityMiligram = $quantityMiligram;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): static
    {
        $this->ingredient = $ingredient;

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
