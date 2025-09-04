<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Comment;

interface CommentServiceInterface
{
    /**
     * Zapisuje komentarz.
     */
    public function save(Comment $comment): void;

    /**
     * Usuwa komentarz.
     */
    public function delete(Comment $comment): void;
}
