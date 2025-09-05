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
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Kategoria przepisu (nazwa, slug, znaczniki czasowe).
 */
#[ORM\Table(name: 'categories')]
#[UniqueEntity(fields: ['slug'], message: 'Slug must be unique.')]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100, minMessage: 'Name is too short.', maxMessage: 'Name is too long.')]
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[Assert\NotBlank]
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
     * Zwraca identyfikator kategorii.
     *
     * @return int|null ID kategorii
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Zwraca nazwę kategorii.
     *
     * @return string|null nazwa kategorii
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Ustawia nazwę kategorii.
     *
     * @param string $name nazwa kategorii
     *
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Zwraca slug kategorii.
     *
     * @return string|null slug
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Ustawia slug kategorii.
     *
     * @param string $slug slug
     *
     * @return static
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Zwraca datę utworzenia.
     *
     * @return \DateTimeImmutable|null data utworzenia
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Ustawia datę utworzenia.
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
     * Zwraca datę ostatniej aktualizacji.
     *
     * @return \DateTimeImmutable|null data aktualizacji
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Ustawia datę ostatniej aktualizacji.
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
}
