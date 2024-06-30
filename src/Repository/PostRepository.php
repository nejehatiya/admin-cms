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


    // get List Posts
    public function getListPosts($post_type,$start,$max_result,$post_status="",$is_count=false,$params=[],$is_parent=false,$id_parent=0,$excluded_id=0){
        $query = $this->createQueryBuilder('p');
        // check if count select
        if($is_count){
            $query = $query->select('count(p.id) as count');
        }
        // set param post_type
        $query = $query->join('p.post_type','pt')
        ->andWhere('pt.slug_post_type = :post_type')
        ->setParameter('post_type', $post_type);
        // eliminer id
        if((int)$excluded_id && $is_parent){
            $query = $query->andWhere('p.id != :id')
            ->setParameter('id', (int)$excluded_id);
        }
        // check post status
        if(strlen($post_status)){
            $query = $query->andWhere('p.post_status = :post_status')
            ->setParameter('post_status', $post_status);
        }
        // check if page parent
        if($is_parent){
            $query = $query->andWhere('p.post_parent = :post_parent')
            ->setParameter('post_parent', $id_parent);
        }
        // add param to serach
        if(!empty($params)){
            foreach($params as $key=>$value){
                if($key=="search_text"){
                    $query = $query->andWhere('p.post_title like :post_title')
                    ->setParameter('post_title', '%'.$value.'%');
                }elseif($key == "collect_filter_list"){
                    foreach($value as $k=>$v){
                        if($v['field'] == "terms"){
                            $query = $query->join('p.terms','t'.$k)
                            ->andWhere('t'.$k.'.id like :id_term'.$k)
                            ->setParameter('id_term'.$k, (int)$v['val']);
                        }elseif($v['field'] == "author"){
                            $query = $query->andWhere('p.author = :author')
                            ->setParameter('author', (int)$v['val']);
                        }
                    }
                }
            }
        }
        if(!$is_count){
            $query = $query->setMaxResults($max_result)->setFirstResult( $start >= 0 ? $start : 0 );
            //var_dump($query->getQuery());exit;
            $query = $query->getQuery()->getResult();
        }else{
            $query = $query->getQuery()->getSingleScalarResult();
        }
        return $query;
    }

    // get list of page parent
    public function getPageByParent($id_parent,$post_type="page"){
        $query = $this->createQueryBuilder('p');
        // set param post_type
        $query = $query->join('p.post_type','pt')
        ->andWhere('pt.slug_post_type = :post_type')
        ->setParameter('post_type', $post_type);
        // eliminer id
        $query = $query->andWhere('p.id != :id')
            ->setParameter('id', (int)$id_parent);
        // check post status
        $query = $query->andWhere('p.post_status = :post_status')
        ->setParameter('post_status', 'Publié');
        // check if page parent
        $query = $query->andWhere('p.post_parent = :post_parent')
        ->setParameter('post_parent', $id_parent);
        $query = $query->getQuery()->getResult();
        return $query;
    }
}
