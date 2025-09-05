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

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Title is too short.', maxMessage: 'Title is too long.')]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, minMessage: 'Content is too short.')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne]
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
     * Konstruktor – inicjalizuje kolekcje komentarzy i tagów.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * Zwraca identyfikator przepisu.
     *
     * @return int|null id przepisu
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Zwraca tytuł przepisu.
     *
     * @return string|null tytuł
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Ustawia tytuł przepisu.
     *
     * @param string $title tytuł przepisu
     *
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Zwraca treść przepisu.
     *
     * @return string|null treść przepisu
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Ustawia treść przepisu.
     *
     * @param string $content treść przepisu
     *
     * @return static
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Zwraca datę utworzenia przepisu.
     *
     * @return \DateTimeImmutable|null data utworzenia
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Ustawia datę utworzenia przepisu.
     *
     * @param \DateTimeImmutable $createdAt data utworzenia
     *
     * @return static
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Zwraca datę ostatniej aktualizacji przepisu.
     *
     * @return \DateTimeImmutable|null data aktualizacji
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Ustawia datę ostatniej aktualizacji przepisu.
     *
     * @param \DateTimeImmutable $updatedAt data aktualizacji
     *
     * @return static
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Zwraca kategorię przypisaną do przepisu.
     *
     * @return Category|null kategoria
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Ustawia kategorię przepisu.
     *
     * @param Category|null $category kategoria
     *
     * @return static
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Zwraca kolekcję komentarzy przypisanych do przepisu.
     *
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Dodaje komentarz do przepisu.
     *
     * @param Comment $comment komentarz
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
     * Usuwa komentarz z przepisu.
     *
     * @param Comment $comment komentarz
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
     * Zwraca kolekcję tagów przypisanych do przepisu.
     *
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Dodaje tag do przepisu.
     *
     * @param Tag $tag tag
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
     * Usuwa tag z przepisu.
     *
     * @param Tag $tag tag
     *
     * @return static
     */
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
