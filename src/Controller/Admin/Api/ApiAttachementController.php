<?php

namespace App\Controller\Admin\Api;

use App\Entity\{ModelesPost,Images,PostType};
use App\Form\PostModalsType;
use App\Repository\PostModalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use App\Service\UploadAttachement;
#[Route('/api/attachement')]
class ApiAttachementController extends AbstractController
{
    private $em;
    private $serializer;
    private $upload_file_service;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer,UploadAttachement $upload_file_service)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->upload_file_service = $upload_file_service;
    }
    #[Route('/upload-file', name: 'app_attachement_upload_file', methods: ['POST'])]
    public function indexUploadAttachement(Request $request): Response
    {
        $file = $request->files->get('file');
        $this->upload_file_service->uploadImage($file);
        return $this->json(['success'=>true,'message'=>""]);
    }
}
