<?php

declare(strict_types=1);

/**
 * Serwis do obsługi logiki użytkowników.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Serwis do obsługi logiki użytkowników.
 */
final class UserService implements UserServiceInterface
{
    /**
     * Konstruktor serwisu użytkowników.
     *
     * @param UserRepository         $users repozytorium użytkowników
     * @param EntityManagerInterface $em    menedżer encji
     */
    public function __construct(private readonly UserRepository $users, private readonly EntityManagerInterface $em)
    {
    }

    /**
     * Pobiera listę użytkowników z paginacją i sortowaniem.
     *
     * @param int    $page  numer strony
     * @param int    $limit liczba elementów na stronę
     * @param string $sort  pole sortowania
     * @param string $dir   kierunek sortowania (ASC/DESC)
     *
     * @return array<string, mixed> dane paginacji
     */
    public function getPaginatedList(int $page, int $limit, string $sort, string $dir): array
    {
        $allowedSorts = ['id', 'email'];
        if (!\in_array($sort, $allowedSorts, true)) {
            $sort = 'email';
        }

        $dir    = 'DESC' === \strtoupper($dir) ? 'DESC' : 'ASC';
        $offset = ($page - 1) * $limit;

        $qb = $this->users->createQueryBuilder('u')
            ->orderBy('u.'.$sort, $dir)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $items = $qb->getQuery()->getResult();

        $total = (int) $this->users->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $pages = (int) \ceil($total / $limit);

        return [
            'items' => $items,
            'total' => $total,
            'pages' => $pages,
            'sort'  => $sort,
            'dir'   => $dir,
        ];
    }

    /**
     * Zapisuje użytkownika.
     *
     * @param User $user encja użytkownika
     */
    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Zmienia hasło użytkownika.
     *
     * @param User                        $user          encja użytkownika
     * @param string                      $plainPassword nowe hasło w postaci jawnej
     * @param UserPasswordHasherInterface $hasher        serwis haszujący hasła
     */
    public function changePassword(User $user, string $plainPassword, UserPasswordHasherInterface $hasher): void
    {
        $user->setPassword($hasher->hashPassword($user, $plainPassword));
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Usuwa użytkownika.
     *
     * @param User $user encja użytkownika
     */
    public function delete(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
