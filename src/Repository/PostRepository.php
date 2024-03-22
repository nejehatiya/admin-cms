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
     * @return Post[] Returns an array of Post objects
     */

    public function findByParentField($parent, $post_id, $postType, $postTitle = "")
    {
        if ($post_id != 0) { // in the Edit Post Form , when post has an Id => 'post_id' // get the posts without its fils or itself 

            if ($parent == 0) // apply the join with PostType

                $response = $this->createQueryBuilder('p')

                    ->select('p')
                    ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type = :postType')
                    ->andWhere('p.post_parent = :parent')
                    ->andWhere('p.id != :post_id')
                    ->andWhere('p.post_status = :post_status')
                    ->andWhere('p.post_title like :postTitle')
                    ->setParameters(new ArrayCollection([
                        new Parameter('parent', $parent),
                        new Parameter('post_id', $post_id),
                        new Parameter('post_status', 'Publié'),
                        new Parameter('postType', $postType),
                        new Parameter('postTitle', $postTitle . '%'),
                    ]))
                    ->orderBy('p.id', 'ASC')
                    ->setMaxResults(50)
                    ->getQuery()
                    ->getResult();
            else // no need to Joining with posttype, cause the list is already selected ==> we get th parent with post_id
                $response = $this->createQueryBuilder('p') // get the posts without its fils or itself
                    ->andWhere('p.post_parent = :parent')
                    ->andWhere('p.id != :post_id')
                    ->andWhere('p.post_status = :post_status')
                    ->andWhere('p.post_title like :postTitle')
                    ->setParameters(new ArrayCollection([
                        new Parameter('parent', $parent),
                        new Parameter('post_id', $post_id),
                        new Parameter('post_status', 'Publié'),
                        new Parameter('postTitle', $postTitle . '%'),
                    ]))
                    ->orderBy('p.id', 'ASC')
                    ->setMaxResults(50)
                    ->getQuery()
                    ->getResult();
        } else { // in the new Post Form 

            if ($parent == 0) // apply the join with PostType

                $response = $this->createQueryBuilder('p')

                    ->select('p')
                    ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type = :postType')
                    ->andWhere('p.post_parent = :parent')
                    ->andWhere('p.post_status = :post_status')
                    ->andWhere('p.post_title like :postTitle')

                    ->setParameters(new ArrayCollection([
                        new Parameter('parent', $parent),
                        new Parameter('post_status', 'Publié'),
                        new Parameter('postType', $postType),
                        new Parameter('postTitle', $postTitle . '%'),
                    ]))
                    ->orderBy('p.id', 'ASC')
                    ->setMaxResults(50)
                    ->getQuery()
                    ->getResult();
            else // no need to Joining with posttype, cause the list is already selected ==> we get th parent with post_id

                $response = $this->createQueryBuilder('p')
                    ->andWhere('p.post_parent = :parent')
                    ->andWhere('p.post_status = :post_status')
                    ->andWhere('p.post_title like :postTitle')

                    ->setParameters(new ArrayCollection([
                        new Parameter('parent', $parent),
                        new Parameter('post_status', 'Publié'),
                        new Parameter('postTitle', $postTitle . '%'),
                    ]))
                    ->orderBy('p.id', 'ASC')
                    ->setMaxResults(50)
                    ->getQuery()
                    ->getResult();
        }
        return $response;
    }

    public function findOneByName($name, $parent): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.post_name = :name')
            ->andWhere('p.post_parent = :parent')
            ->setParameters(new ArrayCollection([
                new Parameter('parent', $parent),
                new Parameter('name', $name)
            ]))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * search post with name and id and post type
     */
    public function postSearch($post_recherche, $post_type = "")
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.post_title like :val')
            ->setParameter('val', '%' . $post_recherche . '%');
        if (strlen($post_type) && $post_type != null) {
            $query = $query->leftJoin('p.post_type', 't')
                ->andWhere('t.slug_post_type  = :slug_post_type')
                ->setParameter('slug_post_type', $post_type);
        }
        $query = $query->orWhere('p.id = :id')
            ->setParameter('id', $post_recherche);
        if (strlen($post_type) && $post_type != null) {
            $query = $query->andWhere('t.slug_post_type  = :slug_post_type')
                ->setParameter('slug_post_type', $post_type);
        }
        $query = $query->setMaxResults(100)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
        return $query;
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findAllByPostType($postType, $search = "", $status = "Publié")
    {
        return $this->createQueryBuilder('p')

            ->select('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type = :postType')
            ->andWhere('p.post_title like :search')
            ->andWhere('p.post_status = :postStatus')
            ->setParameters(new ArrayCollection([
                new Parameter('postType', $postType),
                new Parameter('postStatus', $status),
                new Parameter('search', '%' . $search . '%'),
            ]))

            ->getQuery()
            ->getResult();
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findLimitByPostType($postType, $search = "")
    {
        return $this->createQueryBuilder('p')

            ->select('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type = :postType')
            ->andWhere('p.post_title like :search')
            ->andWhere('p.post_status = :postStatus')
            ->setParameters(new ArrayCollection([
                new Parameter('postType', $postType),
                new Parameter('postStatus', 'Publié'),
                new Parameter('search', '%' . $search . '%'),
            ]))
            ->setMaxResults(14)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findAllByPostTypeExcept0($postType, $search = "")
    {
        return $this->createQueryBuilder('p')

            ->select('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type = :postType')
            ->andWhere('p.post_title like :search')
            ->andWhere('p.post_status = :postStatus')
            ->andWhere('p.post_parent != 0')
            ->setParameters(new ArrayCollection([
                new Parameter('postType', $postType),
                new Parameter('postStatus', 'Publié'),
                new Parameter('search', '%' . $search . '%'),
            ]))

            ->getQuery()
            ->getResult();
    }


    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findByState($stat, $postType)
    {
        return $this->createQueryBuilder('p')

            ->select('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type = :postType')

            ->andWhere('p.post_status = :stat')

            ->setParameters(new ArrayCollection([
                new Parameter('stat', $stat),
                new Parameter('postType', $postType)
            ]))
            ->getQuery()
            ->getResult();
    }

    public function isPostNameExiste($slug, $my_parent = 0, $post_id = 0): ?Post // is slug exist with the same parent
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.post_name = :slug')
            ->andWhere('p.id != :post_id')
            ->andWhere('p.post_parent = :my_parent')
            ->setParameters(new ArrayCollection([
                new Parameter('slug', $slug),
                new Parameter('post_id', $post_id),
                new Parameter('my_parent', $my_parent)
            ]))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * find Accueil Post
     */
    public function findAccueilPost()
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.post_status like :post_status')
            ->setParameter('post_status', 'Publié')
            ->leftJoin('p.post_type', 't')
            ->andWhere('t.slug_post_type  = :slug_post_type')
            ->setParameter('slug_post_type', 'accueil')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $query;
    }
    /**
     * count posts by post_type
     */
    public function postsCount($post_type)
    {
        $query = $this->createQueryBuilder('p')
            ->select('count(p.id)');
        $query = $query->leftJoin('p.post_type', 't')
            ->andWhere('t.slug_post_type  = :slug_post_type')
            ->setParameter('slug_post_type', $post_type);
        return $query->getQuery()->getSingleScalarResult();
    }
    /**
     * get Post Repo getPostRepo
     */
    public function getPostRepo($page_number)
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.post_parent_migration  != :post_parent_migration')
            ->setParameter('post_parent_migration', 0);
        $query = $query->leftJoin('p.post_type', 't')
            ->andWhere('t.slug_post_type  = :slug_post_type')
            ->setParameter('slug_post_type', 'page');
        return $query->setFirstResult($page_number)->setMaxResults(1)->getQuery()->getOneOrNullResult();
        ;
    }
    /**
     * get Post Repo getPostRepo
     */
    public function getPostCountRepo()
    {
        $query = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.post_parent_migration  != :post_parent_migration')
            ->setParameter('post_parent_migration', 0);
        $query = $query->leftJoin('p.post_type', 't')
            ->andWhere('t.slug_post_type  = :slug_post_type')
            ->setParameter('slug_post_type', 'page');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getPageCategories($postTitle)
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type = :postType')
            ->andWhere('p.post_parent = :parent')
            ->andWhere('p.post_status = :postStatus')
            ->andWhere('p.post_title like :postTitle')
            ->andWhere('p.page_menu = :page_menu')
            ->setParameters(new ArrayCollection([
                new Parameter('parent', 0),
                new Parameter('postStatus', 'Publié'),
                new Parameter('postTitle', '%' . $postTitle . '%'),
                new Parameter('postType', 'page'),
                new Parameter('page_menu', true),

            ]))
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getPostByParentId_and_PostName($parentId, $postName, $post_type = "page", $post_status = "publié")
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->andWhere('p.post_name like :postName')
            ->andWhere('p.post_parent = :parent')
            ->andWhere('p.post_status like :postStatus')
            ->setParameters(new ArrayCollection([
                new Parameter('parent', $parentId),
                new Parameter('postStatus', '%' . $post_status . '%'),
                new Parameter('postType', '%' . $post_type . '%'),
                new Parameter('postName', '%' . $postName . '%'),
            ]))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $response;
    }

    public function getPostsByParentId($parentId, $search = "", $post_type = "page", $post_status = "Publié")
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.postMetas', 'pM', 'WITH', 'pM.meta_key like :template_page_key')
            ->andWhere('pM.meta_value like :template_page_value')
            ->andWhere('p.post_parent = :parent')
            ->andWhere('p.post_status like :postStatus')
            ->andWhere('p.post_title like :search')
            ->setParameters(new ArrayCollection([
                new Parameter('parent', $parentId),
                new Parameter('postStatus', '%' . $post_status . '%'),
                new Parameter('postType', '%' . $post_type . '%'),
                new Parameter('search', '%' . $search . '%'),
                new Parameter('template_page_key', '%_wp_page_template%'),
                new Parameter('template_page_value', '%template_departement%')

            ]))
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getPageByParentIdWithTemplatePage($parentId, $search = "", $template_page_key, $template_page_value, $maxResult = 50, $post_status = "Publié")
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.postMetas', 'pM', 'WITH', 'pM.meta_key like :template_page_key')
            ->andWhere('pM.meta_value like :template_page_value')
            ->andWhere('p.post_parent = :parent')
            ->andWhere('p.post_status like :postStatus')
            ->andWhere('p.post_title like :search')
            ->setParameters(new ArrayCollection([
                new Parameter('parent', $parentId),
                new Parameter('postStatus', '%' . $post_status . '%'),
                new Parameter('postType', '%page%'),
                new Parameter('search', '%' . $search . '%'),
                new Parameter('template_page_value', '%' . $template_page_value . '%'),
                new Parameter('template_page_key', '%' . $template_page_key . '%'),

            ]))
            ->orderBy('p.id', 'ASC')
            ->setMaxResults($maxResult)
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getblog($search = "")
    {

        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->andWhere('p.post_status like :postStatus')
            ->andWhere('p.post_title like :search')
            ->setParameters(new ArrayCollection([
                new Parameter('postStatus', '%Publié%'),
                new Parameter('postType', '%blog%'),
                new Parameter('search', '%' . $search . '%'),
            ]))
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
        return $response;
    }
    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findLimitFirstByPostType($postType, $start, $limit, $search = "")
    {
        return $this->createQueryBuilder('p')

            ->select('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type = :postType')
            ->andWhere('p.post_status = :postStatus')
            ->andWhere('p.post_title like :search')
            ->setParameters(new ArrayCollection([
                new Parameter('postType', $postType),
                new Parameter('postStatus', 'Publié'),
                new Parameter('search', '%' . $search . '%'),
            ]))
            ->setFirstResult(($start - 1) * $limit)
            ->setMaxResults($limit)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function get_blog_by_post_meta($meta_key)
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.postMetas', 'pM', 'WITH', 'pM.meta_key like :meta_key')
            ->andWhere('p.post_status like :postStatus')
            ->setParameters(new ArrayCollection([
                new Parameter('postStatus', '%Publié%'),
                new Parameter('postType', '%blog%'),
                new Parameter('meta_key', '%' . $meta_key . '%'),
            ]))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $response;
    }


    public function getPageByTermDevisWithTemplatePage()
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.postMetas', 'pM', 'WITH', 'pM.meta_key like :template_page_key')
            ->innerJoin('p.terms', 't', 'WITH', 't.slug_terms like :terms')
            ->andWhere('pM.meta_value like :template_page_value')
            ->andWhere('p.post_status like :postStatus')
            ->setParameters(new ArrayCollection([
                new Parameter('postStatus', '%Publié%'),
                new Parameter('postType', '%page%'),
                new Parameter('template_page_value', '%template_devis%'),
                new Parameter('template_page_key', '%_wp_page_template%'),
                new Parameter('terms', '%devis%'),

            ]))
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getPageByTermPostypeQuestions($id)
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.terms', 't', 'WITH', 't.id = :idTerms')
            ->andWhere('p.post_status like :postStatus')
            ->andWhere('p.post_parent = :idParent')
            ->setParameters(new ArrayCollection([
                new Parameter('postStatus', '%Publié%'),
                new Parameter('postType', '%questions%'),
                new Parameter('idTerms', $id),
                new Parameter('idParent', 0),
            ]))
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getPostsByTerm($slug_term, $search = "", $status = "Publié", $post_type = "", $page_template = "")
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.terms', 't', 'WITH', 't.slug_terms like :terms')
            ->andWhere('p.post_title like :search')
            ->andWhere('p.page_template like :page_template')
            ->andWhere('p.post_status like :postStatus')
            ->setParameters(new ArrayCollection([
                new Parameter('search', '%' . $search . '%'),
                new Parameter('postStatus', '%' . $status . '%'),
                new Parameter('postType', '%' . $post_type . '%'),
                new Parameter('page_template', '%' . $page_template . '%'),
                new Parameter('terms', '%' . $slug_term . '%'),
            ]))
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getTemplatePageService($start, $limit, $search = "")
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.postMetas', 'pM', 'WITH', 'pM.meta_key like :template_page_key')
            ->andWhere('pM.meta_value like :template_page_value')
            ->andWhere('p.post_status like :postStatus')
            ->andWhere('p.post_title like :search')
            ->andWhere('p.post_parent > :idp')
            ->setParameters(new ArrayCollection([
                new Parameter('postStatus', '%Publié%'),
                new Parameter('postType', '%page%'),
                new Parameter('template_page_value', '%default%'),
                new Parameter('template_page_key', '%_wp_page_template%'),
                new Parameter('search', '%' . $search . '%'),
                new Parameter('idp', 0)

            ]))
            ->orderBy('p.id', 'ASC')
            ->setFirstResult(($start - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $response;
    }
    public function getblogmax($start, $limit, $search = "")
    {

        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->andWhere('p.post_status like :postStatus')
            ->andWhere('p.post_title like :search')
            ->setParameters(new ArrayCollection([
                new Parameter('postStatus', '%Publié%'),
                new Parameter('postType', '%blog%'),
                new Parameter('search', '%' . $search . '%'),
            ]))
            ->orderBy('p.id', 'ASC')
            ->setFirstResult(($start - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getPostsByTermAndId($id_post)
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.terms', 't', 'WITH', 't.slug_terms like :terms')
            ->andWhere('p.page_template like :page_template')
            ->andWhere('p.post_status like :postStatus')
            ->andWhere('p.id = :idPost')

            ->setParameters(new ArrayCollection([
                new Parameter('postStatus', '%Publié%'),
                new Parameter('postType', '%page%'),
                new Parameter('page_template', '%template_devis%'),
                new Parameter('terms', '%devis%'),
                new Parameter('idPost', $id_post),
            ]))
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getPostsByTermSlug($word, $terms)
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.terms', 't', 'WITH', 't.id = :terms')
            ->andWhere('p.post_title like :word_key')
            ->andWhere('p.post_status like :postStatus')

            ->setParameters(new ArrayCollection([
                new Parameter('postStatus', '%Publié%'),
                new Parameter('postType', '%nous-rejoindre%'),
                new Parameter('word_key', '%' . $word . '%'),
                new Parameter('terms', $terms),

            ]))
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getPostsByTermWord($word)
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.terms', 't')
            ->andWhere('p.post_title like :word_key')
            ->andWhere('p.post_status like :postStatus')

            ->setParameters(new ArrayCollection([
                new Parameter('postStatus', '%Publié%'),
                new Parameter('postType', '%nous-rejoindre%'),
                new Parameter('word_key', '%' . $word . '%'),
            ]))
            ->getQuery()
            ->getResult();
        return $response;
    }

    public function getallPostsByTermSlug()
    {
        $response = $this->createQueryBuilder('p')
            ->innerJoin('p.post_type', 'pT', 'WITH', 'pT.slug_post_type like :postType')
            ->innerJoin('p.terms', 't')
            ->andWhere('p.post_status like :postStatus')

            ->setParameters(new ArrayCollection([
                new Parameter('postStatus', '%Publié%'),
                new Parameter('postType', '%nous-rejoindre%'),

            ]))
            ->getQuery()
            ->getResult();
        return $response;
    }
    public function findAllCommentByPage($search)
    {
        return $this->createQueryBuilder('p')

            ->select('p')
            ->andWhere('p.post_title LIKE :search')
            ->andWhere('p.post_status = :postStatus')
            ->andWhere('p.page_template = :pageTemplate')
            ->setParameter('postStatus', 'Publié')
            ->setParameter('pageTemplate', 'template_fiche_produit')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * get Googles Avis
     */

    public function getGooglesAvis()
    {
        ///*,pm._meta_note_avis_google as note_comment,pm._meta_image_avis_google as images_avis
        return $this->createQueryBuilder('p')
            ->select('p.id,p.post_title as first_name,p.post_excerpt as comment_content,p.date_add as date_add,pm.meta_value as note_comment,pm2.meta_value as image_url')
            ->leftJoin('p.postMetas', 'pm')
            ->leftJoin('p.postMetas', 'pm2')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'avis-google')
            ->andWhere('pm.meta_key = :meta_key')
            ->setParameter('meta_key', '_meta_note_avis_google')
            ->andWhere('pm2.meta_key = :meta_key2')
            ->setParameter('meta_key2', '_meta_image_avis_google')
            ->getQuery()
            ->getResult();
    }
    /**
     * count Comments Googles Avis
     */
    public function countCommentsGooglesAvis()
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id) as count')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'avis-google')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * note global Comments Googles Avis
     */
    public function sumNotesGooglesAvis()
    {
        return $this->createQueryBuilder('p')
            ->select('SUM(pm.meta_value) as sum_note')
            ->leftJoin('p.postMetas', 'pm')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'avis-googles')
            ->andWhere('pm.meta_key = :meta_key')
            ->setParameter('meta_key', '_meta_note_avis_google')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * get all agence list
     */
    public function findAllByPostTypes($post_type = "", $id = null, $start = 0, $max_results = 0)
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', $post_type);
        // check if agence list
        if ($post_type == "nos-agences") {
            $query = $query->andWhere('p.post_parent != :parent')
                ->setParameter('parent', '0');
        }
        // exclude id if != null
        if ($id != null) {
            $query = $query->andWhere('p.id != :id')
                ->setParameter('id', $id);
        }
        // check if start and max_result
        if ($start && $max_results) {
            $query = $query->setFirstResult(($start - 1) * $max_results)
                ->setMaxResults($max_results);
        }
        $query = $query->orderBy('p.date_upd', 'DESC')->getQuery();
        $query = $query->getResult();
        return $query;
    }
    /**
     * get Agence Detail
     */
    public function getAgenceDetail($post = "")
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'nos-agences')
            ->andWhere('p.post_parent != :parent')
            ->setParameter('parent', '0')
            ->andWhere('p.post_name = :post_name')
            ->setParameter('post_name', $post);
        $query = $query->setMaxResults(1)->getQuery()
            ->getOneOrNullResult();
        return $query;
    }
    /**
     * findAllAgenceAdress
     */
    public function findAllAgenceAdress()
    {
        $query = $this->createQueryBuilder('p')
            ->select("pm.meta_value as meta_val")
            ->leftJoin('p.postMetas', 'pm')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'nos-agences')
            ->andWhere('p.post_parent != :parent')
            ->setParameter('parent', '0')
            ->andWhere('pm.meta_key like :meta_key')
            ->setParameter('meta_key', '_agence_address');
        $query = $query->getQuery()
            ->getResult();
        return $query;
    }
    /**
     * get Blog Detail
     */
    public function getBlogDetail($post = "")
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'blog')
            ->andWhere('p.post_name = :post_name')
            ->setParameter('post_name', $post);
        $query = $query->setMaxResults(1)->getQuery()
            ->getOneOrNullResult();
        return $query;
    }
    /**
     * get Author Detail
     */
    public function getAuthorDetail($post = "")
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'auteurs')
            ->andWhere('p.post_name = :post_name')
            ->setParameter('post_name', $post);
        $query = $query->setMaxResults(1)->getQuery()
            ->getOneOrNullResult();
        return $query;
    }


    /**
     * list des auteurs
     */

    public function findAuteursList()
    {

        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'auteurs');
        $query = $query->getQuery()


            ->getResult();
        return $query;
    }
    /**
     * find Auteur
     */
    public function findAuteur($id = null)
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'auteurs')->setMaxResults(1);
        if ($id) {
            $query = $query->andWhere('p.id = :id')
                ->setParameter('id', $id);
        }
        $query = $query->setMaxResults(1)->getQuery()
            ->getOneOrNullResult();

        return $query;
    }
    /**
     * get all autheurs
     */
    public function getAllAuteurs()
    {
        return $this->createQueryBuilder('p')

            ->select('p')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'auteurs')
            ->getQuery()
            ->getResult();
    }
    /**
     * findAllDepannage 
     */
    public function findAllDepannage()
    {
        return $this->createQueryBuilder('p')

            ->select('p')
            ->leftJoin('p.post_type', 'pt')
            ->andWhere('p.post_status = :postStatus')
            ->setParameter('postStatus', 'Publié')
            ->andWhere('pt.slug_post_type = :slug_post_type')
            ->setParameter('slug_post_type', 'page')
            ->andWhere('p.post_name like :post_name')
            ->setParameter('post_name', '%depannage-rideau-metallique-%')
            ->orWhere('p.post_name like :post_name_2')
            ->setParameter('post_name_2', '%depannage-rideaux-metalliques-%')
            ->getQuery()
            ->getResult();
    }
    /**
     * select Count All post
     */
    public function selectCount(){
        return $this->createQueryBuilder('p')
        ->select('count(p.id) as count')
        ->getQuery()
        ->getSingleScalarResult();
    }
    /**
     * select post by index
     */
    public function selectByIndex($index){
        return $this->createQueryBuilder('p')
        ->setFirstResult($index)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
    }
}
