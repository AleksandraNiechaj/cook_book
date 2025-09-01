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

/**
 * Przepis (tytuł, treść, kategoria, komentarze, znaczniki czasowe, tagi).
 */
#[ORM\Index(name: 'idx_recipes_created_at', columns: ['created_at'])]
#[ORM\Index(name: 'idx_recipes_category_id', columns: ['category_id'])]
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
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

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: 'recipe_tag')]
    private Collection $tags;

    /**
     * Konstruktor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return int|null Id przepisu
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null Tytuł przepisu
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title Tytuł przepisu
     *
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null Treść przepisu
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content Treść przepisu
     *
     * @return static
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null Data utworzenia
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt Data utworzenia
     *
     * @return static
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null Data modyfikacji
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable $updatedAt Data modyfikacji
     *
     * @return static
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Category|null Kategoria
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category Kategoria
     *
     * @return static
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Comment> Lista komentarzy
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment Komentarz
     *
     * @return static
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
     * @param Comment $comment Komentarz
     *
     * @return static
     */
    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment) && $comment->getRecipe() === $this) {
            $comment->setRecipe(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag> Kolekcja tagów
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag Tag
     *
     * @return static
     */
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * @param Tag $tag Tag
     *
     * @return static
     */
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
