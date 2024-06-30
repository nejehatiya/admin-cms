<?php

namespace App\Controller\Admin\Views;

use App\Entity\Images;
use App\Form\ImagesType;
use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/media', options:["need_permession"=>true,"module"=>"Media", "method"=>["Afficher","Ajouter","Modifier","Supprimer"]])]
class MediaController extends AbstractController
{
    #[Route('/', name: 'app_media_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5])]
    public function index(ImagesRepository $imagesRepository): Response
    {
        return $this->render('admin/media/index.html.twig', [
            'images' => $imagesRepository->getListImages(1),
            'months'=>$imagesRepository->getDateMonth()
        ]);
    }

    #[Route('/new', name: 'app_media_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $image = new Images();
        
        return $this->render('admin/media/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_media_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Images $image, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImagesType::class, $image);
       
        return $this->render('admin/media/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_media_delete', methods: ['POST'])]
    public function delete(Request $request, Images $image, EntityManagerInterface $entityManager): Response
    {
        return $this->redirectToRoute('app_media_index', [], Response::HTTP_SEE_OTHER);
    }
}
