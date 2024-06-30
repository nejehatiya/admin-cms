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
    public function findByName($nom,$id_current){
        $query =  $this->createQueryBuilder('m')
        ->andWhere('m.name_post_type = :name_template')
        ->setParameter('name_template', $nom);
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
        $query =  $this->createQueryBuilder('m')
        ->andWhere('m.slug_post_type = :slug_post_type')
        ->setParameter('slug_post_type', $nom);
        if((int)$id_current){
            $query = $query->andWhere('m.id != :id_current')
            ->setParameter('id_current', $id_current);
        }
        
        $query = $query->getQuery()
        ->getOneOrNullResult()
        ;
        return $query;
    }
    // find list postype in side bar menu
    public function findSidebarList(){
        $query =  $this->createQueryBuilder('m')
        ->select('m.id,m.slug_post_type,m.name_post_type,m.icone_dasbord')
        ->andWhere('m.displayInSitemap = :displayInSitemap')
        ->setParameter('displayInSitemap', 1);
        $query = $query->getQuery()
        ->getResult();
        return $query;
    }

}
