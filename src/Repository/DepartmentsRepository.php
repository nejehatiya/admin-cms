<?php

namespace App\Repository;

use App\Entity\Departments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Departments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departments[]    findAll()
 * @method Departments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departments::class);
    }

    /**
     * @return Departments[] Returns an array of Regions objects
     */
    public function getDepatements($name, int $region_id)
    {
        $response = $this->createQueryBuilder('d')
            ->innerJoin('d.regions', 'dr')
            ->andWhere('dr.id = :region_id')
            ->andWhere('d.name like :name')
            ->setParameters(new ArrayCollection([
                new Parameter('region_id', $region_id),
                new Parameter('name', '%' . $name . '%'),
            ]))
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getDepatementsByRegions(int $region_id)
    {
        $response = $this->createQueryBuilder('d')
            ->innerJoin('d.regions', 'dr')
            ->andWhere('dr.id = :region_id')
            ->setParameters(new ArrayCollection([
                new Parameter('region_id', $region_id),
            ]))
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
        return $response;
    }
    // /**
    //  * @return Departments[] Returns an array of Departments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Departments
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
