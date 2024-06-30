<?php

namespace App\Controller\Admin\Views;

use Cocur\Slugify\Slugify;
use App\Entity\ModelesPost;
use App\Form\ModelesPostType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ModelesPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/modeles/post', options:["need_permession"=>true,"module"=>"Modéles Post", "method"=>["Afficher","Ajouter","Modifier","Supprimer"]])]
class ModelesPostController extends AbstractController
{
    private $serializer;
    private $slugify;
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        //$this->slugify = new Slugify();
    }

    #[Route('/', name: 'app_modeles_post_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5])]
    public function index(Request $request,ModelesPostRepository $modelesPostRepository): Response
    {
        $list_modeles_posts = $modelesPostRepository->findAll();
        $list_modeles_posts = json_decode($this->serializer->serialize($list_modeles_posts, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        return $this->render('admin/modeles_post/index.html.twig', [
            'modeles_posts' => $list_modeles_posts,
        ]);
    }

    #[Route('/new', name: 'app_modeles_post_new', methods: ['GET', 'POST'], options:["action"=>"Ajouter","order"=>4])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $modelesPost = new ModelesPost();
        $form = $this->createForm(ModelesPostType::class, $modelesPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($modelesPost);
            $entityManager->flush();

            return $this->redirectToRoute('app_modeles_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/modeles_post/new.html.twig', [
            'modeles_post' => $modelesPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_modeles_post_edit', methods: ['GET', 'POST'], options:["action"=>"Modifier","order"=>1], requirements:["id"=>"[0-9]+"])]
    public function edit(Request $request, ModelesPost $modelesPost, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModelesPostType::class, $modelesPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_modeles_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/modeles_post/edit.html.twig', [
            'modeles_post' => $modelesPost,
            'form' => $form,
            
            'fields'=>json_decode($modelesPost->getFields(),true),
        ]);
    }

    #[Route('/{id}', name: 'app_modeles_post_delete', methods: ['POST'], options:["action"=>"Supprimer","order"=>3], requirements:["id"=>"[0-9]+"])]
    public function delete(Request $request, ModelesPost $modelesPost, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modelesPost->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($modelesPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_modeles_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
