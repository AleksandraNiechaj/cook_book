<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

final class CategoryService
{
    public function __construct(private CategoryRepository $categories) {}

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
