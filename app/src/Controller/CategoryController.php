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

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\CategoryService;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Kontroler odpowiedzialny za obsługę kategorii.
 */
#[Route('/categories')]
final class CategoryController extends AbstractController
{
    /**
     * Lista wszystkich kategorii.
     *
     * @param CategoryService $categories Serwis obsługujący kategorie.
     *
     * @return Response Odpowiedź HTTP.
     */
    #[Route('', name: 'category_list', methods: ['GET'])]
    public function list(CategoryService $categories): Response
    {
        return $this->render('category/list.html.twig', [
            'categories' => $categories->allOrdered(),
        ]);
    }


    /**
     * Dodawanie nowej kategorii.
     *
     * @param Request         $request    Obiekt żądania HTTP.
     * @param CategoryService $categories Serwis obsługujący kategorie.
     *
     * @return Response Odpowiedź HTTP.
     */
    #[Route('/new', name: 'category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryService $categories): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTimeImmutable();
            $category->setCreatedAt($now);
            $category->setUpdatedAt($now);

            $categories->save($category);

            $this->addFlash('success', 'Kategoria dodana.');

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }


    /**
     * Wyświetlanie pojedynczej kategorii.
     *
     * @param string          $slug       Unikalny identyfikator kategorii.
     * @param Request         $request    Obiekt żądania HTTP.
     * @param CategoryService $categories Serwis obsługujący kategorie.
     * @param RecipeService   $recipes    Serwis obsługujący przepisy.
     *
     * @return Response Odpowiedź HTTP.
     */
    #[Route('/{slug}', name: 'category_show', methods: ['GET'])]
    public function show(string $slug, Request $request, CategoryService $categories, RecipeService $recipes): Response
    {
        $category = $categories->bySlug($slug);
        if (!$category) {
            throw $this->createNotFoundException('Kategoria nie istnieje');
        }

        $page = $request->query->getInt('page', 1);
        $pagination = $recipes->paginateByCategory($category, $page, 10);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'recipes' => $pagination,
        ]);
    }


    /**
     * Edycja kategorii.
     *
     * @param Request         $request    Obiekt żądania HTTP.
     * @param Category        $category   Edytowana kategoria.
     * @param CategoryService $categories Serwis obsługujący kategorie.
     *
     * @return Response Odpowiedź HTTP.
     */
    #[Route('/{id}/edit', name: 'category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, CategoryService $categories): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable());
            $categories->save($category);

            $this->addFlash('success', 'Kategoria zaktualizowana.');

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }


    /**
     * Usuwanie kategorii.
     *
     * @param Request         $request    Obiekt żądania HTTP.
     * @param Category        $category   Usuwana kategoria.
     * @param CategoryService $categories Serwis obsługujący kategorie.
     *
     * @return Response Odpowiedź HTTP.
     */
    #[Route('/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryService $categories): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), (string) $request->request->get('_token'))) {
            $categories->delete($category);
            $this->addFlash('success', 'Kategoria usunięta.');
        }

        return $this->redirectToRoute('category_list');
    }
}
