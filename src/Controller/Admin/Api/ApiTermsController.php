<?php

namespace App\Controller\Admin\Api;

use App\Entity\{Taxonomy,Images,PostType,Terms};

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
use App\Twig\Functions\TwigAdminFunctions;

#[Route('/api/terms')]
class ApiTermsController extends AbstractController
{
    private $em;
    private $serializer;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
    }
    #[Route('/check-name', name: 'app_post_terms_check_index', methods: ['POST'])]
    public function indexCheckName(Request $request): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(Terms::class)->findByName($name,$id);
        return $this->json(['success'=>empty($check_name),'message'=>empty($check_name)?'modéle name autorisé':'taxonomy name existe déja']);
    }

    #[Route('/check-slug', name: 'app_terms_check_slug', methods: ['POST'])]
    public function indexCheckSlug(Request $request): Response
    {
        $slugify = new Slugify();
        // get name from request
        $slug = $slugify->slugify(strip_tags($request->request->get('slug')));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(Terms::class)->findBySlug($slug,$id);
        return $this->json(['success'=>empty($check_name),'slug'=>$slug,'message'=>empty($check_name)?'post type  autorisé':'post type existe déja']);
    }
    
    #[Route('/new', name: 'app_terms_new_yyy', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        return $this->indexEdit($request,null);
    }
    #[Route('/edit/{id}', name: 'app_terms_edit_dscsdcsd_test', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get slug from request
        $slug = strip_tags($request->request->get('slug'));
        // get blocks from request
        $parent = (int)$request->request->get('parent');
        $image = (int)$request->request->get('media_selected');
        $terms_is_draft = $request->request->get('terms_is_draft');
        // check taxonomy
        
        $id_taxo = (int)$request->request->get('id_taxo');
        $check_taxo =  $id_taxo ? $this->em->getRepository(Taxonomy::class)->find($id_taxo) : array();
        if(empty($check_taxo)){
            return $this->json(['success'=>false,'message'=>'taxonomy n\'existe pas']);
        }
        // get blocks from request
        $description = $request->request->get('description');
        // check if name is existe
        $check_name = $this->em->getRepository(Terms::class)->findByName($name,$id);
        
        if(!empty($check_name)){
            return $this->json(['success'=>false,'message'=>'taxonomy name existe déja']);
        }
        // check if name is existe
        $check_slug = $this->em->getRepository(Terms::class)->findBySlug($slug,$id);
        if(!empty($check_slug)){
            return $this->json(['success'=>false,'message'=>'taxonomy slug existe déja']);
        }
        // start modele post creation
        $trem  = $id ?  $this->em->getRepository(Terms::class)->find($id) : new Terms() ;
        if($id && empty($trem )){
            return $this->json(['success'=>false,'message'=>'taxonomy n\'existe pas']);
        }
        // start set data
        $trem->setNameTerms($name);
        $trem->setSlugTerms($slug);
        $trem->setDescriptionTerms($description);
        $trem->setParentTerms($parent);
        $trem->setIdTaxonomy($check_taxo);
        $trem->setIsDraft($terms_is_draft);
        if($image){
            $image = $this->em->getRepository(Images::class)->find($image);
            if(!empty($image)){
                $trem->setImage($image);
            }
        }
        // is create
        if(!$id){
            $trem->setDate(new \DateTime());
            $this->em->persist($trem);
        }
        // publish in database
        $this->em->flush();
        // get item html
        $trem = json_decode($this->serializer->serialize($trem, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        $tr_html = $this->renderView('admin/terms/_partials/tr-item.html.twig',['entity_name'=>'Terms','is_view'=>false,'path_edit'=>'app_terms_edit','item'=>$trem,'params_link'=>["taxonomy"=>$check_taxo->getSlugTaxonomy()]]);
        return $this->json(['success'=>true,'tr_html'=>$tr_html,'message'=>$id ?'term mis à jour avec succés':'term créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_terms_edit_dscsdcsd', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $term  = $this->em->getRepository(Terms::class)->find($id);
        $image = $term->getImage();
        $term  = json_decode($this->serializer->serialize($term , 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($term )){
            return $this->json(['success'=>false,'message'=>'term n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'term'=>$term,'image_preview'=> TwigAdminFunctions::getImagesUrl($image,300) ]);
    }
}