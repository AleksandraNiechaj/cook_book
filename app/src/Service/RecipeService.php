<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 *
 * PHP version 8.3
 *
 * @author    Aleksandra Niechaj <aleksandra.niechaj@example.com>
 * @copyright 2025 Aleksandra Niechaj
 * @license   For educational purposes (course project).
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Serwis do obsługi logiki biznesowej przepisów.
 */
final class RecipeService implements RecipeServiceInterface
{
    /**
     * Konstruktor serwisu.
     *
     * @param RecipeRepository   $recipes   repozytorium przepisów
     * @param PaginatorInterface $paginator komponent paginacji
     */
    public function __construct(private readonly RecipeRepository $recipes, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Zapisuje przepis.
     *
     * @param Recipe $recipe encja przepisu
     */
    public function save(Recipe $recipe): void
    {
        $this->recipes->save($recipe);
    }

    /**
     * Usuwa przepis.
     *
     * @param Recipe $recipe encja przepisu
     */
    public function delete(Recipe $recipe): void
    {
        $this->recipes->delete($recipe);
    }

    /**
     * Paginacja najnowszych przepisów.
     *
     * @param int $page    numer strony
     * @param int $perPage liczba elementów na stronę
     *
     * @return PaginationInterface obiekt paginacji
     */
    public function paginateLatest(int $page, int $perPage = 10): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->recipes->qbLatest(),
            max(1, $page),
            $perPage
        );
    }

    /**
     * Paginacja przepisów w danej kategorii.
     *
     * @param Category $category kategoria, dla której pobieramy przepisy
     * @param int      $page     numer strony
     * @param int      $perPage  liczba elementów na stronę
     *
     * @return PaginationInterface obiekt paginacji
     */
    public function paginateByCategory(Category $category, int $page, int $perPage = 10): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->recipes->qbByCategory($category),
            max(1, $page),
            $perPage
        );
    }

    /**
     * Znajduje przepis wraz z powiązanymi komentarzami.
     *
     * @param int $id identyfikator przepisu
     *
     * @return Recipe|null encja przepisu lub null
     */
    public function findWithComments(int $id): ?Recipe
    {
        return $this->recipes->findWithComments($id);
    }

    /**
     * Pobiera najnowsze przepisy (limitowane).
     *
     * @param int $limit maksymalna liczba przepisów
     *
     * @return array<Recipe> lista przepisów
     */
    public function latest(int $limit = 3): array
    {
        return $this->recipes->qbLatest()
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
