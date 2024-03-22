<?php

namespace App\Repository;

use App\Entity\Cities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cities|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cities|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cities[]    findAll()
 * @method Cities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cities::class);
    }

    /**
     * @return Cities[] Returns an array of Regions objects
    */
    public function getCities(int $dep_id, $search="") {
        $response = $this->createQueryBuilder('c')
                ->innerJoin('c.departments', 'd')
                ->andWhere('d.id = :dep_id') 
                ->andWhere('c.name like :search') 
                ->setParameters(new ArrayCollection([
                    new Parameter('dep_id', $dep_id),
                    new Parameter('search', '%'. $search .'%')
                ]))
                ->orderBy('c.id', 'ASC')
                ->getQuery()
                ->getResult();
        return $response;
    }
    /**
     * @return Cities[] Returns an array of Regions objects
    */
    public function getCitiesV2(int $dep_id) {
        $response = $this->createQueryBuilder('c')
                ->leftJoin('c.departments', 'd')
                ->andWhere('d.id = :dep_id')
                ->setParameter('dep_id', $dep_id)
                ->getQuery()
                ->getResult();
        return $response;
    }
    /**
     * @return Cities[] Returns an array of Regions objects
    */
    public function getCitiesbyname($start, $limit, $search="") {
    
        $response = $this->createQueryBuilder('c')
            ->andWhere('c.postalCode like :search or c.name like :search') 
            ->setParameters(new ArrayCollection([
                new Parameter('search', $search .'%'),
            ]))
            ->orderBy('c.id', 'ASC')
            ->setFirstResult(( $start-1)* $limit )
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        return $response;
    }

    
    // /**
    //  * @return Cities[] Returns an array of Cities objects
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
    public function findOneBySomeField($value): ?Cities
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
