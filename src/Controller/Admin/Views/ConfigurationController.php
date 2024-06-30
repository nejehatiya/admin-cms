<?php

namespace App\Controller\Admin\Views;

use App\Entity\Emplacement;
use App\Form\EmplacementType;
use App\Entity\PostMetaFields;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmplacementRepository;
use App\Repository\ConfigurationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/configuration', options:["need_permession"=>true,"module"=>"Configuration", "method"=>["Afficher"]])]
class ConfigurationController extends AbstractController
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
    }
    #[Route('/', name: 'app_configuration_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5])]
    public function index(ConfigurationRepository $configurationRepository): Response
    {
        // get data form
        $data_set = $configurationRepository->findByKey('configuration_json');
        // get configuration field list
        $forms  = $this->em->getRepository(PostMetaFields::class)->findByPostType("configuration-builder");
        
        // render form configuration
        return $this->render('admin/configuration/index.html.twig',[
            'fields'=>!empty($forms)?json_decode($forms[0]->getFields()):[],
            'data_set'=>!empty($data_set)?$data_set->getConfigValue():null,
        ]);
    }
}
