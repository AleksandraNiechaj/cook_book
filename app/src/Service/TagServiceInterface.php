<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Tag;

interface TagServiceInterface
{
    /**
     * Zapisuje (persist+flush) tag.
     */
    public function save(Tag $tag): void;

    /**
     * Usuwa tag.
     */
    public function delete(Tag $tag): void;
}
