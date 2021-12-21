<?php

namespace App\Factory;

use App\Interfaces\EntityManagerAwareInterface;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\MigrationFactory;
use Doctrine\ORM\EntityManager;


class MigrationsFactoryDecorator implements MigrationFactory
{

    private $migrationFactory;
    private EntityManager $entityManager;

    public function __construct(MigrationFactory $migrationFactory, EntityManager $entityManager)
    {
        $this->migrationFactory = $migrationFactory;
        $this->entityManager = $entityManager;
    }

    public function createVersion(string $migrationClassName): AbstractMigration
    {
        $instance = $this->migrationFactory->createVersion($migrationClassName);
        if($instance instanceof EntityManagerAwareInterface)
        $instance->setEntityManager($this->entityManager);
        return $instance;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager): void
    {
        $this->entityManager = $entityManager;
    }
}
