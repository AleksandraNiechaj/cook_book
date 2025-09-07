<?php

declare(strict_types=1);

/**
 * Interfejs serwisu do obsługi logiki biznesowej przepisów.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Recipe;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interfejs serwisu do obsługi logiki biznesowej przepisów.
 */
interface RecipeServiceInterface
{
    /**
     * Pobiera najnowsze przepisy (limitowane).
     *
     * @param int $limit maksymalna liczba przepisów do pobrania
     *
     * @return array<Recipe> lista najnowszych przepisów
     */
    public function latest(int $limit): array;

    /**
     * Paginacja wszystkich przepisów (od najnowszych).
     *
     * @param int $page  numer strony
     * @param int $limit liczba elementów na stronę
     *
     * @return PaginationInterface obiekt paginacji
     */
    public function paginateLatest(int $page, int $limit): PaginationInterface;

    /**
     * Paginacja przepisów w danej kategorii.
     *
     * @param Category $category kategoria, dla której pobieramy przepisy
     * @param int      $page     numer strony
     * @param int      $limit    liczba elementów na stronę
     *
     * @return PaginationInterface obiekt paginacji
     */
    public function paginateByCategory(Category $category, int $page, int $limit): PaginationInterface;

    /**
     * Znajduje przepis wraz z powiązanymi komentarzami.
     *
     * @param int $id identyfikator przepisu
     *
     * @return Recipe|null przepis lub null, jeśli nie istnieje
     */
    public function findWithComments(int $id): ?Recipe;

    /**
     * Zapisuje przepis.
     *
     * @param Recipe $recipe encja przepisu
     */
    public function save(Recipe $recipe): void;

    /**
     * Usuwa przepis.
     *
     * @param Recipe $recipe encja przepisu
     */
    public function delete(Recipe $recipe): void;
}
