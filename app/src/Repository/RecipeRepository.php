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

    /** Zapis encji przepisu. */
    public function save(Recipe $entity): void
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    /** Usunięcie encji przepisu. */
    public function delete(Recipe $entity): void
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
    }

    /**
     * Lista od najnowszych — JOIN kategorii + partial select
     * (unikamy N+1 i nie ściągamy zbędnych kolumn).
     */
    public function qbLatest(): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->select(
            // wybieramy tylko to, co wyświetlasz na listach
                'partial r.{id, title, content, createdAt}',
                'partial c.{id, name, slug}'
            )
            ->join('r.category', 'c')
            ->orderBy('r.createdAt', 'DESC');
    }

    /**
     * Lista przepisów dla kategorii — też JOIN + partial
     * (na stronie kategorii i tak wyświetlasz tytuł/treść przepisu).
     */
    public function qbByCategory(Category $category): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->select('partial r.{id, title, content, createdAt}')
            // JOIN nie jest konieczny dla samego WHERE,
            // ale często i tak odwołujemy się do r.category.* w twigach;
            // można zostawić bez JOIN jeśli nic z kategorią nie renderujesz.
            // ->join('r.category', 'c')
            ->andWhere('r.category = :cat')
            ->setParameter('cat', $category)
            ->orderBy('r.createdAt', 'DESC');
    }

    /**
     * Szczegóły: pobieramy przepis wraz z komentarzami i kategorią (JOIN FETCH),
     * żeby nie było N+1 na widoku pojedynczego przepisu.
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
