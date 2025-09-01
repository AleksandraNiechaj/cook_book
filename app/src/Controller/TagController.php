<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\RecipeRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class TagController extends AbstractController
{
    #[Route('/tags', name: 'tag_index', methods: ['GET'])]
    public function index(TagRepository $tags): Response
    {
        return $this->render('tag/index.html.twig', [
            'items' => $tags->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/tags/new', name: 'tag_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', 'Tag utworzony.');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tags/{id}/edit', name: 'tag_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Tag $tag, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Tag zaktualizowany.');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
            'item' => $tag,
        ]);
    }

    #[Route('/tags/{id}/delete', name: 'tag_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Tag $tag, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), (string) $request->request->get('_token'))) {
            $em->remove($tag);
            $em->flush();
            $this->addFlash('success', 'Tag usunięty.');
        }

        return $this->redirectToRoute('tag_index');
    }

    /**
     * Publiczny widok: lista przepisów w danym tagu (paginacja po 10, najnowsze najpierw).
     */
    #[Route('/tags/{slug}', name: 'tag_show', methods: ['GET'])]
    public function show(string $slug, TagRepository $tags, RecipeRepository $recipes, Request $request): Response
    {
        $tag = $tags->findOneBy(['slug' => $slug]);
        if (null === $tag) {
            throw $this->createNotFoundException();
        }

        $page  = \max(1, (int) $request->query->get('page', 1));
        $limit = 10;
        $qb = $recipes->createQueryBuilder('r')
            ->innerJoin('r.tags', 't')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('r.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb, true);
        $total = \count($paginator);
        $pages = (int) \ceil($total / $limit);

        return $this->render('tag/show.html.twig', [
            'tag'   => $tag,
            'items' => \iterator_to_array($paginator->getIterator()),
            'page'  => $page,
            'pages' => $pages,
            'total' => $total,
        ]);
    }
}
