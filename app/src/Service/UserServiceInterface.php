<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface UserServiceInterface
{
    public function save(User $user): void;

    public function delete(User $user): void;

    public function changePassword(User $user, string $plainPassword, UserPasswordHasherInterface $hasher): void;

    /**
     * Pobranie listy użytkowników z paginacją i sortowaniem.
     *
     * @param int    $page numer strony
     * @param int    $limit liczba elementów na stronę
     * @param string $sort pole do sortowania (np. email)
     * @param string $dir kierunek sortowania (ASC/DESC)
     *
     * @return array<string, mixed> dane paginacji
     */
    public function getPaginatedList(int $page, int $limit, string $sort, string $dir): array;
}
