<?php

namespace App\Repository;

use App\Entity\PostEditibale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostEditibale|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostEditibale|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostEditibale[]    findAll()
 * @method PostEditibale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostEditibaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostEditibale::class);
    }

    // /**
    //  * @return PostEditibale[] Returns an array of PostEditibale objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostEditibale
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
