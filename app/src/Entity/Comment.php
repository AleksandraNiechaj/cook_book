<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 *
 * PHP version 8.3
 *
 * @author    Aleksandra Niechaj <aleksandra.niechaj@example.com>
 * @copyright 2025 Aleksandra Niechaj
 * @license   For educational purposes (course project).
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Komentarz do przepisu (autor, email, treść, data, ocena, powiązanie z przepisem).
 */
#[ORM\Index(name: 'idx_comments_recipe_id', columns: ['recipe_id'])]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'comment.validation.name_required')]
    #[Assert\Length(max: 100, maxMessage: 'comment.validation.name_too_long')]
    #[ORM\Column(length: 100)]
    private ?string $authorName = null;

    #[Assert\NotBlank(message: 'comment.validation.email_required')]
    #[Assert\Email(message: 'comment.validation.email_invalid')]
    #[Assert\Length(max: 180, maxMessage: 'comment.validation.email_too_long')]
    #[ORM\Column(length: 180)]
    private ?string $authorEmail = null;

    #[Assert\NotBlank(message: 'comment.validation.content_required')]
    #[Assert\Length(min: 3, minMessage: 'comment.validation.content_too_short')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'comment.validation.rating_range')]
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $rating = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    /**
     * Zwraca identyfikator komentarza.
     *
     * @return int|null ID komentarza
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Zwraca imię autora komentarza.
     *
     * @return string|null imię autora
     */
    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    /**
     * Ustawia imię autora komentarza.
     *
     * @param string $authorName imię autora
     *
     * @return self
     */
    public function setAuthorName(string $authorName): static
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Zwraca email autora komentarza.
     *
     * @return string|null email autora
     */
    public function getAuthorEmail(): ?string
    {
        return $this->authorEmail;
    }

    /**
     * Ustawia email autora komentarza.
     *
     * @param string $authorEmail email autora
     *
     * @return self
     */
    public function setAuthorEmail(string $authorEmail): static
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * Zwraca treść komentarza.
     *
     * @return string|null treść komentarza
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Ustawia treść komentarza.
     *
     * @param string $content treść komentarza
     *
     * @return self
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Zwraca ocenę komentarza.
     *
     * @return int|null ocena w skali 1–5
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * Ustawia ocenę komentarza.
     *
     * @param int|null $rating ocena w skali 1–5
     *
     * @return self
     */
    public function setRating(?int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Zwraca datę utworzenia komentarza.
     *
     * @return \DateTimeImmutable|null data utworzenia
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Ustawia datę utworzenia komentarza.
     *
     * @param \DateTimeImmutable $createdAt data utworzenia
     *
     * @return self
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Zwraca powiązany przepis.
     *
     * @return Recipe|null przepis
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * Ustawia powiązany przepis.
     *
     * @param Recipe|null $recipe przepis
     *
     * @return self
     */
    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
