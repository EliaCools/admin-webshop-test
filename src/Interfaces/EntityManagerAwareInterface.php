<?php

namespace App\Interfaces;

use Doctrine\ORM\EntityManager;

interface EntityManagerAwareInterface
{
    public function setEntityManager(EntityManager $entityManager = null);
}
