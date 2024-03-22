<?php

namespace App\Repository;

use App\Entity\Terms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Terms|null find($id, $lockMode = null, $lockVersion = null)
 * @method Terms|null findOneBy(array $criteria, array $orderBy = null)
 * @method Terms[]    findAll()
 * @method Terms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TermsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Terms::class);
    }

    // /**
    //  * @return Terms[] Returns an array of Terms objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Terms
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findTermsParent($id)
    {
        return $this->createQueryBuilder('t')
        ->select('t.name_terms, t.id, t.level, t.parentTerms')
        ->andWhere('t.id_taxonomy = :id')
        ->andWhere('t.is_draft = 0')
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult()
        ;
        
    }

    public function findTermsFils()
    {
        return $this->createQueryBuilder('t')
        ->select('t.name_terms, t.id,  t.description_terms, t.parentTerms, t.name_terms as parent')
        ->andWhere('t.parentTerms > :id')
        ->setParameter('id', 0)
        ->getQuery()
        ->getResult()
        ;
    }

    public function getNameParent()
    {
        
        return $this->createQueryBuilder('t')
        ->select('t.id , t.name_terms')
        ->andWhere('t.parentTerms = :id')
        ->setParameter('id', 0)
        ->getQuery()
        ->getResult()
        ;
    }

    public function findTermsSecondFils($id)
    {
        return $this->createQueryBuilder('t')
        ->select('t.name_terms, t.id,  t.description_terms, t.parentTerms')
        ->andWhere('t.parentTerms = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult()
        ;

       
    }
    /**
     * get Terms Repo getTermRepo
     */
    public function getTermRepo($page_number){
        $query= $this->createQueryBuilder('p')
            ->andWhere('p.parent_migration  != :parent_migration')
            ->setParameter('parent_migration', '0');
        return $query->setFirstResult( $page_number )->setMaxResults(1)->getQuery()->getOneOrNullResult();;
    }
    /**
     * get Post Repo getTermsCountRepo
     */
    public function getTermsCountRepo(){
        $query= $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.parent_migration  != :parent_migration')
            ->setParameter('parent_migration', '0');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function findTermsByTaxonomySlug($taxo_slug, $search, $is_draft = 0)
    {
        return $this->createQueryBuilder('t')
        ->select('t.name_terms, t.id, t.slug_terms')
        ->innerJoin('t.id_taxonomy', 'taxo', 'WITH', 'taxo.slug_taxonomy like :taxo_slug')
        ->andWhere('t.name_terms like :search')
        ->andWhere('t.is_draft =:is_draft')

        ->setParameters(new ArrayCollection([
            new Parameter('taxo_slug', '%'.$taxo_slug.'%'),
            new Parameter('search', '%'.$search.'%'),
            new Parameter('is_draft', $is_draft)
        ]))
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
        ;
    }

    public function getTermsTaxonomyblog()
    {
      
        $result= $this->createQueryBuilder('t')
        ->select('t.name_terms, t.id')
        ->innerJoin('t.id_taxonomy', 'taxo', 'WITH', 'taxo.slug_taxonomy like :taxo_slug')
        ->leftJoin('taxo.Posttype', 'pT', 'WITH', 'pT.slug_post_type like :posttype')
        ->andWhere('t.is_draft =:is_draft')
        ->setParameters(new ArrayCollection([
            new Parameter('taxo_slug', 'etiquette'),
            new Parameter('posttype', 'blog'),            
            new Parameter('is_draft', 0)
        ]))
        ->getQuery()
        ->getResult()
        ;

        return $result;
        
    }

    public function getBlogbyTerms($id)
    {
      
        $result= $this->createQueryBuilder('t')
        ->select(' t')
        ->innerJoin('t.id_post', 'post')
        ->andWhere('t.id =:id')

        ->setParameters(new ArrayCollection([
            new Parameter('id', $id),
        ]))
        ->getQuery()
        ->getResult()
        ;
        return $result;
    }

    public function getPageByTermQuestion() {
        $response = $this->createQueryBuilder('t')
            ->innerJoin('t.id_taxonomy', 'taxo', 'WITH', 'taxo.slug_taxonomy like :taxo_slug')
            ->leftJoin('taxo.Posttype', 'pT', 'WITH', 'pT.slug_post_type like :posttype')
            ->andWhere('t.parentTerms = :id')
            ->setParameters(new ArrayCollection([
                new Parameter('posttype', '%questions%'),
                new Parameter('taxo_slug', '%categorie-questions%'),
                new Parameter('id', 0),
            ]))
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getTermsByParent($id)
    {
        return $this->createQueryBuilder('t')
        ->select('t')
        ->andWhere('t.parentTerms = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult()
        ;
    }
    
    public function getTermsByPostId($id)
    {
      
        $result= $this->createQueryBuilder('t')
        ->select(' t')
        ->innerJoin('t.id_post', 'post')
        ->andWhere('post.id =:id')

        ->setParameters(new ArrayCollection([
            new Parameter('id', $id),
        ]))
        ->getQuery()
        ->getResult()
        ;
        return $result;
    }

}
