<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\CategoryService;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(
        RecipeService $recipes,
        CategoryService $categories,
        AuthenticationUtils $authenticationUtils
    ): Response {
        // kategorie (posortowane) + 3 najnowsze przepisy
        $allCategories = $categories->allOrdered();
        $latest = $recipes->latest(3);

        // obsługa logowania
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home/index.html.twig', [
            'categories'    => $allCategories,
            'recipes'       => $latest,
            'error'         => $error,
            'last_username' => $lastUsername,
        ]);
    }
}
