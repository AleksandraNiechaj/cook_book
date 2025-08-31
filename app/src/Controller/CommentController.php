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
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kontroler odpowiedzialny za dodawanie i usuwanie komentarzy.
 */
final class CommentController extends AbstractController
{
    /**
     * Dodaje nowy komentarz do przepisu.
     *
     * @param Recipe         $recipe   przepis, do którego dodajemy komentarz
     * @param Request        $request  obiekt żądania HTTP
     * @param CommentService $comments serwis do obsługi komentarzy
     *
     * @return Response odpowiedź HTTP
     */
    #[\Symfony\Component\Routing\Attribute\Route('/comment/add/{id}', name: 'app_comment_add', methods: ['POST'])]
    public function add(Recipe $recipe, Request $request, CommentService $comments): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setRecipe($recipe);
            $comment->setCreatedAt(new \DateTimeImmutable());

            $comments->save($comment);
            $this->addFlash('success', 'Komentarz został dodany!');
        }

        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
    }

    /**
     * Usuwa komentarz (dostęp tylko dla admina).
     *
     * @param Comment        $comment  komentarz do usunięcia
     * @param Request        $request  obiekt żądania HTTP
     * @param CommentService $comments serwis do obsługi komentarzy
     *
     * @return Response odpowiedź HTTP
     */
    #[\Symfony\Component\Routing\Attribute\Route('/comment/delete/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Comment $comment, Request $request, CommentService $comments): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), (string) $request->request->get('_token'))) {
            $comments->delete($comment);
            $this->addFlash('success', 'Komentarz został usunięty.');
        }

        return $this->redirectToRoute('recipe_show', ['id' => $comment->getRecipe()->getId()]);
    }
}
