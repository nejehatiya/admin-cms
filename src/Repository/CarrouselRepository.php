<?php

namespace App\Repository;

use App\Entity\Carrousel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Carrousel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carrousel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carrousel[]    findAll()
 * @method Carrousel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarrouselRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carrousel::class);
    }

    public function findAllActive(){
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status')
            ->setParameter('status', true)
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return Carrousel[] Returns an array of Carrousel objects
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
    public function findOneBySomeField($value): ?Carrousel
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByName($title_carrousel,$id_current){
        return $this->createQueryBuilder('e')
        ->andWhere('e.title_carrousel = :titre')
        ->setParameter('titre', $title_carrousel)
        ->andWhere('e.id != :id_current')
        ->setParameter('id_current', $id_current)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
}
