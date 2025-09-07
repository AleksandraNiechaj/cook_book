<?php

declare(strict_types=1);

/**
 * Tag przypisany do przepisu (unikalna nazwa i slug).
 */

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tags')]
#[ORM\UniqueConstraint(name: 'uniq_tags_slug', columns: ['slug'])]
#[ORM\UniqueConstraint(name: 'uniq_tags_name', columns: ['name'])]
#[UniqueEntity(fields: ['name'], message: 'Tag with this name already exists.')]
#[UniqueEntity(fields: ['slug'], message: 'Tag with this slug already exists.')]
/**
 * Klasa encji Tag (unikalna nazwa i slug).
 */
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
    #[Assert\Regex(
        pattern: '/^[a-z0-9\-]+$/',
        message: 'Only lowercase letters, digits and dashes are allowed.'
    )]
    #[ORM\Column(length: 80)]
    private ?string $slug = null;

    /**
     * Zwraca identyfikator taga.
     *
     * @return int|null Id taga
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Zwraca nazwę taga.
     *
     * @return string|null Nazwa taga
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Ustawia nazwę taga.
     *
     * @param string $name Nazwa taga
     *
     * @return array Result
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Zwraca slug taga.
     *
     * @return string|null Slug taga
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Ustawia slug taga.
     *
     * @param string $slug Slug taga
     *
     * @return array Result
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Reprezentacja obiektu jako string (nazwa taga).
     *
     * @return string Nazwa taga
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }
}
