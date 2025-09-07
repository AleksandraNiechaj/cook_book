<?php

declare(strict_types=1);

/**
 * Repozytorium encji Tag.
 *
 * @extends ServiceEntityRepository<Tag>
 */

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repozytorium encji Tag.
 *
 */
final class TagRepository extends ServiceEntityRepository
{
    /**
     * Konstruktor repozytorium tagów.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }
}
