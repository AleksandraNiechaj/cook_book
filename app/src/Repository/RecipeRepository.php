<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 *
 * PHP version 8.3
 *
 * @author    Aleksandra Niechaj
 *
 * @copyright 2025
 *
 * @license   For educational purposes (course project).
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repozytorium encji Recipe.
 *
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    /**
     * Konstruktor repozytorium przepisów.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * Zapis encji przepisu.
     *
     * @param Recipe $entity Encja przepisu
     */
    public function save(Recipe $entity): void
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    /**
     * Usunięcie encji przepisu.
     *
     * @param Recipe $entity Encja przepisu
     */
    public function delete(Recipe $entity): void
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
    }

    /**
     * Lista od najnowszych — JOIN kategorii + partial select.
     *
     * @return QueryBuilder Query builder
     */
    public function qbLatest(): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->select(
                'partial r.{id, title, content, createdAt}',
                'partial c.{id, name, slug}'
            )
            ->join('r.category', 'c')
            ->orderBy('r.createdAt', 'DESC');
    }

    /**
     * Lista przepisów dla kategorii.
     *
     * @param Category $category Kategoria przepisu
     *
     * @return QueryBuilder Query builder
     */
    public function qbByCategory(Category $category): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->select('partial r.{id, title, content, createdAt}')
            ->andWhere('r.category = :cat')
            ->setParameter('cat', $category)
            ->orderBy('r.createdAt', 'DESC');
    }

    /**
     * Szczegóły: pobieramy przepis wraz z komentarzami, kategorią i tagami.
     *
     * @param int $id Id przepisu
     *
     * @return Recipe|null Przepis lub null
     */
    public function findWithComments(int $id): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.comments', 'c')->addSelect('c')
            ->leftJoin('r.category', 'cat')->addSelect('cat')
            ->leftJoin('r.tags', 't')->addSelect('t') // 👈 dociągamy tagi
            ->andWhere('r.id = :id')->setParameter('id', $id)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
