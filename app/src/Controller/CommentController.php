<?php
declare(strict_types=1);

/**
 * Cook Book — educational project
 * (c) 2025 Aleksandra Niechaj
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentType;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
final class CommentController extends AbstractController
{
    // Dodawanie komentarza do przepisu
    #[Route('/add/{id}', name: 'app_comment_add', methods: ['POST'])]
    public function add(
        Recipe $recipe,
        Request $request,
        CommentService $comments
    ): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setRecipe($recipe);
            $comment->setCreatedAt(new \DateTimeImmutable());

            $comments->save($comment); // przez serwis → repo
            $this->addFlash('success', 'Komentarz został dodany!');
        }

        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
    }

    // Usuwanie komentarza (tylko admin)
    #[Route('/delete/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Comment $comment, Request $request, CommentService $comments): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), (string) $request->request->get('_token'))) {
            $comments->delete($comment); // przez serwis → repo
            $this->addFlash('success', 'Komentarz został usunięty.');
        }

        return $this->redirectToRoute('recipe_show', ['id' => $comment->getRecipe()->getId()]);
    }
}
