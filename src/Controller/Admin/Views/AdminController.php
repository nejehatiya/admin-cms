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

class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_modals_index', methods: ['GET'])]
    public function index(PostModalsRepository $postModalsRepository): Response
    {
        return $this->render('admin/admin.html.twig', []);
    }
}
