<?php

namespace App\Controller\Admin\Views;

use App\Entity\PostModals;
use App\Form\PostModalsType;
use App\Repository\PostModalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/post/modals')]
class PostModalsController extends AbstractController
{
    #[Route('/', name: 'app_post_modals_index', methods: ['GET'])]
    public function index(Request $request,PostModalsRepository $postModalsRepository): Response
    {
        return $this->render('admin/post_modals/index.html.twig', [
            'post_modals' => $postModalsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_post_modals_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postModal = new PostModals();
        $form = $this->createForm(PostModalsType::class, $postModal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postModal);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_modals_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/post_modals/new.html.twig', [
            'post_modal' => $postModal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_modals_show', methods: ['GET'])]
    public function show(PostModals $postModal): Response
    {
        return $this->render('admin/post_modals/show.html.twig', [
            'post_modal' => $postModal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_modals_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PostModals $postModal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostModalsType::class, $postModal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_modals_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/post_modals/edit.html.twig', [
            'post_modal' => $postModal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_modals_delete', methods: ['POST'])]
    public function delete(Request $request, PostModals $postModal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postModal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($postModal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_modals_index', [], Response::HTTP_SEE_OTHER);
    }
}
