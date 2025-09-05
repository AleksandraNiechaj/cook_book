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

/**
 * Interfejs serwisu do obsługi tagów.
 */
interface TagServiceInterface
{
    /**
     * Zapisuje tag (persist + flush).
     *
     * @param Tag $tag encja tagu
     *
     * @return void
     */
    public function save(Tag $tag): void;

    /**
     * Usuwa tag.
     *
     * @param Tag $tag encja tagu
     *
     * @return void
     */
    public function delete(Tag $tag): void;
}
