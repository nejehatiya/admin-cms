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
    

    public function findTaxonomyParent()
    {
       
        return $this->getEntityManager()
            ->createQuery(
                'SELECT t.name_taxonomy, t.id
                FROM App\Entity\Taxonomy t  '
            )
            ->getResult();
    }

    public function findAllTaxo()
    {
      
       
        return $this->getEntityManager()
            ->createQuery(
                'SELECT t.name_taxonomy,t.id, t.description_taxonomy, t.parent_taxonomy
                FROM App\Entity\Taxonomy t 
                '
            )
            ->getResult();
    }
}
