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

use App\Service\CategoryService;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Kontroler odpowiedzialny za stronę główną aplikacji.
 */
final class HomeController extends AbstractController
{
    /**
     * Strona główna z listą kategorii i najnowszymi przepisami.
     *
     * @param RecipeService       $recipes             Serwis do obsługi przepisów.
     * @param CategoryService     $categories          Serwis do obsługi kategorii.
     * @param AuthenticationUtils $authenticationUtils Obsługa logowania użytkownika.
     *
     * @return Response Odpowiedź HTTP.
     */
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(RecipeService $recipes, CategoryService $categories, AuthenticationUtils $authenticationUtils): Response
    {
        $allCategories = $categories->allOrdered();
        $latest = $recipes->latest(3);

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home/index.html.twig', [
            'categories' => $allCategories,
            'recipes' => $latest,
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }
}
