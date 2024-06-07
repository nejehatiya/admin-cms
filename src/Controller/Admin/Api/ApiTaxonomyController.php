<?php

namespace App\Controller\Admin\Api;

use App\Entity\{Taxonomy,Images,PostType};
use App\Form\PostModalsType;
use App\Repository\PostModalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Cocur\Slugify\Slugify;

#[Route('/api/taxonomy')]
class ApiTaxonomyController extends AbstractController
{
    private $em;
    private $serializer;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
    }
    #[Route('/check-name', name: 'app_post_taxonomy_check_index', methods: ['POST'])]
    public function indexCheckName(Request $request): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(Taxonomy::class)->findByName($name,$id);
        return $this->json(['success'=>empty($check_name),'message'=>empty($check_name)?'modéle name autorisé':'taxonomy name existe déja']);
    }

    #[Route('/check-slug', name: 'app_taxonomy_check_slug', methods: ['POST'])]
    public function indexCheckSlug(Request $request): Response
    {
        $slugify = new Slugify();
        // get name from request
        $slug = $slugify->slugify(strip_tags($request->request->get('slug')));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(Taxonomy::class)->findBySlug($slug,$id);
        return $this->json(['success'=>empty($check_name),'slug'=>$slug,'message'=>empty($check_name)?'post type  autorisé':'post type existe déja']);
    }
    
    #[Route('/new', name: 'app_taxonomy_new_yyy', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        return $this->indexEdit($request,null);
    }
    #[Route('/edit/{id}', name: 'app_taxonomy_edit_dscsdcsd_test', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get slug from request
        $slug = strip_tags($request->request->get('slug'));
        // get post type from request
        $post_type = $request->request->has('post_type')?json_decode($request->request->get('post_type'),true):[];
        // get blocks from request
        $status_in_sidebar = $request->request->get('status_in_sidebar');
        $taxonomy_is_draft = $request->request->get('taxonomy_is_draft');
        $taxonomy_statutMenu = $request->request->get('taxonomy_statutMenu');
        $order = (int)$request->request->get('order');
        // get blocks from request
        $description = $request->request->get('description');
        // check if name is existe
        $check_name = $this->em->getRepository(Taxonomy::class)->findByName($name,$id);
        if(!empty($check_name)){
            return $this->json(['success'=>false,'message'=>'taxonomy name existe déja']);
        }
        // check if name is existe
        $check_slug = $this->em->getRepository(Taxonomy::class)->findBySlug($slug,$id);
        if(!empty($check_slug)){
            return $this->json(['success'=>false,'message'=>'taxonomy slug existe déja']);
        }
        // start modele post creation
        $taxonomy  = $id ?  $this->em->getRepository(Taxonomy::class)->find($id) : new Taxonomy() ;
        if($id && empty($taxonomy )){
            return $this->json(['success'=>false,'message'=>'taxonomy n\'existe pas']);
        }
        // start set data
        $taxonomy->setNameTaxonomy($name);
        $taxonomy->setSlugTaxonomy($slug);
        $taxonomy->setDescriptionTaxonomy($description);
        $taxonomy->setParentTaxonomy("0");
        $taxonomy->setOrderTaxonomy($order);
        $taxonomy->setStatutSideBar($status_in_sidebar);
        $taxonomy->setIsDraft($taxonomy_is_draft);
        $taxonomy->setStatutMenu($taxonomy_statutMenu);
        // add new post types
        if(is_array($post_type) && !empty($post_type)){
            // check if post type existe
            $check_existence = $this->em->getRepository(PostType::class)->find((int)$post_type[0]);
            if($check_existence){
                $taxonomy->setPosttype($check_existence);
            }
        }else if((int)$post_type){
            // check if post type existe
            $check_existence = $this->em->getRepository(PostType::class)->find((int)$post_type);
            if($check_existence){
                $taxonomy->setPosttype($check_existence);
            }
            
        }
        // is create
        if(!$id){
            $taxonomy->setDate(new \DateTime());
            $this->em->persist($taxonomy );
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>$id ?'taxonomy mis à jour avec succés':'taxonomy créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_taxonomy_edit_dscsdcsd', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $taxonomy  = $this->em->getRepository(Taxonomy::class)->find($id);
        $taxonomy  = json_decode($this->serializer->serialize($taxonomy , 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($taxonomy )){
            return $this->json(['success'=>false,'message'=>'taxonomy n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'template_page'=>$taxonomy ]);
    }
}
