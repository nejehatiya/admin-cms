<?php

namespace App\Controller\Admin\Views;

use App\Entity\TemplatePage;
use App\Form\TemplatePageType;
use App\Repository\TemplatePageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('/template-page', options:["need_permession"=>true,"module"=>"Template Page", "method"=>["Afficher","Ajouter","Modifier","Supprimer"]])]
class TemplatePageController extends AbstractController
{
    private $serializer;
    private $slugify;
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        //$this->slugify = new Slugify();
    }
    #[Route('/', name: 'app_template_page_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5])]
    public function index(TemplatePageRepository $templatePageRepository): Response
    {
        $template_pages = $templatePageRepository->findAll();
        $template_pages = json_decode($this->serializer->serialize($template_pages, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        return $this->render('admin/template_page/index.html.twig', [
            'template_pages' => $template_pages,
        ]);
    }

    #[Route('/new', name: 'app_template_page_new', methods: ['GET', 'POST'], options:["action"=>"Ajouter","order"=>4])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $templatePage = new TemplatePage();
        $form = $this->createForm(TemplatePageType::class, $templatePage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($templatePage);
            $entityManager->flush();

            return $this->redirectToRoute('app_template_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/template_page/new.html.twig', [
            'template_page' => $templatePage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_template_page_edit', methods: ['GET', 'POST'], options:["action"=>"Modifier","order"=>1], requirements:["id"=>"[0-9]+"])]
    public function edit(Request $request, TemplatePage $templatePage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TemplatePageType::class, $templatePage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_template_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/template_page/edit.html.twig', [
            'template_page' => $templatePage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_template_page_delete', methods: ['POST'], options:["action"=>"Supprimer","order"=>3], requirements:["id"=>"[0-9]+"])]
    public function delete(Request $request, TemplatePage $templatePage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$templatePage->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($templatePage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_template_page_index', [], Response::HTTP_SEE_OTHER);
    }
}
