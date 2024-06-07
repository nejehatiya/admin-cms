<?php

namespace App\Repository;

use App\Entity\Taxonomy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Taxonomy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taxonomy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taxonomy[]    findAll()
 * @method Taxonomy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxonomyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taxonomy::class);
    }

    // /**
    //  * @return Taxonomy[] Returns an array of Taxonomy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

   
    // public function findOneById($value): ?Taxonomy
    // {
    //     return $this->createQueryBuilder('t')
    //         ->andWhere('t.id = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
    

    public function findByName($nom,$id_current){
        $query =  $this->createQueryBuilder('m')
        ->andWhere('m.name_taxonomy = :name')
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

    public function findBySlug($nom,$id_current){
        $query =  $this->createQueryBuilder('m')
        ->andWhere('m.slug_taxonomy = :slug')
        ->setParameter('slug', $nom);
        if((int)$id_current){
            $query = $query->andWhere('m.id != :id_current')
            ->setParameter('id_current', $id_current);
        }
        $query = $query->getQuery()
        ->getOneOrNullResult()
        ;
        return $query;
    }

    // get all taxonomy  related to post type
    public function findByPostType($post_type){
        $query = $this->createQueryBuilder('m')
        ->join('m.Posttype','pt')
        ->andWhere('pt.slug_post_type = :slug_post_type')
        ->setParameter('slug_post_type', $post_type);
        
        $query = $query->getQuery()
        ->getResult()
        ;
        return $query;
    }
    
}
