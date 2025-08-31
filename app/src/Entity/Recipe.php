<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 *
 * PHP version 8.3
 *
 * @author    Aleksandra Niechaj <aleksandra.niechaj@example.com>
 *
 * @copyright 2025 Aleksandra Niechaj
 *
 * @license   For educational purposes (course project).
 */

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Index(name: 'idx_recipes_created_at', columns: ['created_at'])]
#[ORM\Index(name: 'idx_recipes_category_id', columns: ['category_id'])]
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
/**
 * Przepis (tytuł, treść, kategoria, komentarze, znaczniki czasowe).
 */
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Title is required.')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Title is too short.', maxMessage: 'Title is too long.')]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank(message: 'Content is required.')]
    #[Assert\Length(min: 10, minMessage: 'Content is too short.')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Assert\NotNull(message: 'Category is required.')]
    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(
        targetEntity: Comment::class,
        mappedBy: 'recipe',
        orphanRemoval: true,
        fetch: 'EXTRA_LAZY'
    )]
    private Collection $comments;

    /** Konstruktor. */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Pobiera identyfikator przepisu.
     *
     * @return int|null Id przepisu
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Pobiera tytuł przepisu.
     *
     * @return string|null Tytuł przepisu
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Ustawia tytuł przepisu.
     *
     * @param string $title Tytuł przepisu
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Pobiera treść przepisu.
     *
     * @return string|null Treść przepisu
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Ustawia treść przepisu.
     *
     * @param string $content Treść przepisu
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Pobiera datę utworzenia przepisu.
     *
     * @return \DateTimeImmutable|null Data utworzenia
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Ustawia datę utworzenia przepisu.
     *
     * @param \DateTimeImmutable $createdAt Data utworzenia
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Pobiera datę ostatniej modyfikacji.
     *
     * @return \DateTimeImmutable|null Data modyfikacji
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Ustawia datę ostatniej modyfikacji.
     *
     * @param \DateTimeImmutable $updatedAt Data modyfikacji
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Pobiera kategorię przepisu.
     *
     * @return Category|null Kategoria
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Ustawia kategorię przepisu.
     *
     * @param Category|null $category Kategoria
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Pobiera komentarze przypisane do przepisu.
     *
     * @return Collection<int, Comment> Lista komentarzy
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Dodaje komentarz do przepisu.
     *
     * @param Comment $comment Komentarz
     */
    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setRecipe($this);
        }

        return $this;
    }

    /**
     * Usuwa komentarz z przepisu.
     *
     * @param Comment $comment Komentarz
     */
    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment) && $comment->getRecipe() === $this) {
            $comment->setRecipe(null);
        }

        return $this;
    }
}
