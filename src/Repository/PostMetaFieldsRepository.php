<?php

namespace App\Repository;

use App\Entity\PostMetaFields;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostMetaFields>
 *
 * @method PostMetaFields|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostMetaFields|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostMetaFields[]    findAll()
 * @method PostMetaFields[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostMetaFieldsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostMetaFields::class);
    }

    //    /**
    //     * @return PostMetaFields[] Returns an array of PostMetaFields objects
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

    //    public function findOneBySomeField($value): ?PostMetaFields
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByName($nom,$id_current){
        $query = $this->createQueryBuilder('m')
        ->andWhere('m.name = :name')
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
    // get all template forms for menues
    public function findByMenuBuilder(){
        $query = $this->createQueryBuilder('m')
        ->join('m.post_type','p')
        ->andWhere('p.slug_post_type = :slug_post_type')
        ->setParameter('slug_post_type', "menu-builder");
        $query = $query->getQuery()
        ->getResult()
        ;
        return $query;
    }

    // get all post meta fields  for menues
    public function findByPostType($post_type){
        $query = $this->createQueryBuilder('m')
        ->join('m.post_type','p')
        ->andWhere('p.slug_post_type = :slug_post_type')
        ->setParameter('slug_post_type', $post_type);
        $query = $query->getQuery()
        ->getResult()
        ;
        return $query;
    }
}
