<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Serwis do obsługi logiki biznesowej przepisów.
 */
final class RecipeService implements RecipeServiceInterface
{
    public function __construct(
        private readonly RecipeRepository $recipes,
        private readonly PaginatorInterface $paginator
    ) {
    }

    public function save(Recipe $recipe): void
    {
        $this->recipes->save($recipe);
    }

    public function delete(Recipe $recipe): void
    {
        $this->recipes->delete($recipe);
    }

    public function paginateLatest(int $page, int $perPage = 10): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->recipes->qbLatest(),
            max(1, $page),
            $perPage
        );
    }

    public function paginateByCategory(Category $category, int $page, int $perPage = 10): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->recipes->qbByCategory($category),
            max(1, $page),
            $perPage
        );
    }

    public function findWithComments(int $id): ?Recipe
    {
        return $this->recipes->findWithComments($id);
    }

    public function latest(int $limit = 3): array
    {
        return $this->recipes->qbLatest()
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
