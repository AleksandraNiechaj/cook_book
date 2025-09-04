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

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tag przypisany do przepisu (unikalna nazwa i slug).
 */
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tags')]
#[ORM\UniqueConstraint(name: 'uniq_tags_slug', columns: ['slug'])]
#[ORM\UniqueConstraint(name: 'uniq_tags_name', columns: ['name'])]
#[UniqueEntity(fields: ['name'], message: 'Tag with this name already exists.')]
#[UniqueEntity(fields: ['slug'], message: 'Tag with this slug already exists.')]
final class Tag implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 80)]
    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 80)]
    #[Assert\Regex(pattern: '/^[a-z0-9\-]+$/', message: 'Only lowercase letters, digits and dashes are allowed.')]
    #[ORM\Column(length: 80)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}