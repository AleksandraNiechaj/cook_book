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
final class CommentService
{
    /**
     * Konstruktor.
     *
     * @param CommentRepository $comments Repozytorium komentarzy.
     */
    public function __construct(private readonly CommentRepository $comments)
    {
    }

    /**
     * Zapisuje komentarz.
     *
     * @param Comment $comment Encja komentarza.
     *
     * @return void
     */
    public function save(Comment $comment): void
    {
        $this->comments->save($comment);
    }

    /**
     * Usuwa komentarz.
     *
     * @param Comment $comment Encja komentarza.
     *
     * @return void
     */
    public function delete(Comment $comment): void
    {
        $this->comments->delete($comment);
    }
}
