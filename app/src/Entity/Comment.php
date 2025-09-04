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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): static
    {
        $this->authorName = $authorName;
        return $this;
    }

    public function getAuthorEmail(): ?string
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail(string $authorEmail): static
    {
        $this->authorEmail = $authorEmail;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): static
    {
        $this->rating = $rating;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;
        return $this;
    }
}