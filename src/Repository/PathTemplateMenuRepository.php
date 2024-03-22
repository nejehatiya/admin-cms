<?php

namespace App\Repository;

use App\Entity\PathTemplateMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PathTemplateMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method PathTemplateMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method PathTemplateMenu[]    findAll()
 * @method PathTemplateMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PathTemplateMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PathTemplateMenu::class);
    }

    // /**
    //  * @return PathTemplateMenu[] Returns an array of PathTemplateMenu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PathTemplateMenu
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByName($path_menu,$id_current){
        return $this->createQueryBuilder('e')
        ->andWhere('e.path_menu = :path_menu')
        ->setParameter('path_menu', $path_menu)
        ->andWhere('e.id != :id_current')
        ->setParameter('id_current', $id_current)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
}
