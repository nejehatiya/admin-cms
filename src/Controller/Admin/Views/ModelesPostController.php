<?php

namespace App\Controller\Admin\Views;

use App\Entity\ModelesPost;
use App\Form\ModelesPostType;
use App\Repository\ModelesPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
#[Route('/modeles/post')]
class ModelesPostController extends AbstractController
{
    private $serializer;
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'app_modeles_post_index', methods: ['GET'])]
    public function index(ModelesPostRepository $modelesPostRepository): Response
    {
        $list_modeles_posts = $modelesPostRepository->findAll();
        $list_modeles_posts = json_decode($this->serializer->serialize($list_modeles_posts, 'json', ['groups' =>[], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        return $this->render('admin/modeles_post/index.html.twig', [
            'modeles_posts' => $list_modeles_posts,
        ]);
    }

    #[Route('/new', name: 'app_modeles_post_new', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_modeles_post_show', methods: ['GET'])]
    public function show(ModelesPost $modelesPost): Response
    {
        return $this->render('admin/modeles_post/show.html.twig', [
            'modeles_post' => $modelesPost,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_modeles_post_edit', methods: ['GET', 'POST'])]
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
        ]);
    }

    #[Route('/{id}', name: 'app_modeles_post_delete', methods: ['POST'])]
    public function delete(Request $request, ModelesPost $modelesPost, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modelesPost->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($modelesPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_modeles_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
