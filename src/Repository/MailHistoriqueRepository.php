<?php

namespace App\Repository;

use App\Entity\MailHistorique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MailHistorique|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailHistorique|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailHistorique[]    findAll()
 * @method MailHistorique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailHistoriqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailHistorique::class);
    }

    // /**
    //  * @return MailHistorique[] Returns an array of MailHistorique objects
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
    public function findOneBySomeField($value): ?MailHistorique
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
