<?php

namespace App\Controller\Admin\Api;

use App\Entity\{Menu,Images,PostType,PostMetaFields};
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
#[Route('/api/menu-builder')]
class ApiMenuBuilderController extends AbstractController
{
    private $em;
    private $serializer;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'app_menu_builder_check_index_ss', methods: ['POST','GET'])]
    public function indexcGetItemMenu(Request $request): Response
    {
        $menu_item =  json_decode($request->request->get('menu-item'),true);
        $format = array('titre'=>$menu_item[0]['menu-item-title'],'type'=>$menu_item[0]['menu-item-type'],'url'=>$menu_item[0]['menu-item-url']);
        $meny_item_html = $this->renderView('admin/menu/_partials/menu-structure/element-item.html.twig',[
            'menu_item'=>$format,
            'item'=>uniqid().'_'.strtotime("now"),
        ]);
        return $this->json(['success'=>true,'menu_item'=>$meny_item_html]);
    }
    #[Route('/check-name', name: 'app_menu_builder_check_name', methods: ['POST'])]
    public function indexCheckName(Request $request): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(Menu::class)->findByName($name,$id);
        return $this->json(['success'=>empty($check_name),'message'=>empty($check_name)?'menu name autorisé':'taxonomy name existe déja']);
    }
    
    #[Route('/new', name: 'app_menu_builder_new_yyy', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        return $this->indexEdit($request,null);
    }
    #[Route('/edit/{id}', name: 'app_menu_builder_edit_dscsdcsd_test', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get slug from request
        // get blocks from request
        $json_menu = $request->request->get('json_menu');
        // check if name is existe
        $check_name = $this->em->getRepository(Menu::class)->findByName($name,$id);
        if(!empty($check_name)){
            return $this->json(['success'=>false,'message'=>'menu name existe déja']);
        }
        // start modele post creation
        $menu  = $id ?  $this->em->getRepository(Menu::class)->find($id) : new Menu() ;
        if($id && empty($menu )){
            return $this->json(['success'=>false,'message'=>'menu n\'existe pas']);
        }
        // start set data
        $menu->setNameMenu($name);
        $menu->setMenuContent($json_menu);
        $menu->setStatusMenu(true);
        $menu->setDateUpdate(new \DateTime());
        // is create
        if(!$id){
            $menu->setDateAdd(new \DateTime());
            $this->em->persist($menu );
        }
        // publish in database
        $this->em->flush();
        $html_option = !$id?'<option value="'.$menu->getId().'" selected="selected">'.$menu->getNameMenu().'</option>':'';
        return $this->json(['success'=>true,'html_option'=>$html_option,'message'=>$id ?'menu mis à jour avec succés':'menu créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_menu_builder_get_data_dscsdcsd', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $menu  = $this->em->getRepository(Menu::class)->find($id);
        $emplacement_menu = "";
        foreach($menu->getIdEmplacement() as $emplacement){
            $emplacement_menu .= $emplacement->getKeyEmplacement().",";
        }
        $menu  = json_decode($this->serializer->serialize($menu , 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($menu)){
            return $this->json(['success'=>false,'message'=>'menu n\'existe pas']);
        }
        // html menu edit 
        $html_edit = $this->renderView('admin/menu/_partials/menu-structure/elements.html.twig',['menu_item'=>$menu]);
        return $this->json(['success'=>true,'emplacement_menu'=>$emplacement_menu,'html_edit'=>$html_edit,'menu'=>$menu ]);
    }

    #[Route('/forms/{id}', name: 'app_menu_builder_forms_dscsdcsd', methods: ['POST','GET'])]
    public function indexFormsData(Request $request,$id): Response
    {
        $data_set = json_decode($request->request->get('data_set'),true);
        //var_dump($data_set);exit;
        // get modele post
        $forms  = $this->em->getRepository(PostMetaFields::class)->find($id);
        if(empty($forms)){
            return $this->json(['success'=>false,'message'=>'template menu n\'existe pas']);
        }
        // html menu edit 
        $html_form = $this->renderView('admin/global/forms/forms-builder.html.twig',['fields'=>json_decode($forms->getFields()),'data_set'=>$data_set]);
        return $this->json(['success'=>true,'html_form'=>$html_form ]);
    }
}
