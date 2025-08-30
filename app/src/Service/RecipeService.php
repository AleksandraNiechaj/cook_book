<?php
declare(strict_types=1);

/**
 * Cook Book — educational project
 * (c) 2025 Aleksandra Niechaj
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;

final class RecipeService
{
    public function __construct(
        private RecipeRepository $recipes,
        private PaginatorInterface $paginator
    ) {}

    /** Zapis przepisu. */
    public function save(Recipe $recipe): void
    {
        $this->recipes->save($recipe);
    }

    /** Usunięcie przepisu. */
    public function delete(Recipe $recipe): void
    {
        $this->recipes->delete($recipe);
    }

    /** Paginacja najnowszych przepisów. */
    public function paginateLatest(int $page, int $perPage = 10)
    {
        return $this->paginator->paginate(
            $this->recipes->qbLatest(),
            max(1, $page),
            $perPage
        );
    }

    /** Paginacja przepisów dla kategorii. */
    public function paginateByCategory(Category $category, int $page, int $perPage = 10)
    {
        return $this->paginator->paginate(
            $this->recipes->qbByCategory($category),
            max(1, $page),
            $perPage
        );
    }

    /** Widok szczegółowy z komentarzami (JOIN FETCH). */
    public function findWithComments(int $id): ?Recipe
    {
        return $this->recipes->findWithComments($id);
    }

    /** 3 najnowsze na stronę główną (bez paginacji). */
    public function latest(int $limit = 3): array
    {
        return $this->recipes->qbLatest()
            ->setMaxResults($limit)
            ->getQuery()->getResult();
    }
}
