<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 *
 * PHP version 8.3
 *
 * @author    Aleksandra Niechaj <aleksandra.niechaj@example.com>
 *
 * @copyright 2025 Aleksandra Niechaj
 *
 * @license   For educational purposes (course project).
 */

namespace App\Service;

use App\Entity\Category;

/**
 * Interfejs serwisu do obsługi logiki biznesowej kategorii.
 */
interface CategoryServiceInterface
{
    /**
     * Zwraca wszystkie kategorie w kolejności alfabetycznej lub wg innego kryterium.
     *
     * @return array<Category> lista kategorii
     */
    public function allOrdered(): array;

    /**
     * Znajduje kategorię po slug-u.
     *
     * @param string $slug unikalny identyfikator kategorii w URL
     *
     * @return Category|null znaleziona kategoria lub null, jeśli brak
     */
    public function bySlug(string $slug): ?Category;

    /**
     * Zapisuje kategorię.
     *
     * @param Category $category encja kategorii
     *
     * @return void
     */
    public function save(Category $category): void;

    /**
     * Usuwa kategorię.
     *
     * @param Category $category encja kategorii
     *
     * @return void
     */
    public function delete(Category $category): void;
}
