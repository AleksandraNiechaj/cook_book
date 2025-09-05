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
     *
     * @return void
     */
    public function save(Comment $comment): void;

    /**
     * Usuwa komentarz.
     *
     * @param Comment $comment encja komentarza
     *
     * @return void
     */
    public function delete(Comment $comment): void;
}
