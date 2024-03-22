<?php

namespace App\Repository;

use App\Entity\TemplateMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemplateMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateMenu[]    findAll()
 * @method TemplateMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateMenu::class);
    }

    // /**
    //  * @return TemplateMenu[] Returns an array of TemplateMenu objects
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

    /*
    public function findOneBySomeField($value): ?TemplateMenu
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByName($template_menu,$id_current){
        return $this->createQueryBuilder('t')
        ->andWhere('t.name_template = :name_template')
        ->setParameter('name_template', $template_menu)
        ->andWhere('t.id != :id_current')
        ->setParameter('id_current', $id_current)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
}
