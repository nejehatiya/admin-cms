<?php

namespace App\Controller\Admin\Api;

use App\Entity\{PostMetaFields,Images,PostType};
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
#[Route('/api/acf-meta')]
class ApiAcfMetaController extends AbstractController
{
    private $em;
    private $serializer;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
    }
    #[Route('/check-name', name: 'app_acf_meta_check_index_ss', methods: ['POST'])]
    public function indexCheckName(Request $request): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(PostMetaFields::class)->findByName($name,$id);
        return $this->json(['success'=>empty($check_name),'message'=>empty($check_name)?'modéle name autorisé':'modélé name existe déja']);
    }
    
    #[Route('/new', name: 'app_acf_meta_new_yyy_ss', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        return $this->indexEdit($request,null);
    }
    #[Route('/edit/{id}', name: 'app_acf_meta_edit_dscsdcsd_test_dd', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get post type from request
        $post_type = $request->request->has('post_type')?json_decode($request->request->get('post_type'),true):[];
        
        // get image from request
        $image = (int)$request->request->get('image');
        
        // get blocks from request
        $blocks = json_decode($request->request->get('blocks'),true);
        //$blocks = array_map('htmlspecialchars', $blocks);
        // get blocks from request
        $status = $request->request->get('status');
        // check if name is existe
        $check_name = $this->em->getRepository(PostMetaFields::class)->findByName($name,$id);
        if(!empty($check_name)){
            return $this->json(['success'=>true,'message'=>'modélé name existe déja']);
        }
        // start modele post creation
        $acf_meta = $id ?  $this->em->getRepository(PostMetaFields::class)->find($id) : new PostMetaFields() ;
        if($id && empty($acf_meta)){
            return $this->json(['success'=>false,'message'=>'modélé n\'existe pas']);
        }
        // start set data
        $acf_meta->setName($name);
        $acf_meta->setFields(json_encode($blocks, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $acf_meta->setStatus($status);
        // start post type collection
        if($id){
            $old_used_in_list = $acf_meta->getPostType();
            foreach($old_used_in_list as $use_in){
                $acf_meta->removePostType($use_in);
            }
        }
        // add new post types
        if(is_array($post_type) && !empty($post_type)){
            foreach($post_type as $item ){
                // check if post type existe
                $check_existence = $this->em->getRepository(PostType::class)->find($item);
                if($check_existence){
                    $acf_meta->addPostType($check_existence);
                }
            }
        }
        // is create
        if(!$id){
            $acf_meta->setDate(new \DateTime());
            $this->em->persist($acf_meta);
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>$id ?'modèle mis à jour avec succés':'modèle créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_acf_meta_edit_dscsdcsd_ccc', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $acf_meta = $this->em->getRepository(PostMetaFields::class)->find($id);
        $acf_meta = json_decode($this->serializer->serialize($acf_meta, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($acf_meta)){
            return $this->json(['success'=>false,'message'=>'modélé n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'model_post'=>$acf_meta]);
    }
}
