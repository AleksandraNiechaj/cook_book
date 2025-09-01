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
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Kontroler odpowiedzialny za dodawanie i usuwanie komentarzy.
 */
final class CommentController extends AbstractController
{
    /**
     * Dodaje nowy komentarz do przepisu (tylko zalogowani).
     *
     * @param Recipe         $recipe   przepis, do którego dodajemy komentarz
     * @param Request        $request  obiekt żądania HTTP
     * @param CommentService $comments serwis do obsługi komentarzy
     *
     * @return Response odpowiedź HTTP
     */
    #[Route('/comment/add/{id}', name: 'app_comment_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(Recipe $recipe, Request $request, CommentService $comments): Response
    {
        $comment = new Comment();

        // 🔑 USTAWIAMY POWIĄZANIA PRZED WALIDACJĄ (bo recipe ma Assert\NotNull)
        $comment->setRecipe($recipe);
        $comment->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $comment->setUser($user);

            // Dla kompatybilności z istniejącymi widokami (email/nick)
            if ($comment->getAuthorEmail() === null) {
                $comment->setAuthorEmail($user->getEmail());
            }
            if ($comment->getAuthorName() === null) {
                $comment->setAuthorName(\explode('@', (string) $user->getEmail())[0] ?: 'user');
            }

            $comments->save($comment);
            $this->addFlash('success', 'Komentarz został dodany!');
        } else {
            // Wypiszemy konkretne błędy walidacji
            $messages = [];
            foreach ($form->getErrors(true) as $error) {
                $messages[] = $error->getMessage();
            }
            $this->addFlash('danger', $messages ? \implode(' ', $messages) : 'Nie udało się dodać komentarza.');
        }

        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
    }

    /**
     * Usuwa komentarz (dostęp tylko dla admina).
     */
    #[Route('/comment/delete/{id}', name: 'app_comment_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Comment $comment, Request $request, CommentService $comments): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), (string) $request->request->get('_token'))) {
            $comments->delete($comment);
            $this->addFlash('success', 'Komentarz został usunięty.');
        }

        return $this->redirectToRoute('recipe_show', ['id' => $comment->getRecipe()->getId()]);
    }
}
