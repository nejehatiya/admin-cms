<?php

namespace App\Repository;

use App\Entity\Commentaire;
use App\Entity\Post;
use App\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }


  
    
    public function findAllComment($search = "")
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.email_comment, c.first_name, c.last_name, c.comment_content, c.note_comment, c.status')
            // ->innerJoin('c.id_post', 'post')
            ->Where('c.status = :status')
            ->andWhere('c.email_comment like :search OR c.first_name like :search OR c.last_name like :search OR c.comment_content like :search ')
            ->setParameters(new ArrayCollection([
                new Parameter('status', true),
                new Parameter('search', '%'.$search.'%'),
            ]))
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
       
        // return $this->getEntityManager()
        //     ->createQuery(
        //         'SELECT p.post_title,c.id, c.first_name, c.last_name, c.email_comment, c.comment_content, c.date_upd, c.status_comment
        //         FROM App\Entity\Post p  , App\Entity\Commentaire c 
        //         WHERE p.id = c.id_post '
        //     )
        //     ->getResult();
    }
    public function findAllCommentsByPostId($post_id)
    {
        return $this->createQueryBuilder('c')
            ->select(['c.id, p.id AS post_id  , c.email_comment, c.first_name, c.last_name, c.comment_content, c.note_comment, c.status','c.date_add'])
            ->innerJoin('c.id_post', 'p','WITH','p.id = :post_id')
            ->andWhere('c.status = :status')
            ->setParameter('status', false)
            ->setParameter('post_id', $post_id)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
       
       /* $query= $this->getEntityManager()->createQuery(
                'SELECT c.id,c.id_post_id,c.id_user_id, c.email_comment, c.first_name, c.last_name, c.comment_content, c.note_comment, c.status,c.date_add
                FROM  App\Entity\Commentaire c 
                INNER JOIN  App\Entity\Post p 
                ON c.id_post=p.id
                INNER JOIN App\Entity\User u 
                on c.id_user_id=u.id
                WHERE c.id_post=29
                ORDER BY c.id
                 WHERE p.id = c.id_post '
            );
            return $query->getResult();*/
    }
    
    // /**
    //  * @return Commentaire[] Returns an array of Commentaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commentaire
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    // get count comment
    public function countComments($id=null){
        $query = $this->createQueryBuilder('c')
        ->select('count(c.id) as count');
        if($id){
            $query = $query->leftJoin('c.id_post','p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id);
        }
        $query = $query->andWhere('c.status = :val')
        ->setParameter('val', 1)
        ->getQuery()
        ->getSingleScalarResult();
        return $query;
    }
    // get sum note comment
    public function sumNotes($id=null){
        $query = $this->createQueryBuilder('c')
        ->select('SUM(c.note_comment) as sum_note');
        if($id){
            $query = $query->leftJoin('c.id_post','p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id);
        }
        $query = $query->andWhere('c.status = :val')
        ->setParameter('val', 1)
        ->getQuery()
        ->getSingleScalarResult();
        return $query;
    }
}
