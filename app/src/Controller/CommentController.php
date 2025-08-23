<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    // Dodawanie komentarza do przepisu
    #[Route('/add/{id}', name: 'app_comment_add', methods: ['POST'])]
    public function add(
        Recipe $recipe,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setRecipe($recipe);
            $comment->setCreatedAt(new \DateTimeImmutable());

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Komentarz został dodany!');
        }

        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
    }

    // Usuwanie komentarza (tylko admin)
    #[Route('/delete/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Komentarz został usunięty.');
        }

        return $this->redirectToRoute('recipe_show', ['id' => $comment->getRecipe()->getId()]);
    }
}
