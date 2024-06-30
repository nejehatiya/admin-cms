<?php

namespace App\Controller\Admin\Views;

use App\Entity\PostModals;
use App\Form\PostModalsType;
use App\Repository\PostModalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    #[Route('/', name: 'app_admin_dashbord_index', methods: ['GET'])]
    public function index(): Response
    {
        if(!$this->getUser()){
           return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }
        return $this->render('admin/admin.html.twig', []);
    }
}
