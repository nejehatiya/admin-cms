<?php

namespace App\Repository;

use App\Entity\CategoryCarrousel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryCarrousel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryCarrousel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryCarrousel[]    findAll()
 * @method CategoryCarrousel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryCarrouselRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryCarrousel::class);
    }

    // /**
    //  * @return CategoryCarrousel[] Returns an array of CategoryCarrousel objects
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
    public function findOneBySomeField($value): ?CategoryCarrousel
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByName($name_category,$id_current){
        return $this->createQueryBuilder('e')
        ->andWhere('e.name_category = :name_category')
        ->setParameter('name_category', $name_category)
        ->andWhere('e.id != :id_current')
        ->setParameter('id_current', $id_current)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
    public function findCategoryCarrousel($id)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.carousel = :val')
            ->setParameter('val', $id)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
