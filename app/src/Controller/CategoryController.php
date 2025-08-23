<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface; // 👈 dodane
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('', name: 'category_list', methods: ['GET'])]
    public function list(CategoryRepository $categoryRepo): Response
    {
        $categories = $categoryRepo->findBy([], ['name' => 'ASC']);

        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'category_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTimeImmutable();
            $category->setCreatedAt($now);
            $category->setUpdatedAt($now);

            $em->persist($category);
            $em->flush();

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
        CategoryRepository $categoryRepo,
        RecipeRepository $recipeRepo,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $category = $categoryRepo->findOneBy(['slug' => $slug]);
        if (!$category) {
            throw $this->createNotFoundException('Kategoria nie istnieje');
        }

        $qb = $recipeRepo->createQueryBuilder('r')
            ->andWhere('r.category = :cat')
            ->setParameter('cat', $category)
            ->orderBy('r.createdAt', 'DESC');

        $recipes = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1), // numer strony z URL
            10 // ile rekordów na stronę
        );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'recipes'  => $recipes,
        ]);
    }

    #[Route('/{id}/edit', name: 'category_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Kategoria zaktualizowana.');
            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'Kategoria usunięta.');
        }

        return $this->redirectToRoute('category_list');
    }
}
