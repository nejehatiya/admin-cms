<?php

namespace App\Repository;

use App\Entity\ModelesPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModelesPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelesPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelesPost[]    findAll()
 * @method ModelesPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelesPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModelesPost::class);
    }

    public function findAllActive(){
        return $this->createQueryBuilder('c')
            ->Where('c.status_modele = :status')
            ->setParameter('status', true)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return ModelesPost[] Returns an array of ModelesPost objects
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
    public function findOneBySomeField($value): ?ModelesPost
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByName($nom_de_modele,$id_current){
        return $this->createQueryBuilder('m')
        ->andWhere('m.name_modele = :name_template')
        ->setParameter('name_template', $nom_de_modele)
        ->andWhere('m.id != :id_current')
        ->setParameter('id_current', $id_current)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
    /**
     * find by tarifs
     */
    public function findByTarifs(){
        return $this->createQueryBuilder('m')
        
        ->andWhere('m.name_modele like :name_modele')
        ->setParameter('name_modele', "%Tarifs%")
        ->getQuery()
        ->getResult()
        ;
    }
}
