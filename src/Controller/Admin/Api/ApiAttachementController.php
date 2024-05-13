<?php

namespace App\Controller\Admin\Api;

use App\Entity\{ModelesPost,Images,PostType};
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
use App\Service\{UploadAttachement,Links};
#[Route('/api/attachement')]
class ApiAttachementController extends AbstractController
{
    private $em;
    private $serializer;
    private $upload_file_service;
    private $link;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer,UploadAttachement $upload_file_service,Links $link)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->upload_file_service = $upload_file_service;
        $this->link = $link;
    }
    #[Route('/upload-file', name: 'app_attachement_upload_file', methods: ['POST'])]
    public function indexUploadAttachement(Request $request): Response
    {
        // get file from request
        $file = $request->files->get('file');
        // upload image
        $image = $this->upload_file_service->uploadImage($file);
        // get image from preview
        $preview_image = $this->renderView('admin/media/_partials/uploader-item.html.twig', [
            'item' => $image,
        ]);
        // return response
        return $this->json([
            'success'=>$image?true:false,
            'message'=>$image?"fichier téléverser avec succés":"errure lors de téléversement de fichier",
            'preview_image'=>$preview_image
        ]);
    }

    #[Route('/get-data/{id}', name: 'app_attachement_get_data', methods: ['GET'])]
    public function indexGetDataAttachement(Request $request,$id): Response
    {
        // recuperer l'image dans le base de données
        $image = $this->em->getRepository(Images::class)->find($id);
        // recupére popup edit form
        $popup_form = $this->renderView('admin/media/_partials/uploader-popup-edit.html.twig', [
            'item' => $image,
        ]);
        // return response
        return $this->json([
            'success'=>$image?true:false,
            'message'=>$image?"fichier recupérer avec succés":"fichier non trouvé",
            'image'=>$image,
            'url'=>$this->link->getLinkImage($image),
            'popup_form'=>$popup_form,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_attachement_edit', methods: ['POST'])]
    public function indexEditAttachement(Request $request,$id): Response
    {
        // recuperer l'image dans le base de données
        $image = $this->em->getRepository(Images::class)->find($id);
        // recupére popup edit form
        if($image){
            // get parametres
            $params = $request->request->all();
            // update alt image
            if(array_key_exists('alt_image',$params)){
                $image->setAltImage(strip_tags($params['alt_image']));
            }
            // update titre image
            if(array_key_exists('name_image',$params)){
                $image->setNameImage(strip_tags($params['name_image']));
            }
            // update description image
            if(array_key_exists('description_image',$params)){
                $image->setDescriptionImage(strip_tags($params['description_image']));
            }
            // update in data base
            $this->em->flush();
        }
        // return response
        return $this->json([
            'success'=>$image?true:false,
            'message'=>$image?"fichier recupérer avec succés":"fichier non trouvé",
            'image'=>$image,
        ]);
    }
    #[Route('/list/{page}', name: 'app_attachement_list', methods: ['POST'])]
    public function indexListAttachement(Request $request,$page): Response
    {
        // int page number
        $page = (int)$page?$page:1;
        // get parametres
        $params = $request->request->all();
        // recuperer l'image dans le base de données
        $images = $this->em->getRepository(Images::class)->getListImages($page,$params);
        // get items html
        $list_html = $this->renderView('admin/media/_partials/uploader-item-ajax.html.twig', [
            'images' => $images,
        ]);
        
        // return response
        return $this->json([
            'success'=>!empty($images)?true:false,
            'message'=>!empty($images)?"list recupérer avec succés":"list est vide",
            'list_html'=>$list_html,
        ]);
    }
    #[Route('/popup-media-selection', name: 'app_attachement_popup_slection', methods: ['GET'])]
    public function indexPoppupSelectionAttachement(Request $request): Response
    {
        // recuperer l'image dans le base de données
        $images = $this->em->getRepository(Images::class)->getListImages(1,[]);
        // get items html
        $popup_media_select = $this->renderView('admin/global/media/popup-media-select.html.twig', [
            'images' => $images,
        ]);
        
        // return response
        return $this->json([
            'success'=>true,
            'message'=>"popup recupérer avec succés",
            'popup_media_select'=>$popup_media_select,
        ]);
    }
    
}
