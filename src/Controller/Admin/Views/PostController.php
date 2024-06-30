<?php

namespace App\Controller\Admin\Views;

use App\Entity\PostType;
use App\Form\PostSingleType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\{Post,TemplatePage,Taxonomy,PostMetaFields,ModelesPost};

#[Route('/post/{post_type}', options:["need_permession"=>true,"is_list"=>true,"module"=>"Post", "method"=>["Afficher","Ajouter","Modifier","Supprimer"]])]
class PostController extends AbstractController
{
    private $em;
    private $serializer;
    private $urlGenerator;
    const POST_STATUS = ['Publié','Corbeille','Brouillon'];
    const POST_FIELDS = [
        ['name'=>'Titre','field_name'=>'post_title','method'=>'post_title','sortable'=>true,'type'=>'text'],
        ['name'=>'Slug','field_name'=>'post_name','method'=>'post_name','sortable'=>true,'type'=>'text'],
        ['name'=>'Parent','field_name'=>'post_parent','method'=>'post_parent','sortable'=>false,'type'=>'parent'],
        ['name'=>'Auteur / autrice','field_name'=>'author','method'=>'author','sortable'=>false,'type'=>'entity','field_to_display'=>'post_title','collection'=>false],
        ['name'=>'Créer par','field_name'=>'creator','method'=>'creator','sortable'=>false,'type'=>'entity','field_to_display'=>'email','collection'=>false],
        ['name'=>'Etat','field_name'=>'post_status','method'=>'post_status','sortable'=>false,'type'=>'text','column_status'=>true],
        ['name'=>'Catégories','field_name'=>'terms','method'=>'terms','sortable'=>false,'type'=>'entity','field_to_display'=>'name_terms','collection'=>true],
        ['name'=>'Date','field_name'=>'date_add','method'=>'date_add','sortable'=>true,'type'=>'date']
    ];
    public $current_themes;
    public $assets_front_directory;
    private $post_per_page;
    // init constructor
    public function __construct(
        EntityManagerInterface $EntityManagerInterface,
        SerializerInterface $serializer,
        string $current_themes,
        string $assets_front_directory,
        string $post_per_page,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->current_themes = $current_themes;
        $this->assets_front_directory = $assets_front_directory;
        $this->post_per_page = (int)$post_per_page;
        $this->urlGenerator = $urlGenerator;
    }

    #[Route('/', name: 'app_post_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5], requirements:["post_type"=>"^[a-zA-Z0-9-]+$"])]
    public function index(PostRepository $postRepository,$post_type): Response
    {
        // check post type existances
        $post_type_entity = $this->em->getRepository(PostType::class)->findOneBy(array('slug_post_type'=>$post_type));
        
        if(empty($post_type_entity)){
            return new RedirectResponse($this->urlGenerator->generate('app_post_index',['post_type'=>'post']));
        }
        $taxonomies = $post_type_entity->getTaxonomies();
        $post_type_entity = json_decode($this->serializer->serialize($post_type_entity, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // get posts list
        $posts =  $this->em->getRepository(Post::class)->getListPosts($post_type,0,$this->post_per_page,'Publié',false);
        $posts = json_decode($this->serializer->serialize($posts, 'json', ['groups' =>['post_data'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // get count posts
        $posts_count_tous =  $this->em->getRepository(Post::class)->getListPosts($post_type,0,$this->post_per_page,'',true);

        // get count posts publié
        $posts_count_publie =  $this->em->getRepository(Post::class)->getListPosts($post_type,0,$this->post_per_page,'Publié',true);

        // get count posts Corbeille
        $posts_count_corbeille =  $this->em->getRepository(Post::class)->getListPosts($post_type,0,$this->post_per_page,'Corbeille',true);
        // get count posts Brouillon
        $posts_count_brouillon =  $this->em->getRepository(Post::class)->getListPosts($post_type,0,$this->post_per_page,'Brouillon',true);
        // get coun posts
        $page_count = ceil($posts_count_tous/$this->post_per_page);
        // filters
        $filters_list = [];
        // add toxonmies to filter list
        if(!empty($taxonomies)){
            foreach($taxonomies as $taxonomy){
                $terms_list  = [];
                foreach($taxonomy->getTerms() as $term){
                    $terms_list[$term->getId()] =  $term->getNameTerms();
                }
                $filters_list[] = [
                    'field'=>'terms',
                    'name'=>$taxonomy->getNameTaxonomy(),
                    'list'=>$terms_list,
                ];
            }
        }
        // add author list 
        if($post_type!="auteur"){
            $auteurs = $this->em->getRepository(Post::class)->getListPosts("auteur",0,100,'Publié',false);
            $auteurs_list  = [];
            foreach($auteurs as $auteur){
                $auteurs_list[$auteur->getId()] = $auteur->getPostTitle();
            }
            $filters_list[] = [
                'field'=>'author',
                'name'=>'Auteurs',
                'list'=>$auteurs_list,
            ];
            
        }
        return $this->render('admin/post/index.html.twig', [
            'posts' => $posts,
            'posts_count'=>$posts_count_tous,
            'page_count'=>$page_count,
            'posts_count_publie'=>$posts_count_publie,
            'posts_count_corbeille'=>$posts_count_corbeille,
            'posts_count_brouillon'=>$posts_count_brouillon,
            'fields'=>self::POST_FIELDS,
            'post_type_slug'=>$post_type,
            'filters_list'=>$filters_list,
            'post_type_entity'=>$post_type_entity,
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'], options:["action"=>"Ajouter","order"=>4], requirements:["post_type"=>"^[a-zA-Z0-9-]+$"])]
    public function new(Request $request,$post_type): Response
    {
        // Do something with the analysis results
        $post = new Post();
        // get template list by post type
        $template_pages = $this->em->getRepository(TemplatePage::class)->findByPostType($post_type);
        $template_pages = json_decode($this->serializer->serialize($template_pages, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        //  get category by taxonomy by pos type
        $taxonomy = $this->em->getRepository(Taxonomy::class)->findByPostType($post_type);
        $taxonomy = json_decode($this->serializer->serialize($taxonomy, 'json', ['groups' =>['show_data'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        // get all post meta fields by post type
        $postmeta_forms = $this->em->getRepository(PostMetaFields::class)->findByPostType($post_type);
        $postmeta_forms = json_decode($this->serializer->serialize($postmeta_forms, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // get all modele post by post type
        $all_post_modals = $this->em->getRepository(ModelesPost::class)->findByPostType($post_type);
        $all_post_modals = json_decode($this->serializer->serialize($all_post_modals, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // 
        // add author list 
        $auteurs = [];
        if($post_type!="auteur"){
            $auteurs = $this->em->getRepository(Post::class)->getListPosts("auteur",0,500,'Publié',false);
            $auteurs = json_decode($this->serializer->serialize($auteurs, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true); 
        }
        // add page parent list
        $pages_parents = [];
        if($post_type=="page"){
            $pages_parents = $this->em->getRepository(Post::class)->getListPosts("page",0,500,'Publié',false,[],true,0);
            $pages_parents = json_decode($this->serializer->serialize($pages_parents, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true); 
        }
        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'template_pages'=>$template_pages,
            'taxonomies'=>$taxonomy,
            'postmeta_forms'=>$postmeta_forms,
            'post_modals'=>$all_post_modals,
            'post_type'=>$post_type,
            'post_status'=>self::POST_STATUS,
            'current_themes'=>$this->current_themes,
            'auteurs'=>$auteurs,
            'pages_parents'=>$pages_parents,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'], options:["action"=>"Modifier","order"=>1], requirements:["id"=>"[0-9]+","post_type"=>"^[a-zA-Z0-9-]+$"])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager,$post_type): Response
    {
        // get template list by post type
        $template_pages = $this->em->getRepository(TemplatePage::class)->findByPostType($post_type);
        $template_pages = json_decode($this->serializer->serialize($template_pages, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        //  get category by taxonomy by pos type
        $taxonomy = $this->em->getRepository(Taxonomy::class)->findByPostType($post_type);
        $taxonomy = json_decode($this->serializer->serialize($taxonomy, 'json', ['groups' =>['show_data'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        // get all post meta fields by post type
        $postmeta_forms = $this->em->getRepository(PostMetaFields::class)->findByPostType($post_type);
        $postmeta_forms = json_decode($this->serializer->serialize($postmeta_forms, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // get all modele post by post type
        $all_post_modals = $this->em->getRepository(ModelesPost::class)->findByPostType($post_type);
        $all_post_modals = json_decode($this->serializer->serialize($all_post_modals, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
         
        // add author list 
        $auteurs = [];
        if($post_type!="auteur"){
            $auteurs = $this->em->getRepository(Post::class)->getListPosts("auteur",0,500,'Publié',false);
            $auteurs = json_decode($this->serializer->serialize($auteurs, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true); 
        }
        // add page parent list
        $pages_parents = [];
        if($post_type=="page"){
            $pages_parents = $this->em->getRepository(Post::class)->getListPosts("page",0,500,'Publié',false,[],true,0,$post->getId());
            $pages_parents = json_decode($this->serializer->serialize($pages_parents, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true); 
        }
        // traiter le page parent
        //$post_parent = (int)$post->getPostParent();
        $page_parents_select = [];
        $page_parents_id = [];
        if($post_type=="page"){
            // init vars
            $key = 1;
            $post_parent = (int)$post->getId();
            // select all parent
            do {
                $post_child = $this->em->getRepository(Post::class)->find($post_parent);
                // serialize post ( by group data front)
                $post_child = json_decode($this->serializer->serialize($post_child, 'json', ['groups' => ['show_api','data_front'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
                
                $post_parent_id = (int)$post_child['post_parent'];
                //var_dump($post_parent_id);
                if (!empty($post_child)) {
                    if((int)$post->getId() !== (int)$post_child['id']){
                        $page_parents_id[] = (int)$post_child['id'];
                    }
                    $pages_parents_child = $this->em->getRepository(Post::class)->getListPosts("page",0,500,'Publié',false,[],true,$post_parent_id,(int)$post->getId());
                    $pages_parents_child = json_decode($this->serializer->serialize($pages_parents_child, 'json', ['groups' =>['show_api','data_front'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true); 
                    $page_parents_select[] = array(
                        'id' => $post_child['id'],
                        'parent'=>$post_parent,
                        'name' => $post_child['post_title'],
                        'post_name' => $post_child['post_name'],
                        'pages_parents_child'=>$pages_parents_child,
                    );
                }
                $key = $key + 1;
                $post_parent = (int)$post_child['post_parent'];
                
            } while ($post_parent != 0 && $key < 5);
        }
        // revers page parents select 
        $page_parents_select =array_reverse($page_parents_select,true);
        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'template_pages'=>$template_pages,
            'taxonomies'=>$taxonomy,
            'postmeta_forms'=>$postmeta_forms,
            'post_modals'=>$all_post_modals,
            'post_type'=>$post_type,
            'post_status'=>self::POST_STATUS,
            'current_themes'=>$this->current_themes,
            'auteurs'=>$auteurs,
            'pages_parents'=>$pages_parents,
            'page_parents_select'=>$page_parents_select,
            "page_parents_id"=>$page_parents_id,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'], options:["action"=>"Supprimer","order"=>3], requirements:["id"=>"[0-9]+","post_type"=>"^[a-zA-Z0-9-]+$"])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager,$post_type): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
