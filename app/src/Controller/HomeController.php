<?php
namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RecipeRepository $recipeRepo, CategoryRepository $categoryRepo): Response
    {
        // pobieramy wszystkie kategorie
        $categories = $categoryRepo->findAll();

        // pobieramy 3 najnowsze przepisy
        $recipes = $recipeRepo->findBy([], ['createdAt' => 'DESC'], 3);

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'recipes' => $recipes,
        ]);
    }
}
