<?php

namespace App\Controller\Admin\Views;

use App\Entity\Emplacement;
use App\Form\EmplacementType;
use App\Entity\PostMetaFields;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmplacementRepository;
use App\Repository\OptionsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/options', options:["need_permession"=>true,"module"=>"Options", "method"=>["Afficher"]])]
class OptionsController extends AbstractController
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
    #[Route('/', name: 'app_options_index', methods: ['GET'], options:["action"=>"Afficher","order"=>5])]
    public function index(OptionsRepository $OptionsRepository): Response
    {
        // get data form
        $data_set = $OptionsRepository->findByKey('option_json');
        // get configuration field list
        $forms  = $this->em->getRepository(PostMetaFields::class)->findByPostType("option-builder");
        
        // render form configuration
        return $this->render('admin/options/index.html.twig',[
            'fields'=>!empty($forms)?json_decode($forms[0]->getFields()):[],
            'data_set'=>!empty($data_set)?$data_set->getOptionValue():null,
            'is_options'=>true,
        ]);
    }
}
