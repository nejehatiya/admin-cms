<?php

namespace App\Controller\Admin\Views;

use App\Entity\Redirection;
use App\Form\RedirectionType;
use App\Repository\RedirectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('/redirection', options:["need_permession"=>true,"module"=>"Redirection", "method"=>["Afficher","Ajouter","Modifier","Supprimer"]])]
class RedirectionController extends AbstractController
{
    private $em;
    private $serializer;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'app_redirection_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5])]
    public function index(RedirectionRepository $redirectionRepository): Response
    {
        $redirections = $redirectionRepository->findAll();
        $redirections = json_decode($this->serializer->serialize($redirections, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        return $this->render('admin/redirection/index.html.twig', [
            'redirections' => $redirections,
        ]);
    }

    #[Route('/new', name: 'app_redirection_new', methods: ['GET', 'POST'], options:["action"=>"Ajouter","order"=>4])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $redirection = new Redirection();
        $form = $this->createForm(RedirectionType::class, $redirection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($redirection);
            $entityManager->flush();

            return $this->redirectToRoute('app_redirection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/redirection/new.html.twig', [
            'redirection' => $redirection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_redirection_edit', methods: ['GET', 'POST'], options:["action"=>"Modifier","order"=>1], requirements:["id"=>"[0-9]+"])]
    public function edit(Request $request, Redirection $redirection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RedirectionType::class, $redirection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_redirection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/redirection/edit.html.twig', [
            'redirection' => $redirection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_redirection_delete', methods: ['POST'], options:["action"=>"Supprimer","order"=>3], requirements:["id"=>"[0-9]+"])]
    public function delete(Request $request, Redirection $redirection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$redirection->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($redirection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_redirection_index', [], Response::HTTP_SEE_OTHER);
    }
}
