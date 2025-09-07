<?php

declare(strict_types=1);

/**
 * Obsługa komentarzy (dodawanie i usuwanie).
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentType;
use App\Service\CommentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Kontroler odpowiedzialny za dodawanie i usuwanie komentarzy.
 */
final class CommentController extends AbstractController
{
    /**
     * Dodaje komentarz do przepisu.
     *
     * @param Recipe                  $recipe   encja przepisu
     * @param Request                 $request  obiekt żądania
     * @param CommentServiceInterface $comments serwis komentarzy
     *
     * @return array Result
     */
    #[Route('/comment/add/{id}', name: 'app_comment_add', methods: ['GET', 'POST'])]
    public function add(Recipe $recipe, Request $request, CommentServiceInterface $comments): Response
    {
        if (!$this->getUser() instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            $this->addFlash('warning', 'Musisz być zalogowana/y, aby dodać komentarz.');

            return $this->redirectToRoute('app_login');
        }

        $comment = new Comment();

        $email = (string) $this->getUser()?->getUserIdentifier();
        $nick = \strstr($email, '@', true) ?: $email;

        $comment->setAuthorEmail($email);
        $comment->setAuthorName($nick);
        $comment->setRecipe($recipe);
        $comment->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comments->save($comment);
            $this->addFlash('success', 'Komentarz został dodany!');

            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('comment/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Usuwa komentarz powiązany z przepisem.
     *
     * @param Comment                 $comment  encja komentarza
     * @param CommentServiceInterface $comments serwis komentarzy
     *
     * @return array Result
     */
    #[Route('/comment/delete/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Comment $comment, CommentServiceInterface $comments): Response
    {
        $comments->delete($comment);
        $this->addFlash('success', 'Komentarz został usunięty.');

        return $this->redirectToRoute('recipe_show', ['id' => $comment->getRecipe()->getId()]);
    }
}
