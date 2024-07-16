<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $preparation_time_in_seconds = null;

    #[ORM\Column]
    private ?int $people_number = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MealType $mealType = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, RecipeUstensils>
     */
    #[ORM\OneToMany(targetEntity: RecipeUstensils::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $recipeUstensils;

    /**
     * @var Collection<int, Step>
     */
    #[ORM\OneToMany(targetEntity: Step::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $steps;

    /**
     * @var Collection<int, RecipeIngredients>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredients::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $recipeIngredients;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'recipe', orphanRemoval: true, cascade: ['remove'])]
    private Collection $comments;

    /**
     * @var Collection<int, RecipeImage>
     */
    #[ORM\OneToMany(targetEntity: RecipeImage::class, mappedBy: 'recipe')]
    private Collection $recipeImage;

    /**
     * @var Collection<int, UserFavoriteRecipe>
     */
    #[ORM\OneToMany(targetEntity: UserFavoriteRecipe::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $userFavoriteRecipes;

    public function __construct()
    {
        $this->recipeUstensils = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->recipeImage = new ArrayCollection();
        $this->userFavoriteRecipes = new ArrayCollection();
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

    public function getPreparationTimeInSeconds(): ?int
    {
        return $this->preparation_time_in_seconds;
    }

    public function setPreparationTimeInSeconds(int $preparation_time_in_seconds): static
    {
        $this->preparation_time_in_seconds = $preparation_time_in_seconds;

        return $this;
    }

    public function getPeopleNumber(): ?int
    {
        return $this->people_number;
    }

    public function setPeopleNumber(int $people_number): static
    {
        $this->people_number = $people_number;

        return $this;
    }

    public function getMealType(): ?MealType
    {
        return $this->mealType;
    }

    public function setMealType(?MealType $mealType): static
    {
        $this->mealType = $mealType;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
            $recipeUstensil->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeUstensil(RecipeUstensils $recipeUstensil): static
    {
        if ($this->recipeUstensils->removeElement($recipeUstensil)) {
            // set the owning side to null (unless already changed)
            if ($recipeUstensil->getRecipe() === $this) {
                $recipeUstensil->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): static
    {
        if (!$this->steps->contains($step)) {
            $this->steps->add($step);
            $step->setRecipe($this);
        }

        return $this;
    }

    public function removeStep(Step $step): static
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getRecipe() === $this) {
                $step->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredients>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredients $recipeIngredient): static
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredients $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setRecipe($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeImage>
     */
    public function getRecipeImage(): Collection
    {
        return $this->recipeImage;
    }

    public function addRecipeImage(RecipeImage $recipeImage): static
    {
        if (!$this->recipeImage->contains($recipeImage)) {
            $this->recipeImage->add($recipeImage);
            $recipeImage->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeImage(RecipeImage $recipeImage): static
    {
        if ($this->recipeImage->removeElement($recipeImage)) {
            // set the owning side to null (unless already changed)
            if ($recipeImage->getRecipe() === $this) {
                $recipeImage->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserFavoriteRecipe>
     */
    public function getUserFavoriteRecipes(): Collection
    {
        return $this->userFavoriteRecipes;
    }

    public function addUserFavoriteRecipe(UserFavoriteRecipe $userFavoriteRecipe): static
    {
        if (!$this->userFavoriteRecipes->contains($userFavoriteRecipe)) {
            $this->userFavoriteRecipes->add($userFavoriteRecipe);
            $userFavoriteRecipe->setRecipe($this);
        }

        return $this;
    }

    public function removeUserFavoriteRecipe(UserFavoriteRecipe $userFavoriteRecipe): static
    {
        if ($this->userFavoriteRecipes->removeElement($userFavoriteRecipe)) {
            // set the owning side to null (unless already changed)
            if ($userFavoriteRecipe->getRecipe() === $this) {
                $userFavoriteRecipe->setRecipe(null);
            }
        }

        return $this;
    }
}
