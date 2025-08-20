<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recipe')]
class RecipeController extends AbstractController
{
    // lista (przyda się do testów, później dodamy paginację 10/s)
    #[Route('/', name: 'app_recipe_index', methods: ['GET'])]
    public function index(RecipeRepository $repo): Response
    {
        $recipes = $repo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    // szczegóły po ID (ParamConverter wstrzyknie encję)
    #[Route('/{id}', name: 'recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}
