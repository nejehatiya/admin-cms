<?php

namespace App\Repository;

use App\Entity\PostType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostType[]    findAll()
 * @method PostType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostType::class);
    }

    public function add(PostType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function update(PostType $entity): void
    {
        if (empty($entity->getId()))
            $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(PostType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    // /**
    //  * @return PostType[] Returns an array of PostType objects
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
    public function findOneBySomeField($value): ?PostType
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findposttype()
    {

        return $this->getEntityManager()
            ->createQuery(
                'SELECT pt.name_post_type, pt.id
                FROM App\Entity\PostType pt  '
            )
            ->getResult();
    }




    public function findByPostTypeField()
    {
        //   return $this->createQueryBuilder('p')
        //       ->andWhere('p.type_post_type in (:val)')
        //       ->setParameter('val', ['blog','page','post'])
        //       ->getQuery()
        //       ->getResult()
        //   ;

        return $this->getEntityManager()
            ->createQuery(
                'SELECT pt.type_post_type
            FROM App\Entity\PostType pt where pt.type_post_type in (:val)'
            )->setParameter('val', ['blog', 'page', 'post'])
            ->getResult();
    }

    public function findOtherPostTypeField()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.type_post_type not in (:val)')
            ->andWhere('p.is_draft = 0')
            /*  ->setParameter('val', ['page','post'])*/
            ->setParameter('val', ['page', 'post', 'accueil'])
            ->getQuery()
            ->getResult()
        ;


    }

    public function findPostTypeAuteur()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.type_post_type  in (:val)')
            ->setParameter('val', 'page')
            ->getQuery()
            ->getResult()
        ;


    }





}
