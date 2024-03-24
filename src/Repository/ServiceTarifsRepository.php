<?php

namespace App\Repository;

use App\Entity\ServiceTarifs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceTarifs|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceTarifs|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceTarifs[]    findAll()
 * @method ServiceTarifs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceTarifsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceTarifs::class);
    }

    // /**
    //  * @return ServiceTarifs[] Returns an array of ServiceTarifs objects
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
    public function findOneBySomeField($value): ?ServiceTarifs
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */  public function findByName($Titre,$id_current){
        return $this->createQueryBuilder('e')
        ->andWhere('e.Titre = :titre')
        ->setParameter('titre', $Titre)
        ->andWhere('e.id != :id_current')
        ->setParameter('id_current', $id_current)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
}