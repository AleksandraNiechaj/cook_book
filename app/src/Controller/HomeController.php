<?php

declare(strict_types=1);

/**
 * Kontroler odpowiedzialny za stronę główną aplikacji.
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
     *
     * @param RecipeServiceInterface   $recipes    serwis przepisów
     * @param CategoryServiceInterface $categories serwis kategorii
     *
     * @return Response
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
