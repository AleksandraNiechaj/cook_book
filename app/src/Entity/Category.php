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

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'categories')]
#[UniqueEntity(fields: ['slug'], message: 'Slug must be unique.')]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
/**
 * Kategoria przepisu (nazwa, slug, znaczniki czasowe).
 */
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Category name is required.')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'Name is too short.', maxMessage: 'Name is too long.')]
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[Assert\NotBlank(message: 'Slug is required.')]
    #[Assert\Length(max: 100, maxMessage: 'Slug is too long.')]
    #[Assert\Regex(
        pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        message: 'Slug may contain lowercase letters, numbers and hyphens.'
    )]
    #[ORM\Column(length: 100, unique: true)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Kolekcja przepisów powiązanych z kategorią.
     *
     * @var Collection<int, Recipe>
     */
    #[ORM\OneToMany(
        targetEntity: Recipe::class,
        mappedBy: 'category',
        fetch: 'EXTRA_LAZY'
    )]
    private Collection $recipes;

    /**
     * Konstruktor inicjalizujący kolekcję przepisów.
     */
    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    /**
     * Pobiera identyfikator kategorii.
     *
     * @return int|null Id kategorii
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Pobiera nazwę kategorii.
     *
     * @return string|null Nazwa kategorii
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Ustawia nazwę kategorii.
     *
     * @param string $name Nazwa kategorii
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Pobiera slug kategorii.
     *
     * @return string|null Slug kategorii
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Ustawia slug kategorii.
     *
     * @param string $slug Slug
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Pobiera datę utworzenia kategorii.
     *
     * @return \DateTimeImmutable|null Data utworzenia
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Ustawia datę utworzenia kategorii.
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
     * Pobiera wszystkie przepisy powiązane z kategorią.
     *
     * @return Collection<int, Recipe> Kolekcja przepisów
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    /**
     * Dodaje przepis do kategorii.
     *
     * @param Recipe $recipe Przepis
     */
    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setCategory($this);
        }

        return $this;
    }

    /**
     * Usuwa przepis z kategorii.
     *
     * @param Recipe $recipe Przepis
     */
    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe) && $recipe->getCategory() === $this) {
            $recipe->setCategory(null);
        }

        return $this;
    }
}
