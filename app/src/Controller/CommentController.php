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

final class CommentController extends AbstractController
{
    #[\Symfony\Component\Routing\Attribute\Route('/comment/add/{id}', name: 'app_comment_add', methods: ['POST'])]
    public function add(Recipe $recipe, Request $request, CommentService $comments): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Musisz być zalogowana/y, aby dodać komentarz.');

            return $this->redirectToRoute('app_login');
        }

        $comment = new Comment();

        // ✅ Ustawiamy wymagane pola PRZED walidacją (walidator widzi komplet danych)
        $email = (string) $this->getUser()?->getUserIdentifier();
        $nick  = \strstr($email, '@', true) ?: $email;

        $comment->setAuthorEmail($email);
        $comment->setAuthorName($nick);
        $comment->setRecipe($recipe);                    // <- potrzebne do Assert\NotNull
        $comment->setCreatedAt(new \DateTimeImmutable()); // opcjonalnie tu, bez wpływu na walidację

        // Formularz mapuje tylko pola: content + rating
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comments->save($comment);
            $this->addFlash('success', 'Komentarz został dodany!');

            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        // Jeśli są błędy, pokaż przyczynę (np. brak wybranej oceny)
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $errors[] = $error->getMessage();
        }
        $msg = \count($errors) > 0
            ? 'Nie udało się dodać komentarza: ' . \implode(' ', $errors)
            : 'Nie udało się dodać komentarza.';
        $this->addFlash('error', $msg);

        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
    }

    #[\Symfony\Component\Routing\Attribute\Route('/comment/delete/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Comment $comment, Request $request, CommentService $comments): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), (string) $request->request->get('_token'))) {
            $comments->delete($comment);
            $this->addFlash('success', 'Komentarz został usunięty.');
        }

        return $this->redirectToRoute('recipe_show', ['id' => $comment->getRecipe()->getId()]);
    }
}
