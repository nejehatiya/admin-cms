<?php

namespace App\Controller\Admin\Api;

use App\Entity\{ModelesPost,Images,PostType};
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

#[Route('/api/post-type')]
class ApiPostTypeController extends AbstractController
{
    private $em;
    private $serializer;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
    }
    #[Route('/check-name', name: 'app_post_modals_check_index', methods: ['POST'])]
    public function indexCheckName(Request $request): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(PostType::class)->findByName($name,$id);
        return $this->json(['success'=>empty($check_name),'message'=>empty($check_name)?'modéle name autorisé':'modélé name existe déja']);
    }

    #[Route('/check-slug-post-type', name: 'app_post_type_check_slug_cc', methods: ['POST'])]
    public function indexCheckSlug(Request $request): Response
    {
        $slugify = new Slugify();
        // get name from request
        $slug = $slugify->slugify(strip_tags($request->request->get('slug')));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(PostType::class)->findBySlug($slug,$id);
        return $this->json(['success'=>empty($check_name),'slug'=>$slug,'message'=>empty($check_name)?'post type  autorisé':'post type existe déja']);
    }
    
    #[Route('/new', name: 'app_post_modals_new_yyy', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        return $this->indexEdit($request,null);
    }
    #[Route('/edit/{id}', name: 'app_post_modals_edit_dscsdcsd_test', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get post type from request
        $post_type = $request->request->has('post_type')?$request->request->has('post_type'):[];
        // get image from request
        $image = (int)$request->request->get('image');
        
        // get blocks from request
        $blocks = json_decode($request->request->get('blocks'),true);
        //$blocks = array_map('htmlspecialchars', $blocks);
        // get blocks from request
        $status = $request->request->get('status');
        // check if name is existe
        $check_name = $this->em->getRepository(ModelesPost::class)->findByName($name,$id);
        if(!empty($check_name)){
            return $this->json(['success'=>true,'message'=>'modélé name existe déja']);
        }
        // start modele post creation
        $model_post = $id ?  $this->em->getRepository(ModelesPost::class)->find($id) : new ModelesPost() ;
        if($id && empty($model_post)){
            return $this->json(['success'=>false,'message'=>'modélé n\'existe pas']);
        }
        // start set data
        $model_post->setNameModele($name);
        $model_post->setFields(json_encode($blocks, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $model_post->setStatusModele($status);
        $model_post->setDateUpd(new \DateTime());
        // check if image is existe
        if($image){
            $image = $this->em->getRepository(Images::class)->find($image);
            if($image){
                $model_post->setImagePreview($image);
            }
        }
        // start post type collection
        if($id){
            $old_used_in_list = $model_post->getUsedIn();
            foreach($old_used_in_list as $use_in){
                $model_post->removeUsedIn($use_in);
            }
        }
        // add new post types
        if(is_array($post_type) && !empty($post_type)){
            foreach($post_type as $item ){
                // check if post type existe
                $check_existence = $this->em->getRepository(PostType::class)->find($item);
                if($check_existence){
                    $model_post->addUsedIn($check_existence);
                }
            }
        }
        // is create
        if(!$id){
            $model_post->setDateAdd(new \DateTime());
            $this->em->persist($model_post);
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>$id ?'modèle mis à jour avec succés':'modèle créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_post_modals_edit_dscsdcsd', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $model_post = $this->em->getRepository(ModelesPost::class)->find($id);
        $model_post = json_decode($this->serializer->serialize($model_post, 'json', ['groups' =>[], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($model_post)){
            return $this->json(['success'=>false,'message'=>'modélé n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'model_post'=>$model_post]);
    }
}
