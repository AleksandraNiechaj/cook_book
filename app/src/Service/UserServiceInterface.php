<?php

declare(strict_types=1);

/**
 * Interfejs serwisu użytkowników.
 */

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Interfejs serwisu użytkowników.
 */
interface UserServiceInterface
{
    /**
     * Zapisuje użytkownika.
     *
     * @param User $user encja użytkownika
     */
    public function save(User $user): void;

    /**
     * Usuwa użytkownika.
     *
     * @param User $user encja użytkownika
     */
    public function delete(User $user): void;

    /**
     * Zmienia hasło użytkownika.
     *
     * @param User                        $user          encja użytkownika
     * @param string                      $plainPassword nowe hasło w postaci jawnej
     * @param UserPasswordHasherInterface $hasher        serwis haszujący hasła
     */
    public function changePassword(User $user, string $plainPassword, UserPasswordHasherInterface $hasher): void;

    /**
     * Pobranie listy użytkowników z paginacją i sortowaniem.
     *
     * @param int    $page  numer strony
     * @param int    $limit liczba elementów na stronę
     * @param string $sort  pole do sortowania
     * @param string $dir   kierunek sortowania (ASC/DESC)
     *
     * @return array<string, mixed> dane paginacji
     */
    public function getPaginatedList(int $page, int $limit, string $sort, string $dir): array;
}
