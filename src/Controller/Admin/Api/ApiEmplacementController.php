<?php

namespace App\Controller\Admin\Api;

use App\Entity\{Emplacement,Menu,PostType};
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

#[Route('/api/emplacement')]
class ApiEmplacementController extends AbstractController
{
    private $em;
    private $serializer;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
    }
    #[Route('/check-name', name: 'app_post_emplacement_check_index', methods: ['POST'])]
    public function indexCheckName(Request $request): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(Emplacement::class)->findByName($name,$id);
        return $this->json(['success'=>empty($check_name),'message'=>empty($check_name)?'Emplacement name autorisé':'taxonomy name existe déja']);
    }
    
    #[Route('/new', name: 'app_emplacement_new_yyy', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        return $this->indexEdit($request,null);
    }
    #[Route('/edit/{id}', name: 'app_emplacement_edit_dscsdcsd_test', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get post type from request
        $emplacement_menu = (int)$request->request->get('emplacement_menu');
        // get blocks from request
        $emplacement_status = $request->request->get('emplacement_status');
        
        // check if name is existe
        $check_name = $this->em->getRepository(Emplacement::class)->findByName($name,$id);
        if(!empty($check_name)){
            return $this->json(['success'=>false,'message'=>'Emplacement name existe déja']);
        }
        // start modele post creation
        $emplacement  = $id ?  $this->em->getRepository(Emplacement::class)->find($id) : new Emplacement() ;
        if($id && empty($emplacement )){
            return $this->json(['success'=>false,'message'=>'Emplacement n\'existe pas']);
        }
        // start set data
        $emplacement->setKeyEmplacement($name);
        $emplacement->setStatus($emplacement_status);
        // add new post types
        if($emplacement_menu){
            // check if post type existe
            $check_menu = $this->em->getRepository(Menu::class)->find((int)$emplacement_menu);
            if($check_menu){
                $emplacement->setMenu($check_menu);
            }
        }else{
            $emplacement->setMenu(null);
        }
        $emplacement->setDateUpd(new \DateTime());
        // is create
        if(!$id){
            $emplacement->setDateAdd(new \DateTime());
            $this->em->persist($emplacement );
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>$id ?'emplacement mis à jour avec succés':'emplacement créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_emplacement_edit_dscsdcsd', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $emplacement  = $this->em->getRepository(Emplacement::class)->find($id);
        $emplacement  = json_decode($this->serializer->serialize($emplacement , 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($emplacement )){
            return $this->json(['success'=>false,'message'=>'emplacement n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'emplacement'=>$emplacement ]);
    }
}
