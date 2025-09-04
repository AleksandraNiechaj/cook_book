<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function getPaginatedList(int $page, int $limit, string $sort, string $dir): array
    {
        $allowedSorts = ['id', 'email'];
        if (!\in_array($sort, $allowedSorts, true)) {
            $sort = 'email';
        }
        $dir = \strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';

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

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function changePassword(User $user, string $plainPassword, UserPasswordHasherInterface $hasher): void
    {
        $user->setPassword($hasher->hashPassword($user, $plainPassword));
        $this->em->persist($user);
        $this->em->flush();
    }

    public function delete(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
