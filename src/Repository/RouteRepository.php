<?php

namespace App\Repository;

use App\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
/**
 * @method Route|null find($id, $lockMode = null, $lockVersion = null)
 * @method Route|null findOneBy(array $criteria, array $orderBy = null)
 * @method Route[]    findAll()
 * @method Route[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    /**
     * @return Route[] Returns an array of Route objects
     */
    public function finAll_with_joining_role()
    {
        return $this->createQueryBuilder('route')
            ->select('route', 'role')
            ->innerJoin('route.roles', 'role')

            ->getQuery()
            ->getResult();
    }

    public function findOneByModuleMethodeRole($method, $module, $role_id): ?Route
    {
        return $this->createQueryBuilder('route')
            ->innerJoin('route.roles', 'role')
            ->andWhere('route.module = :module')
            ->andWhere('route.method = :method')
            ->andWhere('role.id = :role_id')
            ->setParameters(new ArrayCollection([
                new Parameter('role_id', $role_id),
                new Parameter('module', $module),
                new Parameter('method', $method)
            ]))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByNameRole($name, $role_id): ?Route
    {
        return $this->createQueryBuilder('route')
            ->innerJoin('route.roles', 'role')
            ->andWhere('route.name = :name')
            ->andWhere('role.id = :role_id')
            ->setParameters(new ArrayCollection([
                new Parameter('role_id', $role_id),
                new Parameter('name', $name)
            ]))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Route[] Returns an array of Route objects
     */ 
    public function getMethodesByModuleRole($module, $role_id)
    {
        return $this->createQueryBuilder('route')
            ->select('route.method')
            ->distinct()
            ->innerJoin('route.roles', 'role')
            ->andWhere('route.module = :module')
            ->andWhere('role.id = :role_id')
            ->setParameters(new ArrayCollection([
                new Parameter('role_id', $role_id),
                new Parameter('module', $module)
            ]))
            ->orderBy('route.module', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Route[] Returns an array of Route objects
    //  */
    // public function finAll_with_joining_role_id($role_id)
    // {
    //     return $this->createQueryBuilder('route')
    //         ->select('route')
    //         ->innerJoin('route.roles', 'role')
    //         ->andWhere('role.id = :role_id')
    //         ->GroupBy('route.module')

    //         ->setParameter('role_id', $role_id)
    //         ->getQuery()
    //         ->getResult();
    // }

    
}
