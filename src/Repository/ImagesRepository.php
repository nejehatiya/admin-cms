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

}
