<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;

/**
 * Serwis do obsługi logiki biznesowej komentarzy.
 */
final class CommentService implements CommentServiceInterface
{
    /**
     * Konstruktor.
     *
     * @param CommentRepository $comments repozytorium komentarzy
     */
    public function __construct(private readonly CommentRepository $comments)
    {
    }

    /**
     * Zapisuje komentarz.
     *
     * @param Comment $comment encja komentarza
     */
    public function save(Comment $comment): void
    {
        $this->comments->save($comment);
    }

    /**
     * Usuwa komentarz.
     *
     * @param Comment $comment encja komentarza
     */
    public function delete(Comment $comment): void
    {
        $this->comments->delete($comment);
    }
}
