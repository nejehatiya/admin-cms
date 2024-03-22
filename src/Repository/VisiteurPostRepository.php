<?php

namespace App\Repository;

use App\Entity\VisiteurPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VisiteurPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method VisiteurPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method VisiteurPost[]    findAll()
 * @method VisiteurPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisiteurPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VisiteurPost::class);
    }

    // /**
    //  * @return VisiteurPost[] Returns an array of VisiteurPost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VisiteurPost
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
