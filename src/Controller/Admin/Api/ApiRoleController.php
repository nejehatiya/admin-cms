<?php

namespace App\Controller\Admin\Api;

use App\Entity\{Roles};
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


#[Route('/api/roles')]
class ApiRoleController extends AbstractController
{
    private $em;
    private $serializer;
    private $slugify;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->slugify = new Slugify();
    }
    #[Route('/check-name', name: 'app_role_check_index_ss', methods: ['POST'])]
    public function indexCheckName(Request $request): Response
    {
        // get name from request
        $name = strtoupper($this->slugify->slugify(strip_tags($request->request->get('name'))));
        $name = 'ROLE_'.str_replace(['ROLE_','-'],['','_'],$name);
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(Roles::class)->findByName($name,$id);
        return $this->json(['success'=>empty($check_name),'message'=>empty($check_name)?'role name autorisé':'role name existe déja']);
    }
    
    #[Route('/new', name: 'app_role_new_yyy_ss', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        return $this->indexEdit($request,null);
    }
    #[Route('/edit-role/{id}', name: 'app_roles_edit_api_test', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // get name from request
        $name = strtoupper($this->slugify->slugify(strip_tags($request->request->get('name'))));
        $name = 'ROLE_'.str_replace(['ROLE_','-'],['','_'],$name);
        // check if name is existe
        $check_name = $this->em->getRepository(Roles::class)->findByName($name,$id);
        if(!empty($check_name)){
            return $this->json(['success'=>true,'message'=>'role name existe déja']);
        }
        // start role creation
        $role = $id ?  $this->em->getRepository(Roles::class)->find($id) : new Roles() ;
        if($id && empty($role)){
            return $this->json(['success'=>false,'message'=>'role n\'existe pas']);
        }
        // start set data
        $role->setRole($name);
        // is create
        if(!$id){
            $this->em->persist($role);
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>$id ?'role mis à jour avec succés':'role créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_role_edit_dscsdcsd_ccc', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $role = $this->em->getRepository(Roles::class)->find($id);
        $role = json_decode($this->serializer->serialize($role, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($role)){
            return $this->json(['success'=>false,'message'=>'role n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'role'=>$role]);
    }
}
