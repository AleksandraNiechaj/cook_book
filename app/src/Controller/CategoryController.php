<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    private array $categories = [
        ['slug' => 'sniadania', 'name' => 'Śniadania'],
        ['slug' => 'obiady', 'name' => 'Obiady'],
        ['slug' => 'desery', 'name' => 'Desery'],
    ];

    #[Route('', name: 'category_list')]
    public function list(): Response
    {
        return $this->render('category/list.html.twig', [
            'categories' => $this->categories,
        ]);
    }

    #[Route('/{slug}', name: 'category_show')]
    public function show(string $slug): Response
    {
        $recipesByCategory = [
            'sniadania' => [
                ['id' => 1, 'title' => 'Owsianka z owocami', 'excerpt' => 'Szybkie i zdrowe śniadanie.'],
                ['id' => 2, 'title' => 'Jajecznica ze szczypiorkiem', 'excerpt' => 'Klasyka na ciepło.'],
            ],
            'obiady' => [
                ['id' => 3, 'title' => 'Kurczak curry', 'excerpt' => 'Aromatyczne i proste.'],
            ],
            'desery' => [
                ['id' => 4, 'title' => 'Sernik na zimno', 'excerpt' => 'Lekki i pyszny.'],
            ],
        ];

        $category = array_values(array_filter($this->categories, fn($c) => $c['slug'] === $slug))[0] ?? null;
        if (!$category) {
            throw $this->createNotFoundException('Kategoria nie istnieje');
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'recipes' => $recipesByCategory[$slug] ?? [],
        ]);
    }
}
