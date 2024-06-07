<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }
    /**
     *  check post by parent,slug
     * 
     */
    public function checkSlugExist($slug,$parent_id,$id=null){
        $query = $this->createQueryBuilder('p')
        ->andWhere('p.post_name = :slug')
        ->setParameter('slug', $slug)
        ->andWhere('p.post_parent = :parent_id')
        ->setParameter('parent_id', $parent_id);
        if((int)$id){
            $query = $query->andWhere('p.id != :id')
            ->setParameter('id', $id);
        }
        $query = $query->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
        return $query;
    }

    // get page parent list
    public function getPageParent($id_parent){
        $query = $this->createQueryBuilder('p')
        ->andWhere('p.post_parent = :id_parent')
        ->setParameter('id_parent', $id_parent);
        $query = $query->setMaxResults(100)
        ->getQuery()
        ->getResult();
        return $query;
    }
}
