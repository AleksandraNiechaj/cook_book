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

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Serwis do obsługi logiki biznesowej tagów.
 */
final class TagService implements TagServiceInterface
{
    /**
     * Konstruktor serwisu tagów.
     *
     * @param TagRepository          $tags repozytorium
     *                                     tagów
     * @param EntityManagerInterface $em   menedżer
     *                                     encji
     */
    public function __construct(private readonly TagRepository $tags, private readonly EntityManagerInterface $em)
    {
    }

    /**
     * Zapisuje (persist + flush) tag.
     *
     * @param Tag $tag encja tagu
     *
     * @return void
     */
    public function save(Tag $tag): void
    {
        $this->em->persist($tag);
        $this->em->flush();
    }

    /**
     * Usuwa tag.
     *
     * @param Tag $tag encja tagu
     *
     * @return void
     */
    public function delete(Tag $tag): void
    {
        $this->em->remove($tag);
        $this->em->flush();
    }
}
