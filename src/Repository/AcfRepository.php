<?php

namespace App\Repository;

use App\Entity\Acf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Acf|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acf|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acf[]    findAll()
 * @method Acf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acf::class);
    }

    // /**
    //  * @return Acf[] Returns an array of Acf objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Acf
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
