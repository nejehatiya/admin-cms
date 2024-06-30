<?php

namespace App\Controller\Admin\Views;

use App\Entity\{Terms,Taxonomy};
use App\Form\TermsType;
use App\Repository\TermsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('{taxonomy}/terms', options:["need_permession"=>true,"module"=>"Terms", "method"=>["Afficher","Modifier","Supprimer"]])]
class TermsController extends AbstractController
{
    private $serializer;
    private $slugify;
    private $em;
    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ) {
        $this->serializer = $serializer;
        $this->em = $em;
        //$this->slugify = new Slugify();
    }

    #[Route('/', name: 'app_terms_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5], requirements:["taxonomy"=>"^[a-zA-Z0-9]+$"])]
    public function index(TermsRepository $termsRepository,$taxonomy): Response
    {
        // get list terms
        $terms = $termsRepository->findByTaxonomy($taxonomy);
        $terms = json_decode($this->serializer->serialize($terms, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // create term form
        $term = new Terms();
        $form = $this->createForm(TermsType::class, $term,['taxonomy'=>$taxonomy]);
        // check taxonomy item
        $taxonomy_object = $this->em->getRepository(Taxonomy::class)->findOneBy(array('slug_taxonomy'=>$taxonomy));
        return $this->render('admin/terms/index.html.twig', [
            'terms' => $terms,
            'taxonomy'=>$taxonomy,
            'form'=>$form,
            'term'=>$term,
            'taxonomy_object'=>$taxonomy_object,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_terms_edit', methods: ['GET', 'POST'], options:["action"=>"Modifier","order"=>5], requirements:["id"=>"[0-9]+","taxonomy"=>"^[a-zA-Z0-9]+$"])]
    public function edit(Request $request, Terms $term, EntityManagerInterface $entityManager,$taxonomy): Response
    {
        $form = $this->createForm(TermsType::class, $term);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_terms_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/terms/edit.html.twig', [
            'term' => $term,
            'form' => $form,
        ]);
    }
}
