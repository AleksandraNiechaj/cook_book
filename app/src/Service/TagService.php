<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

final class TagService implements TagServiceInterface
{
    public function __construct(
        private readonly TagRepository $tags,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function save(Tag $tag): void
    {
        $this->em->persist($tag);
        $this->em->flush();
    }

    public function delete(Tag $tag): void
    {
        $this->em->remove($tag);
        $this->em->flush();
    }
}
