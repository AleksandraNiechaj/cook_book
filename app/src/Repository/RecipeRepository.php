<?php
declare(strict_types=1);

/**
 * Cook Book — educational project
 * (c) 2025 Aleksandra Niechaj
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * Zwraca QueryBuilder dla listy przepisów od najnowszych.
     *
     * Użycie: serwis paginuje wynik na tym QB.
     */
    public function qbLatest(): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC');
    }

    /**
     * Zwraca QueryBuilder dla przepisów w danej kategorii (od najnowszych).
     *
     * @param Category $category
     */
    public function qbByCategory(Category $category): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.category = :cat')
            ->setParameter('cat', $category)
            ->orderBy('r.createdAt', 'DESC');
    }

    /**
     * Pobiera przepis wraz z komentarzami i kategorią (JOIN FETCH),
     * aby uniknąć problemu N+1 na widoku szczegółowym.
     *
     * @param int $id
     * @return Recipe|null
     */
    public function findWithComments(int $id): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.comments', 'c')->addSelect('c')
            ->leftJoin('r.category', 'cat')->addSelect('cat')
            ->andWhere('r.id = :id')->setParameter('id', $id)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
