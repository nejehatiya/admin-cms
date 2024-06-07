<?php

namespace App\Controller\Admin\Views;

use App\Entity\Emplacement;
use App\Form\EmplacementType;
use App\Repository\EmplacementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
#[Route('/emplacement')]
class EmplacementController extends AbstractController
{
    private $serializer;
    private $slugify;
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        //$this->slugify = new Slugify();
    }
    #[Route('/', name: 'app_emplacement_index', methods: ['GET'])]
    public function index(EmplacementRepository $emplacementRepository): Response
    {
        $emplacements = $emplacementRepository->findAll();
        $emplacements = json_decode($this->serializer->serialize($emplacements, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        return $this->render('admin/emplacement/index.html.twig', [
            'emplacements' => $emplacements,
        ]);
    }

    #[Route('/new', name: 'app_emplacement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emplacement = new Emplacement();
        $form = $this->createForm(EmplacementType::class, $emplacement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emplacement);
            $entityManager->flush();

            return $this->redirectToRoute('app_emplacement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/emplacement/new.html.twig', [
            'emplacement' => $emplacement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emplacement_show', methods: ['GET'])]
    public function show(Emplacement $emplacement): Response
    {
        return $this->render('admin/emplacement/show.html.twig', [
            'emplacement' => $emplacement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emplacement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emplacement $emplacement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmplacementType::class, $emplacement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emplacement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/emplacement/edit.html.twig', [
            'emplacement' => $emplacement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emplacement_delete', methods: ['POST'])]
    public function delete(Request $request, Emplacement $emplacement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emplacement->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($emplacement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emplacement_index', [], Response::HTTP_SEE_OTHER);
    }
}
