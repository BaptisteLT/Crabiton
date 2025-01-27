<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
##[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $recipes;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $comments;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\ManyToMany(targetEntity: Comment::class, inversedBy: 'users')]
    private Collection $likes;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $OAuth2ProviderName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $OAuth2ProviderId = null;

    /**
     * @var Collection<int, UserFavoriteRecipe>
     */
    #[ORM\OneToMany(targetEntity: UserFavoriteRecipe::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userFavoriteRecipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->userFavoriteRecipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setUser($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getUser() === $this) {
                $recipe->setUser(null);
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
            $comment->setOwner($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getOwner() === $this) {
                $comment->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Comment $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
        }

        return $this;
    }

    public function removeLike(Comment $like): static
    {
        $this->likes->removeElement($like);

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getOAuth2ProviderName(): ?string
    {
        return $this->OAuth2ProviderName;
    }

    public function setOAuth2ProviderName(?string $OAuth2ProviderName): static
    {
        $this->OAuth2ProviderName = $OAuth2ProviderName;

        return $this;
    }

    public function getOAuth2ProviderId(): ?string
    {
        return $this->OAuth2ProviderId;
    }

    public function setOAuth2ProviderId(?string $OAuth2ProviderId): static
    {
        $this->OAuth2ProviderId = $OAuth2ProviderId;

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
            $userFavoriteRecipe->setUser($this);
        }

        return $this;
    }

    public function removeUserFavoriteRecipe(UserFavoriteRecipe $userFavoriteRecipe): static
    {
        if ($this->userFavoriteRecipes->removeElement($userFavoriteRecipe)) {
            // set the owning side to null (unless already changed)
            if ($userFavoriteRecipe->getUser() === $this) {
                $userFavoriteRecipe->setUser(null);
            }
        }

        return $this;
    }
}
