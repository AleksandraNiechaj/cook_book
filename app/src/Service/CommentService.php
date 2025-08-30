<?php
declare(strict_types=1);

/**
 * Cook Book — educational project
 * (c) 2025 Aleksandra Niechaj
 */

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;

final class CommentService
{
    public function __construct(private CommentRepository $comments) {}

    /** Zapis komentarza. */
    public function save(Comment $comment): void
    {
        $this->comments->save($comment);
    }

    /** Usunięcie komentarza. */
    public function delete(Comment $comment): void
    {
        $this->comments->delete($comment);
    }
}
