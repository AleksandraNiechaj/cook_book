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

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Index(name: 'idx_comments_recipe_id', columns: ['recipe_id'])]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
/**
 * Komentarz do przepisu (autor, email, treść, data, ocena, powiązanie z przepisem).
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

    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'Rating must be between 1 and 5.')]
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $rating = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    /**
     * @return int|null Id komentarza
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null Nick autora
     */
    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    /**
     * @param string $authorName Nick autora
     */
    public function setAuthorName(string $authorName): static
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * @return string|null Adres e-mail autora
     */
    public function getAuthorEmail(): ?string
    {
        return $this->authorEmail;
    }

    /**
     * @param string $authorEmail Adres e-mail
     */
    public function setAuthorEmail(string $authorEmail): static
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * @return string|null Treść komentarza
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content Treść komentarza
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return int|null Ocena 1–5
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param int|null $rating Ocena 1–5
     */
    public function setRating(?int $rating): static
    {
        $this->rating = $rating;

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
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Recipe|null Powiązany przepis
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * @param Recipe|null $recipe Przepis
     */
    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
