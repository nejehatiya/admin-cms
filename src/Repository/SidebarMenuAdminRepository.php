<?php

namespace App\Repository;

use App\Entity\SidebarMenuAdmin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SidebarMenuAdmin|null find($id, $lockMode = null, $lockVersion = null)
 * @method SidebarMenuAdmin|null findOneBy(array $criteria, array $orderBy = null)
 * @method SidebarMenuAdmin[]    findAll()
 * @method SidebarMenuAdmin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SidebarMenuAdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SidebarMenuAdmin::class);
    }

    // /**
    //  * @return SidebarMenuAdmin[] Returns an array of SidebarMenuAdmin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SidebarMenuAdmin
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
