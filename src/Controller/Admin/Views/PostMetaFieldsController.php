<?php

namespace App\Controller\Admin\Views;

use App\Entity\PostMetaFields;
use App\Form\PostMetaFieldsType;
use App\Repository\PostMetaFieldsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('/post-meta-fields', options:["need_permession"=>true,"module"=>"ACF", "method"=>["Afficher","Ajouter","Modifier","Supprimer"]])]
class PostMetaFieldsController extends AbstractController
{
    private $serializer;
    private $slugify;
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        //$this->slugify = new Slugify();
    }
    #[Route('/', name: 'app_post_meta_fields_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5])]
    public function index(PostMetaFieldsRepository $postMetaFieldsRepository): Response
    {
        $post_meta_fields = $postMetaFieldsRepository->findAll();
        $post_meta_fields = json_decode($this->serializer->serialize($post_meta_fields, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        return $this->render('admin/post_meta_fields/index.html.twig', [
            'post_meta_fields' => $post_meta_fields,
        ]);
    }

    #[Route('/new', name: 'app_post_meta_fields_new', methods: ['GET', 'POST'], options:["action"=>"Ajouter","order"=>4])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postMetaField = new PostMetaFields();
        $form = $this->createForm(PostMetaFieldsType::class, $postMetaField);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postMetaField);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_meta_fields_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/post_meta_fields/new.html.twig', [
            'post_meta_field' => $postMetaField,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_meta_fields_edit', methods: ['GET', 'POST'], options:["action"=>"Modifier","order"=>1], requirements:["id"=>"[0-9]+"])]
    public function edit(Request $request, PostMetaFields $postMetaField, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostMetaFieldsType::class, $postMetaField);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_meta_fields_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/post_meta_fields/edit.html.twig', [
            'post_meta_field' => $postMetaField,
            'form' => $form,
            'fields'=>json_decode($postMetaField->getFields(),true),
        ]);
    }

    #[Route('/{id}', name: 'app_post_meta_fields_delete', methods: ['POST'], options:["action"=>"Supprimer","order"=>3], requirements:["id"=>"[0-9]+"])]
    public function delete(Request $request, PostMetaFields $postMetaField, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postMetaField->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($postMetaField);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_meta_fields_index', [], Response::HTTP_SEE_OTHER);
    }
}
