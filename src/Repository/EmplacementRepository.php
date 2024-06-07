<?php

namespace App\Repository;

use App\Entity\Emplacement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Emplacement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emplacement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emplacement[]    findAll()
 * @method Emplacement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmplacementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emplacement::class);
    }

    // /**
    //  * @return Emplacement[] Returns an array of Emplacement objects
    //  */
    
    public function findAllOrder()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Emplacement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByName($nom,$id_current){
        $query =  $this->createQueryBuilder('m')
        ->andWhere('m.key_emplacement = :name')
        ->setParameter('name', $nom);
        if((int)$id_current){
            $query = $query->andWhere('m.id != :id_current')
            ->setParameter('id_current', $id_current);
        }
        $query = $query->getQuery()
        ->getOneOrNullResult()
        ;
        return $query;
    }
}
