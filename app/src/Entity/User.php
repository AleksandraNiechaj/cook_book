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

    /**
     * Pobiera ID użytkownika.
     *
     * @return int|null Id użytkownika
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Pobiera e-mail użytkownika.
     *
     * @return string|null E-mail użytkownika
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Ustawia e-mail.
     *
     * @param string $email E-mail użytkownika
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Zwraca unikalny identyfikator logowania.
     *
     * @return string Identyfikator logowania
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Pobiera role użytkownika (zawsze zawiera co najmniej ROLE_USER).
     *
     * @return string[] Role użytkownika
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    /**
     * Ustawia role użytkownika – zawsze dopisuje ROLE_USER.
     *
     * @param string[] $roles Tablica ról użytkownika
     */
    public function setRoles(array $roles): self
    {
        $roles[] = 'ROLE_USER';
        $this->roles = array_values(array_unique($roles));

        return $this;
    }

    /**
     * Pobiera zaszyfrowane hasło.
     *
     * @return string Zaszyfrowane hasło
     */
    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    /**
     * Ustawia zaszyfrowane hasło.
     *
     * @param string $password Zaszyfrowane hasło
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Czyści dane tymczasowe (np. plainPassword).
     */
    public function eraseCredentials(): void
    {
    }
}
