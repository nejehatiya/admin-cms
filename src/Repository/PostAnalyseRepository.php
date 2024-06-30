<?php

namespace App\Repository;

use App\Entity\PostAnalyse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostAnalyse>
 *
 * @method PostAnalyse|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostAnalyse|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostAnalyse[]    findAll()
 * @method PostAnalyse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostAnalyseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostAnalyse::class);
    }

//    /**
//     * @return PostAnalyse[] Returns an array of PostAnalyse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PostAnalyse
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
