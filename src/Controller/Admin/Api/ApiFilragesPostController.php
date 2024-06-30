<?php

namespace App\Controller\Admin\Api;

use Cocur\Slugify\Slugify;
use App\Form\PostModalsType;
use App\Entity\Configuration;
use App\Entity\{ModelesPost};
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
use App\Controller\Admin\Views\PostController;
#[Route('/api/filter-post')]
class ApiFilragesPostController extends AbstractController
{
    private $em;
    private $serializer;
    private $slugify;
    private $url_site;
    private $post_per_page;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer,string $url_site,string $post_per_page)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->url_site = $url_site;
        $this->slugify = new Slugify();
        $this->post_per_page = (int)$post_per_page;
    }
    #[Route('/', name: 'app_filter_post_api_edit_config', methods: ['POST'])]
    public function indexFilterPost(Request $request): Response
    {
        $params = $request->request->all();
        // post type get from request
        $post_type = $params['post_type'];
        $post_status = $params['post_status'] === "Tous" ? "":$params['post_status'];
        unset($params['post_type']);
        unset($params['post_status']);
        $start = array_key_exists('current_page',$params) && (int)$params['current_page'] ? (int)$params['current_page'] -1 : 0;
        // get posts list filtrer
        $posts =  $this->em->getRepository(Post::class)->getListPosts($post_type,$start,$this->post_per_page,$post_status,false,$params);
        $posts = json_decode($this->serializer->serialize($posts, 'json', ['groups' =>['post_data'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        // get count posts
        $posts_count_tous =  $this->em->getRepository(Post::class)->getListPosts($post_type,0,$this->post_per_page,'',true,$params);
        $page_count_staus_current = $posts_count_tous;
        // get count posts publié
        $posts_count_publie =  $this->em->getRepository(Post::class)->getListPosts($post_type,0,$this->post_per_page,'Publié',true,$params);
        if($post_status=="Publié"){
            $page_count_staus_current = $posts_count_publie;
        }
        // get count posts Corbeille
        $posts_count_corbeille =  $this->em->getRepository(Post::class)->getListPosts($post_type,0,$this->post_per_page,'Corbeille',true,$params);
        if($post_status=="Corbeille"){
            $page_count_staus_current = $posts_count_corbeille;
        }
        // get count posts Brouillon
        $posts_count_brouillon =  $this->em->getRepository(Post::class)->getListPosts($post_type,0,$this->post_per_page,'Brouillon',true,$params);
        if($post_status=="Brouillon"){
            $page_count_staus_current = $posts_count_brouillon;
        }
        // get coun posts
        $page_count = ceil($page_count_staus_current/$this->post_per_page);
        $table_html = $this->renderView("admin/global/list/table/table-body.html.twig",[
            "list"=>$posts,
            "fields"=> PostController::POST_FIELDS,
            "entity_name"=>"Post",
            "is_view"=>false,
            "path_edit"=>"app_post_edit",
            'path_delete'=>"app_post_delete",
            "params_link"=>["post_type"=>$post_type],
        ]);
        return $this->json([
            'success'=>true,
            'body_html'=>$table_html,
            'posts_count_tous'=>$posts_count_tous,
            'posts_count_publie'=>$posts_count_publie,
            'posts_count_corbeille'=>$posts_count_corbeille,
            'posts_count_brouillon'=>$posts_count_brouillon,
            'page_count'=>$page_count,
            'page_count_staus_current'=>$page_count_staus_current,
            'message'=>'config mis à jour avec succés'
        ]);
    }
}
