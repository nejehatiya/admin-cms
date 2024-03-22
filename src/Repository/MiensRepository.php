<?php

namespace App\Repository;

use App\Entity\Miens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Miens|null find($id, $lockMode = null, $lockVersion = null)
 * @method Miens|null findOneBy(array $criteria, array $orderBy = null)
 * @method Miens[]    findAll()
 * @method Miens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MiensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Miens::class);
    }

    // /**
    //  * @return Miens[] Returns an array of Miens objects
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
    public function findOneBySomeField($value): ?Miens
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getLastMiens($post_id){
        return $this->createQueryBuilder('m')
            ->leftJoin('m.post','p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $post_id)
            ->orderBy('m.date_modif', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }
}
