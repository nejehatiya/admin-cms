<?php

namespace App\Repository;

use App\Entity\MigrationImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MigrationImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MigrationImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MigrationImage[]    findAll()
 * @method MigrationImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MigrationImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MigrationImage::class);
    }

    // /**
    //  * @return MigrationImage[] Returns an array of MigrationImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MigrationImage
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
