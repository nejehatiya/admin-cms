<?php

namespace App\Repository;

use App\Entity\TemplatePage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TemplatePage>
 *
 * @method TemplatePage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplatePage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplatePage[]    findAll()
 * @method TemplatePage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplatePageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplatePage::class);
    }

    //    /**
    //     * @return TemplatePage[] Returns an array of TemplatePage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TemplatePage
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
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

    public function findBySlug($nom,$id_current){
        $query = $this->createQueryBuilder('m')
        ->andWhere('m.slug = :slug')
        ->setParameter('slug', $nom);
        if((int)$id_current){
            $query = $query->andWhere('m.id != :id_current')
            ->setParameter('id_current', $id_current);
        }
        $query = $query->getQuery()
        ->getOneOrNullResult()
        ;
        return $query;
    }

    // get all template page related to post type
    public function findByPostType($post_type){
        $query = $this->createQueryBuilder('m')
        ->join('m.post_type','pt')
        ->andWhere('pt.slug_post_type = :slug_post_type')
        ->setParameter('slug_post_type', $post_type);
        
        $query = $query->getQuery()
        ->getResult()
        ;
        return $query;
    }
}
