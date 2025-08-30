<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentType;
use App\Form\RecipeType;
use App\Service\RecipeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recipe')]
final class RecipeController extends AbstractController
{
    // lista z paginacją (10 na stronę, sortowanie od najnowszych)
    #[Route('/', name: 'app_recipe_index', methods: ['GET'])]
    public function index(Request $request, RecipeService $recipes): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $recipes->paginateLatest($page, 10);

        return $this->render('recipe/index.html.twig', [
            // zachowuję nazwę 'recipes', bo tak masz w szablonie
            'recipes' => $pagination,
        ]);
    }

    // nowy przepis
    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTimeImmutable();
            $recipe->setCreatedAt($now);
            $recipe->setUpdatedAt($now);

            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('app_recipe_index');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // edycja
    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            return $this->redirectToRoute('app_recipe_index');
        }

        return $this->render('recipe/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }

    // usuwanie
    #[Route('/{id}/delete', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), (string) $request->request->get('_token'))) {
            $em->remove($recipe);
            $em->flush();
        }

        return $this->redirectToRoute('app_recipe_index');
    }

    // szczegóły + komentarze (pobranie przepisu przez serwis, z JOIN FETCH komentarzy)
    #[Route('/{id}', name: 'recipe_show', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function show(int $id, Request $request, RecipeService $recipes, EntityManagerInterface $em): Response
    {
        $recipe = $recipes->findWithComments($id);
        if (!$recipe) {
            throw $this->createNotFoundException();
        }

        // formularz komentarza
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setRecipe($recipe);
            $comment->setCreatedAt(new \DateTimeImmutable());

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Komentarz dodany.');
            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
