<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Category;

interface CategoryServiceInterface
{
    /**
     * Zwraca wszystkie kategorie w kolejności alfabetycznej lub wg innego kryterium.
     *
     * @return array<Category>
     */
    public function allOrdered(): array;

    /**
     * Znajduje kategorię po slug-u.
     */
    public function bySlug(string $slug): ?Category;

    /**
     * Zapisuje kategorię.
     */
    public function save(Category $category): void;

    /**
     * Usuwa kategorię.
     */
    public function delete(Category $category): void;
}
