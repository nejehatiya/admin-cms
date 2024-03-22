<?php

namespace App\Repository;

use App\Entity\PostMeta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Parameter;

/**
 * @method PostMeta|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostMeta|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostMeta[]    findAll()
 * @method PostMeta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostMetaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostMeta::class);
    }

    /**
     * @return PostMeta[] Returns an array of PostMeta objects
     */

    public function findByMetakeyDistinct($meta_key)
    {
        return $this->createQueryBuilder('p')
            ->select('p.meta_key, p.meta_value')
            ->where('p.meta_key = :meta_key')
            ->setParameter('meta_key', $meta_key)
            ->distinct()
            ->getQuery()
            ->getResult();;
    }

    public function findOneByMetakey($meta_key, $post_id): ?PostMeta
    {
        return $this->createQueryBuilder('pm')
            ->andWhere('pm.meta_key = :meta_key')
            ->innerJoin('pm.id_post', 'p')
            ->andWhere('p.id= :post_id')

            ->setParameters(new ArrayCollection([
                new Parameter('meta_key', $meta_key),
                new Parameter('post_id', $post_id)
            ]))
        
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    public function findOneByExcept($meta_key, $post_id): ?PostMeta
    {
        return $this->createQueryBuilder('pm')
        ->andWhere('pm.meta_key = :meta_key')
        ->innerJoin('pm.id_post', 'p')
        ->andWhere('p.id != :post_id')

        ->setParameters(new ArrayCollection([
            new Parameter('meta_key', $meta_key),
            new Parameter('post_id', $post_id)
        ]))
        ->getQuery()
        ->getOneOrNullResult()
    ;
    }
}
