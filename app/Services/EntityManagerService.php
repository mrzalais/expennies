<?php

declare(strict_types = 1);

namespace App\Services;

use Doctrine\ORM\EntityManager;

class EntityManagerService
{
    public function __construct(protected readonly EntityManager $entityManager)
    {
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
