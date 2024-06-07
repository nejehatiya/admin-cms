<?php

namespace App\Repository;

use App\Entity\Terms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Terms|null find($id, $lockMode = null, $lockVersion = null)
 * @method Terms|null findOneBy(array $criteria, array $orderBy = null)
 * @method Terms[]    findAll()
 * @method Terms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TermsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Terms::class);
    }

    // /**
    //  * @return Terms[] Returns an array of Terms objects
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
    public function findOneBySomeField($value): ?Terms
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByName($nom,$id_current){
        $query = $this->createQueryBuilder('m')
        ->andWhere('m.name_terms = :name')
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
        $query = $this->createQueryBuilder('m')
        ->andWhere('m.slug_terms = :slug')
        ->setParameter('slug', $nom);
        if((int)$id_current){
            $query = $query->andWhere('m.id != :id_current')
            ->setParameter('id_current', (int)$id_current);
        }
        $query = $query->getQuery()->getOneOrNullResult();
        return $query;
    }

    // load items by taxonomy
    public function findByTaxonomy($taxonomy){
        $query = $this->createQueryBuilder('m')
        ->join('m.id_taxonomy ','t')
        ->andWhere('t.slug_taxonomy = :taxonomy')
        ->setParameter('taxonomy', $taxonomy);
        $query = $query->setFirstResult(0)->setMaxResults(20)->getQuery()->getResult();
        return $query;
    }
}
