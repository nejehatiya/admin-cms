<?php

namespace App\Controller\Admin\Api;

use Cocur\Slugify\Slugify;
use App\Entity\{ModelesPost};
use App\Form\PostModalsType;
use App\Repository\PostModalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\{PostMetaFields,Images,PostType,Post,PostMeta,Terms,Revision};

#[Route('/api/{post_type}')]
class ApiPostController extends AbstractController
{
    private $em;
    private $serializer;
    private $slugify;
    private $url_site;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer,string $url_site)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->url_site = $url_site;
        $this->slugify = new Slugify();
    }
    #[Route('/check-slug', name: 'app_post_check_slug_index_ttt', methods: ['POST'])]
    public function indexCheckName(Request $request,$post_type): Response
    {
        // check on the first time post type existances
        $check_post_type = $this->em->getRepository(PostType::class)->findOneBy(array('slug_post_type'=>$post_type));
        if(empty($check_post_type)){
            return $this->json(['success'=>false,'message'=>'post type n\'existe pas ']);
        }
        // get name from request
        $slug = $this->slugify->slugify(strip_tags($request->request->get('slug')));
        // get parent from request
        $parent_id = (int)strip_tags($request->request->get('parent_id'));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_slug = $this->em->getRepository(Post::class)->checkSlugExist($slug,$parent_id,$id);
        if(!empty($check_slug)){
            $index = 1 ;
            $is_existe = true;
            while ($is_existe) :
                $slug = $this->slugger->slug($slug.'-'.$index);
                $check_slug = $this->em->getRepository(Post::class)->checkSlugExist($slug,$parent_id,$id);
                $is_existe = !empty($check_slug);
                $index = $index + 1;
            endwhile;
        }
        return $this->json(['success'=>true,'message'=>'post name autorisé','slug'=>$slug]);
    }
    
    #[Route('/new', name: 'app_post_new', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        return $this->indexEdit($request,null);
    }
    #[Route('/edit/{id}', name: 'app_post_edit_api_test', methods: ['POST'])]
    public function indexEdit(Request $request,$id,$post_type): Response
    {
        // check on the first time post type existances
        $check_post_type = $this->em->getRepository(PostType::class)->findOneBy(array('slug_post_type'=>$post_type));
        if(empty($check_post_type)){
            return $this->json(['success'=>false,'message'=>'post type n\'existe pas ']);
        }
        // 1 - create post
        $post = (int)$id?$this->em->getRepository(Post::class)->find((int)$id):new Post();
        // get content before update
        $post_content = "";
        if((int)$id){
            $post_content = $post->getPostOrderContent();
        }
        // get titre/slug/excerpt/template/page_prent/image_feature/categories/author/post_meta/model_content
        $titre = strip_tags($request->request->get('titre'));
        $slug = strip_tags($request->request->get('slug'));
        $excerpt = strip_tags($request->request->get('excerpt'));
        $parent_id = (int)$request->request->get('page_prent');
        $image_feature = (int)$request->request->get('image_feature');
        $terms = json_decode($request->request->get('terms'),true);
        $author = (int)$request->request->get('author');
        $post_meta = json_decode($request->request->get('post_meta'),true);
        $model_content = $request->request->get('model_content');
        $model_content_decode = json_decode($request->request->get('model_content'),true);
        $template = (int)$request->request->get('template');
        $post_status = $request->request->get('status');
        // 1
        // check if name is existe
        $check_slug = $this->em->getRepository(Post::class)->checkSlugExist($slug,$parent_id,$id);
        if(!empty($check_slug)){
            $index = 1 ;
            $is_existe = true;
            while ($is_existe) :
                $slug = $this->slugger->slug($slug.'-'.$index);
                $check_slug = $this->em->getRepository(Post::class)->checkSlugExist($slug,$parent_id,$id);
                $is_existe = !empty($check_slug);
                $index = $index + 1;
            endwhile;
        }
        // set titre
        $post->setPostTitle($titre);
        $post->setPostName($slug);
        $post->setPostExcerpt($excerpt);
        $post->setPostParent($parent_id);
        $post->setPostStatus($post_status);
        $post->setCommentStatus(false);
        $post->setDateUpd(new \DateTime());
        $post->setPostMetaJson($request->request->get('post_meta'));
        // traiter post meta
        if($id){
            // delete all old post meta related to this post
            $metas = $post->getPostMetas();
            if(!empty($metas)){
                foreach($metas as $meta){
                    $post->removePostMeta($meta);
                    $this->em->remove($meta);
                }
            }
        }
        
        // add new all post meta
        if(!empty($post_meta)){
            foreach($post_meta as $key=>$value){
                if(is_array($value)){
                    foreach($value as $k=>$v){
                        $new_meta = new PostMeta();
                        $new_meta->setMetaKey($k);
                        $new_meta->setMetaValue($v);
                        $new_meta->setIdPost($post);
                        $this->em->persist($new_meta);
                    }
                }
            }
        }
        // traiter terms
        if(!$id){
            // delete all old term related to this post
            $terms = $post->getTerms();
            if(!empty($terms)){
                foreach($terms as $term){
                    $post->removeTerm($term);
                }
            }
        }
        // attach terms to post
        if(!empty($terms)){
            foreach($terms as $key=>$value){
                $get_term = $this->em->getRepository(Terms::class)->find($value);
                if(!empty($get_term)){
                    $post->addTerm($get_term);
                }
            }
        }
        // traiter l'image feautre
        if((int)$image_feature){
            $get_image = $this->em->getRepository(Images::class)->find((int)$image_feature);
            if(!empty($get_image)){
                $post->setIdFeatureImage($get_image);
            }
        }
        // traiter author
        if($author){
            $get_author = $this->em->find(User::class)->find((int)$author);
            if(!empty($get_author)){
                $post->setAuthor($get_author);
            }
        }
        //  traiter postype
        $post->setPostType($check_post_type);
        // set Post content
        $post->setPostOrderContent($model_content);
        // set template page
        $post->setPageTemplate($template);
        // traiter revision
        if($id){
            $new_revision  = new Revision();
            $new_revision->setPost($post); 
            $new_revision->setPostOrderContent($model_content);
            $new_revision->setDate(new \DateTime());
            $this->em->persist($new_revision);
        }
        $post->setIsDraft(0);
        $post->setPageMenu(false);
        $post->setSommaire("");
        $post->setIsFollow(false);
        $post->setIsIndex(false);
        // message il faut lancer analyse et gérer le sommaire pour chaque post on creation et modifiction
        // is create
        if(!$id){
            $post->setDateAdd(new \DateTime());
            $this->em->persist($post);
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>$id ?'post à jour avec succés':'post créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_post_get_data_yyyyyy', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $post = $this->em->getRepository(Post::class)->find($id);
        $post = json_decode($this->serializer->serialize($post, 'json', ['groups' =>['post_data'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($post)){
            return $this->json(['success'=>false,'message'=>'post n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'post'=>$post]);
    }


    #[Route('/get-sous-page/{id_parent}', name: 'app_post_get_data', methods: ['GET'])]
    public function indexGetPageParentList(Request $request,$id_parent): Response
    {
        
        // get modele post
        $posts_parents = $this->em->getRepository(Post::class)->getPageByParent($id_parent);
        $posts_parents = json_decode($this->serializer->serialize($posts_parents, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($posts_parents)){
            return $this->json(['success'=>false,'message'=>'post  n\'a  pas de fils']);
        }
        
        return $this->json(['success'=>true,'post'=>$post]);
    }


    #[Route('/post-titre-change', name: 'app_post_get_data', methods: ['POST'])]
    public function indexPostTitreChange(Request $request,$post_type): Response
    {
        // check on the first time post type existances
        $check_post_type = $this->em->getRepository(PostType::class)->findOneBy(array('slug_post_type'=>$post_type));
        if(empty($check_post_type)){
            return $this->json(['success'=>false,'message'=>'post type n\'existe pas ']);
        }
        $titre = strip_tags($request->request->get('slug'));
        $id = (int)$request->request->get('id');
        $parent_id = (int)$request->request->get('parent_id');
        if(!$id){
            $slug = $this->slugify->slugify($titre);
            $check_slug = $this->em->getRepository(Post::class)->checkSlugExist($slug,$parent_id,$id);
            if(!empty($check_slug)){
                $index = 1 ;
                $is_existe = true;
                while ($is_existe) :
                    $slug = $this->slugify->slugify($slug.'-'.$index);
                    $check_slug = $this->em->getRepository(Post::class)->checkSlugExist($slug,$parent_id,$id);
                    $is_existe = !empty($check_slug);
                    $index = $index + 1;
                endwhile;
            }

            $post = new Post();
            $post->setPostTitle($titre);
            $post->setPostName($slug);
            $this->em->persist($post);
            $this->em->flush();
        }else{
            $slug = $this->slugify->slugify($titre);
            $check_slug = $this->em->getRepository(Post::class)->checkSlugExist($slug,$parent_id,$id);
            if(!empty($check_slug)){
                $index = 1 ;
                $is_existe = true;
                while ($is_existe) :
                    $slug = $this->slugify->slugify($slug.'-'.$index);
                    $check_slug = $this->em->getRepository(Post::class)->checkSlugExist($slug,$parent_id,$id);
                    $is_existe = !empty($check_slug);
                    $index = $index + 1;
                endwhile;
            }  
            $post = $this->em->getRepository(Post::class)->find($id);
        }

        // get modele post
        $post = json_decode($this->serializer->serialize($post, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($post)){
            return $this->json(['success'=>false,'message'=>'post n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'post'=>$post,'id'=>$post['id'],'url_page_edit'=>$this->url_site.''.$this->generateUrl('app_post_edit', array('post_type' => $post_type,'id'=>$post['id']))]);
    }


    #[Route('/get-form/{id_form}', name: 'app_post_get_form_data', methods: ['POST'])]
    public function indexPostGetForm(Request $request,$id_form): Response
    {
        $data_set = json_decode($request->request->get('data_set'),true);
        //var_dump($data_set);exit;
        // get modele post
        $forms  = $this->em->getRepository(ModelesPost::class)->find($id_form);
        if(empty($forms)){
            return $this->json(['success'=>false,'message'=>'modéle post n\'existe pas']);
        }
        if(empty($data_set)){
            $data_set = json_decode($forms->getContentModele(),true);
            if(!empty($data_set) && array_key_exists('json_data',$data_set)){
                $data_set = $data_set['json_data'];
            }
        }
        // html menu edit 
        $html_form = $this->renderView('admin/global/forms/forms-builder.html.twig',['fields'=>json_decode($forms->getFields()),'data_set'=>$data_set,'is_post'=>true]);
        return $this->json(['success'=>true,'html_form'=>$html_form,'class_sortable'=>$forms->getClassSortable() ]);
    }

    #[Route('/get-preview-modele/{id_modele}', name: 'app_post_get_preview_modele_data', methods: ['POST'])]
    public function indexPostGetPreview(Request $request,$id_modele): Response
    {
        $data = json_decode($request->request->get('data'),true);
        $content_defualt = $request->request->get('content_default');
        //var_dump($data_set);exit;
        // get modele post
        $forms  = $this->em->getRepository(ModelesPost::class)->find($id_modele);
        if(empty($forms)){
            return $this->json(['success'=>false,'message'=>'modéle post n\'existe pas']);
        }
        if($content_defualt){
            $forms->setContentModele($request->request->get('data'));
            $this->em->flush();
        }
        // html menu edit 
        $html_form = $this->renderView('admin/global/preview/preview-modele.html.twig',['model'=>$data]);
        $structure_form = $this->renderView('admin/post/_partial/post-structures-item.html.twig',['model'=>$data]);
        return $this->json(['success'=>true,'preview_html'=>$html_form,'structure_form'=>$structure_form,'class_sortable'=>$forms->getClassSortable() ]);
    }
    #[Route('/get-historique-post/{id}/{page}', name: 'app_post_get_historique_data', methods: ['GET'])]
    public function indexPostGetHistorique(Request $request,$id,$page): Response
    {
        $page = (int)$page?($page-1)*100:0;
        // recuperation historique post
        $histories  = $this->em->getRepository(Revision::class)->findById($id,$page);
        if(empty($histories)){
            return $this->json(['success'=>false,'message'=>'modéle post n\'existe pas']);
        }
        // html menu edit 
        $historique_html = $this->renderView('admin/post/history/history.html.twig',['histories'=>$histories]);
        return $this->json(['success'=>true,'historique_html'=>$historique_html]);
    }
}
