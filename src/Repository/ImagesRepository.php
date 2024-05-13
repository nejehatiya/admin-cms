<?php

namespace App\Repository;

use App\Entity\Images;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Images|null find($id, $lockMode = null, $lockVersion = null)
 * @method Images|null findOneBy(array $criteria, array $orderBy = null)
 * @method Images[]    findAll()
 * @method Images[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Images::class);
    }

    // /**
    //  * @return Images[] Returns an array of Images objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Images
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * images media list
     */
    public function imagesList($start,$max_resultat){
        return $this->createQueryBuilder('i')
            ->orderBy('i.date_add', 'DESC')
            ->setFirstResult( $start )
            ->setMaxResults($max_resultat)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * count images media list
     */
    public function imagesListCount($media_search=""){
        $query= $this->createQueryBuilder('i')
            ->select('count(i.id)');
        if(strlen($media_search)){
            $query=$query->andWhere('i.name_image like :name_image_val')
            ->setParameter('name_image_val', '%'.$media_search.'%')
            ->orWhere('i.alt_image like :alt_image_val')
            ->setParameter('alt_image_val', '%'.$media_search.'%')
            ->orWhere('i.url_image like :url_image_val')
            ->setParameter('url_image_val', '%'.$media_search.'%');
        }
        return $query->getQuery()->getSingleScalarResult();
    }
    /**
     * count images media list
     */
    public function imagesGetAlternateText($media_search=""){
        $query= $this->createQueryBuilder('i')
            ->select('i.alt_image')
            ->Where('i.url_image like :url_image_val')
            ->setParameter('url_image_val', $media_search);
        return $query->getQuery()->getOneOrNullResult();
    }
    /*
    * images media search
    */
    public function mediaSearch($start,$max_resultat,$media_search=""){
        $query =  $this->createQueryBuilder('i')
            ->orderBy('i.date_add', 'DESC');
        if(strlen($media_search)){
            $query=$query->andWhere('i.name_image like :name_image_val')
            ->setParameter('name_image_val', '%'.$media_search.'%')
            ->orWhere('i.alt_image like :alt_image_val')
            ->setParameter('alt_image_val', '%'.$media_search.'%')
            ->orWhere('i.url_image like :url_image_val')
            ->setParameter('url_image_val', '%'.$media_search.'%');
        }
        $query=$query->setFirstResult( $start )
            ->setMaxResults($max_resultat)
            ->getQuery();
        $query=$query->getResult();
        return $query;
    }

    public function findImages($index){
        $query = $this->createQueryBuilder('i')
            ->orderBy('i.date_add', 'DESC')
            ->setFirstResult( $index )
            ->setMaxResults(1)
            ->getQuery();
            return $query = $query->getOneOrNullResult();
    }


    // select date month
    public function getDateMonth(){
        $query = $this->createQueryBuilder('i')
            ->select("DISTINCT  DATE_FORMAT(i.date_update, '%M %Y') AS formatted_date")
            ->addSelect("DATE_FORMAT(i.date_update, '%Y-%m') AS formatted_date_key")
            ->getQuery();
        return $query = $query->getResult();
    }
    //get List Images avec search
    public function getListImages($page,$params=array(),$limit=50){
        $query =  $this->createQueryBuilder('i')
            ->orderBy('i.date_update', 'DESC');
        // add  date to search
        if(array_key_exists('date',$params) && strlen($params['date']) && $params['date']!="all"){
            $date_start_devis = date($params['date'].'-01');
            $date_end_devis = date($params['date'].'-t');
            $query=$query->andWhere('i.date_update >= :date')
            ->setParameter('date', $date_start_devis)
            ->andWhere('i.date_update <= :date_end')
            ->setParameter('date_end', $date_end_devis);
        }
        // add  type to search
        $mime = array(
            'image'=>['image/jpeg','image/jpg','image/png','image/gif','image/svg+xml'],
            'pdf'=>['application/pdf'],
            'video'=>['video/mp4'],
        );

        if(array_key_exists('mime_type',$params) && strlen($params['mime_type']) && array_key_exists($params['mime_type'],$mime )){
            $query=$query->andWhere('i.mime_type in (:mime_type)')
            ->setParameter('mime_type', $mime[$params['mime_type']]);
        }
        // add unattached image
        if(array_key_exists('mime_type',$params) && strlen($params['mime_type']) && $params['mime_type'] == "unattached" ){
            $query=$query->leftJoin('i.id_post', 'p')
            ->andWhere('p.id IS NULL');
        }
        // add  search by name_image | alt_image | url_image
        if(array_key_exists('search',$params) && strlen($params['search'])){
            $query =  $query->andWhere($query->expr()->orX(
                $query->expr()->like('i.name_image', ':search'),
                $query->expr()->like('i.alt_image', ':search'),
                $query->expr()->like('i.url_image', ':search')
                
            ))->setParameter('search', '%'.$params['search'].'%');
        }

        $query = $query->setFirstResult( ( $page - 1 ) *  $limit)
            ->setMaxResults($limit)
            ->getQuery();
        $query = $query->getResult();
        return $query;
    }
}
