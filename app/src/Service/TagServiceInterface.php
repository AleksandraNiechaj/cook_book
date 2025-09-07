<?php

declare(strict_types=1);

/**
 * Interfejs serwisu do obsługi tagów.
 */

namespace App\Service;

use App\Entity\Tag;

/**
 * Interfejs definiujący kontrakt serwisu dla operacji na tagach.
 */
interface TagServiceInterface
{
    /**
     * Zapisuje tag (persist + flush).
     *
     * @param Tag $tag encja tagu
     */
    public function save(Tag $tag): void;

    /**
     * Usuwa tag.
     *
     * @param Tag $tag encja tagu
     */
    public function delete(Tag $tag): void;
}
