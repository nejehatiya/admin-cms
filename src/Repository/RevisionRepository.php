<?php

namespace App\Repository;

use App\Entity\Revision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Revision|null find($id, $lockMode = null, $lockVersion = null)
 * @method Revision|null findOneBy(array $criteria, array $orderBy = null)
 * @method Revision[]    findAll()
 * @method Revision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RevisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Revision::class);
    }

    // /**
    //  * @return Revision[] Returns an array of Revision objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Revision
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * count revision by post id
     */
    public function countRevision($post_id){
        $query= $this->createQueryBuilder('r')
            ->select('count(r.id)');
        $query=$query->leftJoin('r.post','p')->andWhere('p.id like :post_id')
        ->setParameter('post_id', $post_id);
        return $query->getQuery()->getSingleScalarResult();
    }

    //
    public function findById($post_id,$first_result=0,$max_result=100){
        $query= $this->createQueryBuilder('r');
        $query = $query->leftJoin('r.post','p')
        ->andWhere('p.id like :post_id')
        ->setParameter('post_id', $post_id);
        $query = $query->orderBy('r.date', 'DESC')
        ->setMaxResults(100)
        ->setFirstResult($first_result);
        return $query->getQuery()->getResult();
    }
}
