<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class PaginationService
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * pagination list pos types
     */
}
