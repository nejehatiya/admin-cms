<?php

namespace App\Repository;

use App\Entity\ModelesContentDefault;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModelesContentDefault>
 *
 * @method ModelesContentDefault|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelesContentDefault|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelesContentDefault[]    findAll()
 * @method ModelesContentDefault[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelesContentDefaultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModelesContentDefault::class);
    }

    //    /**
    //     * @return ModelesContentDefault[] Returns an array of ModelesContentDefault objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ModelesContentDefault
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    // get default content by user
    public function findContentDefault($id_model,$id_user){
        return $this->createQueryBuilder('m')
        ->join('m.modele_id','mm')
        ->andWhere('mm.id = :id')
        ->setParameter('id', $id_model)
        ->andWhere('m.user = :val')
        ->setParameter('val', $id_user)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();   
    }
}
