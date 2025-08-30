<?php
declare(strict_types=1);

/**
 * Cook Book — educational project
 * (c) 2025 Aleksandra Niechaj
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

final class CategoryService
{
    public function __construct(private CategoryRepository $categories) {}

    /** Zapis kategorii. */
    public function save(Category $category): void
    {
        $this->categories->save($category);
    }

    /** Usunięcie kategorii. */
    public function delete(Category $category): void
    {
        $this->categories->delete($category);
    }

    /** Wszystkie kategorie posortowane po nazwie. */
    public function allOrdered(): array
    {
        return $this->categories->findAllOrderedByName();
    }

    /** Znajdź kategorię po slug; null jeśli nie istnieje. */
    public function bySlug(string $slug): ?Category
    {
        return $this->categories->findOneBy(['slug' => $slug]);
    }
}
