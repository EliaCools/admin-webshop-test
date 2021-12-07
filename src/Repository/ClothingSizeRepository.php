<?php

namespace App\Repository;

use App\Entity\ClothingSize;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClothingSize|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClothingSize|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClothingSize[]    findAll()
 * @method ClothingSize[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClothingSizeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClothingSize::class);
    }

    // /**
    //  * @return ClothingSize[] Returns an array of ClothingSize objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClothingSize
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
