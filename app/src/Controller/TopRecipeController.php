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

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Lista najwyżej ocenianych przepisów.
 */
final class TopRecipeController extends AbstractController
{
    /**
     * Wyświetlenie listy najwyżej ocenianych przepisów.
     *
     * @param RecipeRepository $recipes Repozytorium przepisów
     *
     * @return Response
     */
    #[Route('/recipes/top', name: 'app_recipe_top', methods: ['GET'])]
    public function top(RecipeRepository $recipes): Response
    {
        $qb = $recipes->createQueryBuilder('r')
            ->leftJoin('r.comments', 'c', 'WITH', 'c.rating IS NOT NULL')
            ->select('r AS recipe, AVG(c.rating) AS avgRating, COUNT(c.id) AS ratingsCount')
            ->groupBy('r.id')
            ->having('COUNT(c.id) > 0')
            ->orderBy('avgRating', 'DESC')
            ->addOrderBy('ratingsCount', 'DESC')
            ->setMaxResults(20);

        $items = $qb->getQuery()->getResult();

        return $this->render('recipe/top.html.twig', [
            'items' => $items,
        ]);
    }
}
