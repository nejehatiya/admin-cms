<?php

namespace App\Controller\Admin\Views;

use App\Entity\{Menu,Emplacement,PostMetaFields};
use App\Form\{MenuType,EmplacementType};
use App\Repository\{MenuRepository,EmplacementRepository,PostMetaFieldsRepository};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('/menu', options:["need_permession"=>true,"module"=>"Menu", "method"=>["Afficher","Ajouter","Modifier","Supprimer"]])]
class MenuController extends AbstractController
{
    private $serializer;
    private $slugify;
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        //$this->slugify = new Slugify();
    }

    #[Route('/', name: 'app_menu_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5])]
    public function index(MenuRepository $menuRepository,PostMetaFieldsRepository $PostMetaFieldsRepository,EmplacementRepository $emplacementRepository): Response
    {
        // menu list
        $menus = $menuRepository->findAll();
        $menus = json_decode($this->serializer->serialize($menus, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // emplacement list
        $emplacements = $emplacementRepository->findAll();
        $emplacements = json_decode($this->serializer->serialize($emplacements, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // get all template menu by menu builder post type
        $template_forms = $PostMetaFieldsRepository->findByMenuBuilder();
        return $this->render('admin/menu/index.html.twig', [
            'menus' => $menus,
            'emplacements'=>$emplacements,
            'template_forms'=>$template_forms,
        ]);
    }

    #[Route('/new', name: 'app_menu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        return $this->render('admin/menu/new.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_menu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        return $this->render('admin/menu/edit.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_menu_delete', methods: ['POST'])]
    public function delete(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        
        return $this->redirectToRoute('app_menu_index', [], Response::HTTP_SEE_OTHER);
    }
}
