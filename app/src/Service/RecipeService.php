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

/**
 * Serwis do obsługi logiki biznesowej przepisów.
 */
final class RecipeService
{
    /**
     * Konstruktor.
     *
     * @param RecipeRepository   $recipes   repozytorium
     *                                      przepisów
     * @param PaginatorInterface $paginator komponent paginacji
     */
    public function __construct(private readonly RecipeRepository $recipes, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Zapis przepisu.
     *
     * @param Recipe $recipe encja przepisu
     */
    public function save(Recipe $recipe): void
    {
        $this->recipes->save($recipe);
    }

    /**
     * Usunięcie przepisu.
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
     * @return mixed wynik paginacji
     */
    public function paginateLatest(int $page, int $perPage = 10): mixed
    {
        return $this->paginator->paginate(
            $this->recipes->qbLatest(),
            max(1, $page),
            $perPage
        );
    }

    /**
     * Paginacja przepisów dla danej kategorii.
     *
     * @param Category $category kategoria
     * @param int      $page     numer strony
     * @param int      $perPage  liczba elementów na stronę
     *
     * @return mixed wynik paginacji
     */
    public function paginateByCategory(Category $category, int $page, int $perPage = 10): mixed
    {
        return $this->paginator->paginate(
            $this->recipes->qbByCategory($category),
            max(1, $page),
            $perPage
        );
    }

    /**
     * Zwraca przepis wraz z komentarzami i kategorią.
     *
     * @param int $id id przepisu
     *
     * @return Recipe|null encja przepisu lub null
     */
    public function findWithComments(int $id): ?Recipe
    {
        return $this->recipes->findWithComments($id);
    }

    /**
     * Zwraca najnowsze przepisy (np. na stronę główną).
     *
     * @param int $limit maksymalna liczba wyników
     *
     * @return Recipe[] tablica przepisów
     */
    public function latest(int $limit = 3): array
    {
        return $this->recipes->qbLatest()
            ->setMaxResults($limit)
            ->getQuery()->getResult();
    }
}
