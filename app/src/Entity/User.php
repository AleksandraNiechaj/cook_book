<?php
/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
/**
 * Użytkownik aplikacji (konto logowania i role).
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    /** @return int|null Id użytkownika */
    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return string|null E-mail użytkownika */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Ustawia e-mail.
     *
     * @param string $email E-mail użytkownika
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /** @return string Unikalny identyfikator logowania */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /** @return string[] Role użytkownika (zawsze co najmniej ROLE_USER) */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Ustawia role użytkownika.
     *
     * @param string[] $roles Tablica ról użytkownika
     *
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /** @return string Zaszyfrowane hasło */
    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    /**
     * Ustawia zaszyfrowane hasło.
     *
     * @param string $password Zaszyfrowane hasło
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /** Czyści dane tymczasowe (np. plainPassword). @return void */
    public function eraseCredentials(): void
    {
    }
}
