<?php

declare(strict_types=1);

/**
 * Kontroler obsługujący operacje CRUD na kategoriach.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\CategoryService;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Kontroler odpowiedzialny za operacje CRUD na kategoriach.
 */
final class CategoryController extends AbstractController
{
    /**
     * Wyświetla listę kategorii.
     *
     * @param CategoryService $categories serwis kategorii
     *
     * @return Response
     */
    #[Route('/categories', name: 'category_list', methods: ['GET'])]
    public function list(CategoryService $categories): Response
    {
        return $this->render('category/list.html.twig', [
            'categories' => $categories->allOrdered(),
        ]);
    }

    /**
     * Tworzy nową kategorię.
     *
     * @param Request         $request    obiekt żądania
     * @param CategoryService $categories serwis kategorii
     *
     * @return Response
     */
    #[Route('/categories/new', name: 'category_new', methods: ['GET', 'POST'])]
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

            $this->addFlash('success', 'category.flash.created');

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Wyświetla pojedynczą kategorię wraz z przepisami.
     *
     * @param string              $slug       identyfikator slug kategorii
     * @param Request             $request    obiekt żądania
     * @param CategoryService     $categories serwis kategorii
     * @param RecipeService       $recipes    serwis przepisów
     * @param TranslatorInterface $translator tłumaczenia komunikatów
     *
     * @return Response
     */
    #[Route('/categories/{slug}', name: 'category_show', methods: ['GET'])]
    public function show(string $slug, Request $request, CategoryService $categories, RecipeService $recipes, TranslatorInterface $translator): Response
    {
        $category = $categories->bySlug($slug);
        if (!$category instanceof Category) {
            throw $this->createNotFoundException($translator->trans('category.not_found'));
        }

        $page = $request->query->getInt('page', 1);
        $pagination = $recipes->paginateByCategory($category, $page, 10);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'recipes' => $pagination,
        ]);
    }

    /**
     * Edytuje istniejącą kategorię.
     *
     * @param Request         $request    obiekt żądania
     * @param Category        $category   encja kategorii
     * @param CategoryService $categories serwis kategorii
     *
     * @return Response
     */
    #[Route('/categories/{id}/edit', name: 'category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, CategoryService $categories): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable());
            $categories->save($category);

            $this->addFlash('success', 'category.flash.updated');

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Usuwa kategorię.
     *
     * @param Category        $category   encja kategorii
     * @param CategoryService $categories serwis kategorii
     *
     * @return Response
     */
    #[Route('/categories/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(Category $category, CategoryService $categories): Response
    {
        $categories->delete($category);
        $this->addFlash('success', 'category.flash.deleted');

        return $this->redirectToRoute('category_list');
    }
}
