<?php

namespace App\Controller\Admin\Views;

use App\Entity\PostType;
use App\Form\PostTypeType;
use App\Repository\PostTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('/post-type', options:["need_permession"=>true,"module"=>"Post Type", "method"=>["Afficher","Ajouter","Modifier","Supprimer"]])]
class PostTypeController extends AbstractController
{
    private $serializer;
    private $slugify;
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        //$this->slugify = new Slugify();
    }
    #[Route('/', name: 'app_post_type_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5])]
    public function index(PostTypeRepository $postTypeRepository): Response
    {
        $list_post_types = $postTypeRepository->findAll();
        $list_post_types = json_decode($this->serializer->serialize($list_post_types, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        return $this->render('admin/post_type/index.html.twig', [
            'post_types' => $list_post_types,
        ]);
    }

    #[Route('/new', name: 'app_post_type_new', methods: ['GET', 'POST'], options:["action"=>"Ajouter","order"=>4])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postType = new PostType();
        $form = $this->createForm(PostTypeType::class, $postType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postType);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/post_type/new.html.twig', [
            'post_type' => $postType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_type_edit', methods: ['GET', 'POST'], options:["action"=>"Modifier","order"=>1], requirements:["id"=>"[0-9]+"])]
    public function edit(Request $request, PostType $postType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostTypeType::class, $postType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/post_type/edit.html.twig', [
            'post_type' => $postType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_type_delete', methods: ['POST'], options:["action"=>"Supprimer","order"=>3], requirements:["id"=>"[0-9]+"])]
    public function delete(Request $request, PostType $postType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postType->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($postType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
