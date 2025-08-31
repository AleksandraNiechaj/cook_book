<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Index(name: 'idx_comments_recipe_id', columns: ['recipe_id'])]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
/**
 * Komentarz do przepisu (autor, email, treść, data, powiązanie z przepisem).
 */
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Name is required.')]
    #[Assert\Length(max: 100, maxMessage: 'Name is too long.')]
    #[ORM\Column(length: 100)]
    private ?string $authorName = null;

    #[Assert\NotBlank(message: 'Email is required.')]
    #[Assert\Email(message: 'Please enter a valid email.')]
    #[Assert\Length(max: 180, maxMessage: 'Email is too long.')]
    #[ORM\Column(length: 180)]
    private ?string $authorEmail = null;

    #[Assert\NotBlank(message: 'Comment cannot be empty.')]
    #[Assert\Length(min: 3, minMessage: 'Comment is too short.')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    /**
     * Pobiera identyfikator komentarza.
     *
     * @return int|null Id komentarza
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Pobiera nazwę autora komentarza.
     *
     * @return string|null Nick autora
     */
    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    /**
     * Ustawia nazwę autora komentarza.
     *
     * @param string $authorName Nick autora
     *
     * @return static
     */
    public function setAuthorName(string $authorName): static
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Pobiera adres e-mail autora komentarza.
     *
     * @return string|null Adres e-mail autora
     */
    public function getAuthorEmail(): ?string
    {
        return $this->authorEmail;
    }

    /**
     * Ustawia adres e-mail autora komentarza.
     *
     * @param string $authorEmail Adres e-mail
     *
     * @return static
     */
    public function setAuthorEmail(string $authorEmail): static
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * Pobiera treść komentarza.
     *
     * @return string|null Treść komentarza
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Ustawia treść komentarza.
     *
     * @param string $content Treść komentarza
     *
     * @return static
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Pobiera datę utworzenia komentarza.
     *
     * @return \DateTimeImmutable|null Data utworzenia
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Ustawia datę utworzenia komentarza.
     *
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
     * Pobiera przepis powiązany z komentarzem.
     *
     * @return Recipe|null Powiązany przepis
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * Ustawia powiązany przepis.
     *
     * @param Recipe|null $recipe Przepis
     *
     * @return static
     */
    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
