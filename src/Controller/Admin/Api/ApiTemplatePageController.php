<?php

namespace App\Controller\Admin\Api;

use App\Entity\{TemplatePage,Images,PostType};
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

#[Route('/api/template-page')]
class ApiTemplatePageController extends AbstractController
{
    private $em;
    private $serializer;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
    }
    #[Route('/check-name', name: 'app_post_template_page_check_index', methods: ['POST'])]
    public function indexCheckName(Request $request): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(TemplatePage::class)->findByName($name,$id);
        return $this->json(['success'=>empty($check_name),'message'=>empty($check_name)?'modéle name autorisé':'template page name existe déja']);
    }

    #[Route('/check-slug', name: 'app_template_page_check_slug', methods: ['POST'])]
    public function indexCheckSlug(Request $request): Response
    {
        $slugify = new Slugify();
        // get name from request
        $slug = $slugify->slugify(strip_tags($request->request->get('slug')));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(TemplatePage::class)->findBySlug($slug,$id);
        return $this->json(['success'=>empty($check_name),'slug'=>$slug,'message'=>empty($check_name)?'post type  autorisé':'post type existe déja']);
    }
    
    #[Route('/new', name: 'app_template_page_new_yyy', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        return $this->indexEdit($request,null);
    }
    #[Route('/edit/{id}', name: 'app_template_page_edit_dscsdcsd_test', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get slug from request
        $slug = strip_tags($request->request->get('slug'));
        // get post type from request
        $post_type = $request->request->has('post_type')?json_decode($request->request->get('post_type'),true):[];
        // get blocks from request
        $status = $request->request->get('status');
        // check if name is existe
        $check_name = $this->em->getRepository(TemplatePage::class)->findByName($name,$id);
        if(!empty($check_name)){
            return $this->json(['success'=>false,'message'=>'template page name existe déja']);
        }
        // start modele post creation
        $template_page = $id ?  $this->em->getRepository(TemplatePage::class)->find($id) : new TemplatePage() ;
        if($id && empty($template_page)){
            return $this->json(['success'=>false,'message'=>'template page n\'existe pas']);
        }
        // start set data
        $template_page->setName($name);
        $template_page->setSlug($slug);
        $template_page->setStatus($status);
        // start post type collection
        if($id){
            $old_used_in_list = $template_page->getPostType();
            foreach($old_used_in_list as $use_in){
                $template_page->removePostType($use_in);
            }
        }
        // add new post types
        if(is_array($post_type) && !empty($post_type)){
            foreach($post_type as $item ){
                // check if post type existe
                $check_existence = $this->em->getRepository(PostType::class)->find($item);
                if($check_existence){
                    $template_page->addPostType($check_existence);
                }
            }
        }
        // is create
        if(!$id){
            $template_page->setDate(new \DateTime());
            $this->em->persist($template_page);
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>$id ?'template page mis à jour avec succés':'template page créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_template_page_edit_dscsdcsd', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $template_page = $this->em->getRepository(TemplatePage::class)->find($id);
        $template_page = json_decode($this->serializer->serialize($template_page, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($template_page)){
            return $this->json(['success'=>false,'message'=>'template page n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'template_page'=>$template_page]);
    }
}
