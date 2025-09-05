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

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentType;
use App\Form\RecipeType;
use App\Service\CommentService;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Kontroler do obsługi przepisów.
 */
final class RecipeController extends AbstractController
{
    /**
     * Lista przepisów z paginacją.
     *
     * @param Request       $request obiekt żądania
     * @param RecipeService $recipes serwis przepisów
     *
     * @return Response
     */
    #[Route('/recipe/', name: 'app_recipe_index', methods: ['GET'])]
    public function index(Request $request, RecipeService $recipes): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $recipes->paginateLatest($page, 10);

        return $this->render('recipe/index.html.twig', [
            'recipes' => $pagination,
        ]);
    }

    /**
     * Dodawanie nowego przepisu.
     *
     * @param Request       $request obiekt żądania
     * @param RecipeService $recipes serwis przepisów
     *
     * @return Response
     */
    #[Route('/recipe/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecipeService $recipes): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTimeImmutable();
            $recipe->setCreatedAt($now);
            $recipe->setUpdatedAt($now);

            $recipes->save($recipe);

            return $this->redirectToRoute('app_recipe_index');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edycja przepisu.
     *
     * @param Request       $request obiekt żądania
     * @param Recipe        $recipe  encja przepisu
     * @param RecipeService $recipes serwis przepisów
     *
     * @return Response
     */
    #[Route('/recipe/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, RecipeService $recipes): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $recipes->save($recipe);

            return $this->redirectToRoute('app_recipe_index');
        }

        return $this->render('recipe/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }

    /**
     * Usuwanie przepisu.
     *
     * @param Recipe        $recipe  encja przepisu
     * @param RecipeService $recipes serwis przepisów
     *
     * @return Response
     */
    #[Route('/recipe/{id}/delete', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Recipe $recipe, RecipeService $recipes): Response
    {
        $recipes->delete($recipe);
        $this->addFlash('success', 'Przepis został usunięty.');

        return $this->redirectToRoute('app_recipe_index');
    }

    /**
     * Szczegóły przepisu wraz z możliwością dodania komentarza.
     *
     * @param int            $id       identyfikator przepisu
     * @param Request        $request  obiekt żądania
     * @param RecipeService  $recipes  serwis przepisów
     * @param CommentService $comments serwis komentarzy
     *
     * @return Response
     */
    #[Route('/recipe/{id}', name: 'recipe_show', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function show(int $id, Request $request, RecipeService $recipes, CommentService $comments): Response
    {
        $recipe = $recipes->findWithComments($id);
        if (!$recipe instanceof Recipe) {
            throw $this->createNotFoundException();
        }

        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setRecipe($recipe);
            $comment->setCreatedAt(new \DateTimeImmutable());

            $comments->save($comment);

            $this->addFlash('success', 'Komentarz dodany.');

            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
