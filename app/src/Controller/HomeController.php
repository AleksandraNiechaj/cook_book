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

namespace App\Controller;

use App\Service\CategoryServiceInterface;
use App\Service\RecipeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Kontroler odpowiedzialny za stronę główną aplikacji.
 */
final class HomeController extends AbstractController
{
    /**
     * Strona główna z listą kategorii i najnowszymi przepisami.
     */
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(RecipeServiceInterface $recipes, CategoryServiceInterface $categories): Response
    {
        $allCategories = $categories->allOrdered();
        $latest = $recipes->latest(3);

        return $this->render('home/index.html.twig', [
            'categories' => $allCategories,
            'recipes' => $latest,
        ]);
    }
}