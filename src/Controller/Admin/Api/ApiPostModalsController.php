<?php

namespace App\Controller\Admin\Api;

use App\Entity\PostModals;
use App\Form\PostModalsType;
use App\Repository\PostModalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/api/post/modals')]
class ApiPostModalsController extends AbstractController
{
    // init constructor
    #[Route('/new', name: 'app_post_modals_index', methods: ['GET'])]
    public function indexNew(): Response
    {
        return $this->json(['success'=>true,]);
    }
}
