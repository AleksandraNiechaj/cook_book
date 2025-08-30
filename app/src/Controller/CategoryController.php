<?php
declare(strict_types=1);

/**
 * Cook Book — educational project
 * (c) 2025 Aleksandra Niechaj
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

#[Route('/categories')]
final class CategoryController extends AbstractController
{
    #[Route('', name: 'category_list', methods: ['GET'])]
    public function list(CategoryService $categories): Response
    {
        return $this->render('category/list.html.twig', [
            'categories' => $categories->allOrdered(),
        ]);
    }

    #[Route('/new', name: 'category_new', methods: ['GET','POST'])]
    public function new(Request $request, CategoryService $categories): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTimeImmutable();
            $category->setCreatedAt($now);
            $category->setUpdatedAt($now);

            $categories->save($category); // przez serwis → repo

            $this->addFlash('success', 'Kategoria dodana.');
            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'category_show', methods: ['GET'])]
    public function show(
        string $slug,
        Request $request,
        CategoryService $categories,
        RecipeService $recipes
    ): Response {
        // kategoria po slug (przez serwis)
        $category = $categories->bySlug($slug);
        if (!$category) {
            throw $this->createNotFoundException('Kategoria nie istnieje');
        }

        // paginacja przepisów tej kategorii (przez serwis)
        $page = $request->query->getInt('page', 1);
        $pagination = $recipes->paginateByCategory($category, $page, 10);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'recipes'  => $pagination,
        ]);
    }

    #[Route('/{id}/edit', name: 'category_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Category $category, CategoryService $categories): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable());
            $categories->save($category); // przez serwis → repo

            $this->addFlash('success', 'Kategoria zaktualizowana.');
            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryService $categories): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), (string) $request->request->get('_token'))) {
            $categories->delete($category); // przez serwis → repo
            $this->addFlash('success', 'Kategoria usunięta.');
        }

        return $this->redirectToRoute('category_list');
    }
}
