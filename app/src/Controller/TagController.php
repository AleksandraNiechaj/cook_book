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

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\RecipeRepository;
use App\Repository\TagRepository;
use App\Service\TagServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Kontroler odpowiedzialny za obsługę tagów (CRUD i widok).
 */
final class TagController extends AbstractController
{
    /**
     * Konstruktor.
     *
     * @param TagServiceInterface $tagService serwis obsługi tagów
     */
    public function __construct(private readonly TagServiceInterface $tagService)
    {
    }

    /**
     * Lista wszystkich tagów posortowanych alfabetycznie.
     *
     * @param TagRepository $tags repozytorium tagów
     */
    #[Route('/tags', name: 'tag_index', methods: ['GET'])]
    public function index(TagRepository $tags): Response
    {
        return $this->render('tag/index.html.twig', [
            'items' => $tags->findBy([], ['name' => 'ASC']),
        ]);
    }

    /**
     * Dodawanie nowego tagu.
     *
     * @param Request $request obiekt żądania
     */
    #[Route('/tags/new', name: 'tag_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);

            $this->addFlash('success', 'Tag utworzony.');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edycja istniejącego tagu.
     *
     * @param Tag     $tag     encja tagu
     * @param Request $request obiekt żądania
     */
    #[Route('/tags/{id}/edit', name: 'tag_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Tag $tag, Request $request): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);
            $this->addFlash('success', 'Tag zaktualizowany.');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
            'item' => $tag,
        ]);
    }

    /**
     * Usuwanie tagu.
     *
     * @param Tag $tag encja tagu
     */
    #[Route('/tags/{id}/delete', name: 'tag_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Tag $tag): Response
    {
        $this->tagService->delete($tag);
        $this->addFlash('success', 'Tag usunięty.');

        return $this->redirectToRoute('tag_index');
    }

    /**
     * Wyświetlanie przepisów powiązanych z danym tagiem.
     *
     * @param string           $slug    slug tagu
     * @param TagRepository    $tags    repozytorium tagów
     * @param RecipeRepository $recipes repozytorium przepisów
     * @param Request          $request obiekt żądania
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

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($qb, true);
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
