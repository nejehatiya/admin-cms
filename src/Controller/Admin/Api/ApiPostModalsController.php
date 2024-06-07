<?php

namespace App\Controller\Admin\Api;

use Cocur\Slugify\Slugify;
use App\Form\PostModalsType;
use App\Repository\PostModalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Entity\{ModelesPost,Images,PostType};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/api/post/modals')]
class ApiPostModalsController extends AbstractController
{
    private $em;
    private $serializer;
    private $modele_post_folder;
    private $assets_front_directory;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer,string $modele_post_folder,string $assets_front_directory)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->modele_post_folder = $modele_post_folder;
        $this->assets_front_directory = $assets_front_directory;
    }
    #[Route('/check-name', name: 'app_post_modals_check_index_ss', methods: ['POST'])]
    public function indexCheckName(Request $request): Response
    {
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_name = $this->em->getRepository(ModelesPost::class)->findByName($name,$id);
        return $this->json(['success'=>empty($check_name),'message'=>empty($check_name)?'modéle name autorisé':'modélé name existe déja']);
    }
    
    #[Route('/new', name: 'app_post_modals_new_yyy_ss', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        //var_dump($this->modele_post_folder);exit;
        return $this->indexEdit($request,null);
    }
    #[Route('/edit/{id}', name: 'app_post_modals_edit_dscsdcsd_test_dd', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // init slugify 
        $slugify = new Slugify();
        // get name from request
        $name = strip_tags($request->request->get('name'));
        // get name from request
        $class_sortable = strip_tags($request->request->get('class_sortable'));
        // get post type from request
        $post_type = $request->request->has('post_type')?json_decode($request->request->get('post_type'),true):[];
        
        // get image from request
        $image = (int)$request->request->get('image');
        // json format
        $data_form = $request->request->get('data_form');
        // get blocks from request
        $blocks = json_decode($request->request->get('blocks'),true);
        //$blocks = array_map('htmlspecialchars', $blocks);
        // get blocks from request
        $status = $request->request->get('status');
        // get tag new from request
        $is_new  = $request->request->get('is_new');
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
        // get old name before update proccess
        $old_name = $old_class_sortable = '';
        if($id){
            $old_name = $model_post->getNameModele();
            $old_class_sortable = $model_post->getClassSortable(); 
        }
        // start set data
        $model_post->setNameModele($name);
        $model_post->setFields(json_encode($blocks, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $model_post->setStatusModele($status);
        $model_post->setiSNew($is_new);
        $model_post->setDateUpd(new \DateTime());
        $model_post->setClassSortable($slugify->slugify($class_sortable));
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
        // create file in folder (file twig + file css + file js )
        $filesystem = new Filesystem();
        // is create
        if(!$id){
            // create file twig
            $mdele_template='modele-'.$slugify->slugify($name).'.html.twig';
            $file_path_modele = $this->modele_post_folder.$mdele_template;
            $file_existe=$filesystem->exists($file_path_modele);
            
            if(!$file_existe){
                $filesystem->dumpFile($file_path_modele,'{# SECTION_START #}{# '.$data_form.' #}{# SECTION_END #}');
            }
            // create file css and js
            $css = $this->assets_front_directory.'css/modeles/'.$slugify->slugify($class_sortable).'.css';
            $js = $this->assets_front_directory.'js/modeles/'.$slugify->slugify($class_sortable).'.js';
            if(!$filesystem->exists($css)){
                $filesystem->dumpFile($css,'');
            }
            if(!$filesystem->exists($js)){
                $filesystem->dumpFile($js,'');
            }
        }else{

            // Define the section to update
            $sectionStart = '{# SECTION_START #}';
            $sectionEnd = '{# SECTION_END #}';
            $newComment = '{# '.$data_form.' #}';
            // update file
            $mdele_template='modele-'.$slugify->slugify($name).'.html.twig';
            $file_path_modele = $this->modele_post_folder.$mdele_template;
            // modele old name
            $file_old_path_modele = $this->modele_post_folder.'modele-'.$slugify->slugify($old_name).'.html.twig';
            $file_existe = $filesystem->exists($file_old_path_modele);
            if($file_existe){
                // Read the content of the Twig file
                $content = file_get_contents($file_old_path_modele);
                // Check if the section exists in the file
                $startPos = strpos($content, $sectionStart);
                $endPos = strpos($content, $sectionEnd, $startPos);
                if ($startPos !== false && $endPos !== false) {
                    // Update the section comment
                    $beforeSection = substr($content, 0, $startPos + strlen($sectionStart));
                    $afterSection = substr($content, $endPos);

                    $updatedContent = $beforeSection . "\n" . $newComment . "\n" . $afterSection;

                    // Write the updated content back to the file
                    $filesystem->dumpFile($file_old_path_modele, $updatedContent);
                }else{
                    // Update the section comment
                    $beforeSection = substr($content, 0, $startPos + strlen($sectionStart));
                    $afterSection = substr($content, $endPos);

                    $updatedContent = $sectionStart . "\n" . $newComment . "\n" .$sectionEnd . "\n" .$content;

                    // Write the updated content back to the file
                    $filesystem->dumpFile($file_old_path_modele, $updatedContent);
                }
                // Define the section to update
                if($file_old_path_modele!=$file_path_modele){
                    $filesystem->rename($file_old_path_modele, $file_path_modele);
                }
            }else{
                $filesystem->dumpFile($file_path_modele,'{# SECTION_START #}{# '.$data_form.' #}{# SECTION_END #}');
            }
            // update css file and js
            $css = $this->assets_front_directory.'css/modeles/'.$slugify->slugify($class_sortable).'.css';
            $css_old = $old_class_sortable ? $this->assets_front_directory.'css/modeles/'.$slugify->slugify($old_class_sortable).'.css':'';
            $js = $this->assets_front_directory.'js/modeles/'.$slugify->slugify($class_sortable).'.js';
            $js_old = $old_class_sortable ? $this->assets_front_directory.'js/modeles/'.$slugify->slugify($old_class_sortable).'.js':'';

            if($filesystem->exists($css_old) && strlen($css_old)){
                if($css_old!=$css){
                    $filesystem->rename($css_old, $css);
                }
            }else{
                $filesystem->dumpFile($css,'');
            }
            if($filesystem->exists($js_old) && strlen($js_old)){
                if($js_old!=$js){
                    $filesystem->rename($js_old, $js);
                }
            }else{
                $filesystem->dumpFile($js,'');
            }
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>$id ?'modèle mis à jour avec succés':'modèle créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_post_modals_edit_dscsdcsd_ccc', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $model_post = $this->em->getRepository(ModelesPost::class)->find($id);
        $model_post = json_decode($this->serializer->serialize($model_post, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($model_post)){
            return $this->json(['success'=>false,'message'=>'modélé n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'model_post'=>$model_post]);
    }

    #[Route('/forms-post-get', name: 'app_post_modals_forms_get', methods: ['POST','GET'])]
    public function indexFormsData(Request $request): Response
    {
        $fields = json_decode($request->request->get('fields'),true);
        // html menu edit 
        $html_form = $this->renderView('admin/global/forms/forms-builder.html.twig',['fields'=>$fields,'data_set'=>[]]);
        return $this->json(['success'=>true,'html_form'=>$html_form ]);
    }
}
