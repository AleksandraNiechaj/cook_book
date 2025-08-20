<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    #[Route('/recipe/{id}', name: 'recipe_show')]
    public function show(int $id): Response
    {
        $recipes = [
            1 => ['title' => 'Owsianka z owocami', 'content' => 'Płatki + mleko/woda, dusić 5–7 min...'],
            2 => ['title' => 'Jajecznica ze szczypiorkiem', 'content' => 'Jajka, masło, szczypiorek...'],
            3 => ['title' => 'Kurczak curry', 'content' => 'Pierś, curry, mleczko kokosowe...'],
            4 => ['title' => 'Sernik na zimno', 'content' => 'Herbatniki, masło, twaróg, żelatyna...'],
        ];

        $recipe = $recipes[$id] ?? null;
        if (!$recipe) {
            throw $this->createNotFoundException('Przepis nie istnieje');
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}
