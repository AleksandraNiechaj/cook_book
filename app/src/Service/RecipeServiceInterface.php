<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Category;
use App\Entity\Recipe;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface RecipeServiceInterface
{
    /**
     * Pobiera najnowsze przepisy (limitowane).
     *
     * @param int $limit
     *
     * @return array<Recipe>
     */
    public function latest(int $limit): array;

    /**
     * Paginacja wszystkich przepisów (od najnowszych).
     */
    public function paginateLatest(int $page, int $limit): PaginationInterface;

    /**
     * Paginacja przepisów w danej kategorii.
     */
    public function paginateByCategory(Category $category, int $page, int $limit): PaginationInterface;

    /**
     * Znajduje przepis wraz z komentarzami.
     */
    public function findWithComments(int $id): ?Recipe;

    /**
     * Zapisuje przepis.
     */
    public function save(Recipe $recipe): void;

    /**
     * Usuwa przepis.
     */
    public function delete(Recipe $recipe): void;
}
