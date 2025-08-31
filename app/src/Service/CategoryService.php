<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

/**
 * Logika biznesowa związana z kategoriami.
 */
final class CategoryService
{
    /**
     * Konstruktor.
     *
     * @param CategoryRepository $categories Repozytorium kategorii.
     */
    public function __construct(private readonly CategoryRepository $categories)
    {
    }

    /**
     * Zapis kategorii.
     *
     * @param Category $category Encja kategorii.
     *
     * @return void
     */
    public function save(Category $category): void
    {
        $this->categories->save($category);
    }

    /**
     * Usunięcie kategorii.
     *
     * @param Category $category Encja kategorii.
     *
     * @return void
     */
    public function delete(Category $category): void
    {
        $this->categories->delete($category);
    }

    /**
     * Wszystkie kategorie posortowane po nazwie.
     *
     * @return Category[] Lista kategorii.
     */
    public function allOrdered(): array
    {
        return $this->categories->findAllOrderedByName();
    }

    /**
     * Znajdź kategorię po slug.
     *
     * @param string $slug Slug kategorii.
     *
     * @return Category|null Znaleziona encja lub null.
     */
    public function bySlug(string $slug): ?Category
    {
        return $this->categories->findOneBy(['slug' => $slug]);
    }
}
