<?php

namespace App\Controller\Admin\Api;

use Cocur\Slugify\Slugify;
use App\Form\PostModalsType;
use App\Entity\Configuration;
use App\Entity\{Redirection};
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

#[Route('/api/redirection')]
class ApiRedirectionController extends AbstractController
{
    private $em;
    private $serializer;
    private $slugify;
    private $url_site;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer,string $url_site)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->url_site = $url_site;
        $this->slugify = new Slugify();
    }
    #[Route('/new', name: 'app_redirection_api_new_config', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        // get vars from request
        $old_root = $request->request->get('old_root');
        $next_root = $request->request->get('new_root');
        $multiple_redirection = json_decode($request->request->get('parsed_data_csv'),true);
        if(!empty($multiple_redirection)){
            foreach($multiple_redirection as $key=>$value){
                //var_dump($key);var_dump($value);exit;
                if(array_key_exists('old_root',$value) && array_key_exists('new_root',$value)){
                    $old_root =  str_replace($this->url_site,'',$value['old_root']);
                    $next_root = str_replace($this->url_site,'',$value['new_root']);
                    $old_root = $this->slugify->slugify($old_root);
                    $next_root = $this->slugify->slugify($next_root);
                    if($old_root !== $next_root){ 
                        // check old root existance
                        $check_old_root = $this->em->getRepository(Redirection::class)->findOneBy(array('old_root'=>$old_root));
                        if(empty($check_old_root)){
                            // create new Redirection
                            $new_redirection = new Redirection();
                            $new_redirection->setOldRoot($old_root);
                            $new_redirection->setNewRoot($next_root);
                            $this->em->persist($new_redirection);
                        }else{
                            // update redirection
                            $check_old_root->setOldRoot($old_root);
                            $check_old_root->setNewRoot($next_root);
                        }
                    }
                }
            }
            // publish in database
            $this->em->flush();
        }else{
            // remove base url from old and next root
            $old_root =  str_replace($this->url_site,'',$old_root);
            $next_root = $next_root !== "/" ?str_replace($this->url_site,'',$next_root) :  $next_root;
            if($old_root == $next_root){ 
                return $this->json(['success'=>false,'message'=>'on ne peut pas redirigé vers le memem url']);
            }
            $old_root = $this->slugify->slugify($old_root);
            $next_root = $this->slugify->slugify($next_root);
            // check old root existance
            $check_old_root = $this->em->getRepository(Redirection::class)->findOneBy(array('old_root'=>$old_root));
            if(empty($check_old_root)){
                // create new Redirection
                $new_redirection = new Redirection();
                $new_redirection->setOldRoot($old_root);
                $new_redirection->setNewRoot($next_root);
                $this->em->persist($new_redirection);
            }else{
                // update redirection
                $check_old_root->setOldRoot($old_root);
                $check_old_root->setNewRoot($next_root);
            }
            // publish in database
            $this->em->flush();
        }
        
        return $this->json(['success'=>true,'message'=>'redirection mis à jour avec succés']);
    }

    #[Route('/edit-redirection/{id}', name: 'app_redirection_api_edit_config_test', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // check redirection existance
        $check_root = $this->em->getRepository(Redirection::class)->find((int)$id);
        if(empty($check_root)){
            return $this->json(['success'=>false,'message'=>'redirection n\' existe pas']);
        }
        // get vars from request
        $old_root = $request->request->get('old_root');
        $next_root = $request->request->get('new_root');

        // remove base url from old and next root
        $old_root =  str_replace($this->url_site,'',$old_root);
        $next_root =  $next_root !== "/" ?str_replace($this->url_site,'',$next_root) :  $next_root;


        $old_root = $this->slugify->slugify($old_root);
        $next_root = $this->slugify->slugify($next_root);
        if($old_root == $next_root){ 
            return $this->json(['success'=>false,'message'=>'on ne peut pas redirigé vers le memem url']);
        }
        // check old root existance
        $check_old_root = $this->em->getRepository(Redirection::class)->findOneByOldExceptOne($old_root,$id);
        if(empty($check_old_root)){
            // update redirection
            $check_root->setOldRoot($old_root);
            $check_root->setNewRoot($next_root);
        }else{
            return $this->json(['success'=>false,'message'=>'une autre redirection  existe pour cette url']);
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>'config redirection à jour avec succés']);
    }


    

    #[Route('/check-url-old', name: 'app_redirection_api_check_config', methods: ['POST'])]
    public function indexCheckOldRoot(Request $request): Response
    {
        // get vars from request
        $id = (int)$request->request->get('id');
        
        $old_root = $request->request->get('old_root');
        $old_root =  str_replace($this->url_site,'',$old_root);

        $old_root = $this->slugify->slugify($old_root);
        // check old root existance
        $check_old_root = $this->em->getRepository(Redirection::class)->findOneByOldExceptOne($old_root,$id);
        if(!empty($check_old_root)){
            return $this->json(['success'=>false,'message'=>'une autre redirection  existe pour cette url']);
        }
        //
        return $this->json(['success'=>true,'message'=>'old url autorisé']);
    }
}
