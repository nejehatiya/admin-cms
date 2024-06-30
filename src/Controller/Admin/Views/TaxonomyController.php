<?php

namespace App\Controller\Admin\Views;

use App\Entity\Taxonomy;
use App\Form\TaxonomyType;
use App\Repository\TaxonomyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('/taxonomy', options:["need_permession"=>true,"module"=>"Taxonomy", "method"=>["Afficher","Ajouter","Modifier","Supprimer"]])]
class TaxonomyController extends AbstractController
{
    private $serializer;
    private $slugify;
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        //$this->slugify = new Slugify();
    }

    #[Route('/', name: 'app_taxonomy_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5])]
    public function index(TaxonomyRepository $taxonomyRepository): Response
    {
        $taxonomies = $taxonomyRepository->findAll();
        $taxonomies = json_decode($this->serializer->serialize($taxonomies, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        return $this->render('admin/taxonomy/index.html.twig', [
            'taxonomies' => $taxonomies,
        ]);
    }

    #[Route('/new', name: 'app_taxonomy_new', methods: ['GET', 'POST'], options:["action"=>"Ajouter","order"=>4])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $taxonomy = new Taxonomy();
        $form = $this->createForm(TaxonomyType::class, $taxonomy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($taxonomy);
            $entityManager->flush();

            return $this->redirectToRoute('app_taxonomy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/taxonomy/new.html.twig', [
            'taxonomy' => $taxonomy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_taxonomy_edit', methods: ['GET', 'POST'], options:["action"=>"Modifier","order"=>1], requirements:["id"=>"[0-9]+"])]
    public function edit(Request $request, Taxonomy $taxonomy, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaxonomyType::class, $taxonomy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_taxonomy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/taxonomy/edit.html.twig', [
            'taxonomy' => $taxonomy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_taxonomy_delete', methods: ['POST'], options:["action"=>"Supprimer","order"=>3], requirements:["id"=>"[0-9]+"])]
    public function delete(Request $request, Taxonomy $taxonomy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taxonomy->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($taxonomy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_taxonomy_index', [], Response::HTTP_SEE_OTHER);
    }
}
