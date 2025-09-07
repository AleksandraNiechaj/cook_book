<?php

declare(strict_types=1);

/**
 * Interfejs serwisu do obsługi logiki komentarzy.
 */

namespace App\Service;

use App\Entity\Comment;

/**
 * Interfejs serwisu do obsługi logiki komentarzy.
 */
interface CommentServiceInterface
{
    /**
     * Zapisuje komentarz.
     *
     * @param Comment $comment encja komentarza
     */
    public function save(Comment $comment): void;

    /**
     * Usuwa komentarz.
     *
     * @param Comment $comment encja komentarza
     */
    public function delete(Comment $comment): void;
}
