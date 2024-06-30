<?php

namespace App\Controller\Admin\Api;

use App\Entity\Options;
use Cocur\Slugify\Slugify;
use App\Form\PostModalsType;
use App\Entity\Configuration;
use App\Entity\{ModelesPost};
use App\Repository\PostModalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\{PostMetaFields,Images,PostType,Post,PostMeta,Terms,Revision};

#[Route('/api/options')]
class ApiOptionsController  extends AbstractController
{
    private $em;
    private $serializer;
    private $slugify;
    private $url_site;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer,string $url_site)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->url_site = $url_site;
        $this->slugify = new Slugify();
    }
    #[Route('/', name: 'app_options_api_edit_config', methods: ['POST'])]
    public function indexEdit(Request $request): Response
    {
        $this->em->getRepository(Options::class)->deleteAllRecords();
        //sleep(1);
        $this->em->getRepository(Options::class)->resetAutoIncrement();
        // 1 - create config json
        $option = new Options();
        $option->setOptionName('option_json');
        $option->setOptionValue($request->request->get('config_data'));
        $this->em->persist($option);
       
        $config_data_list = json_decode($request->request->get('config_data'),true);
        
        // add new all post meta
        if(!empty($config_data_list)){
            foreach($config_data_list as $key=>$value){
                if(is_array($value)){
                    foreach($value as $k=>$v){
                        $new_meta = new Options();
                        $new_meta->setOptionName($k);
                        $new_meta->setOptionValue($v);
                        $this->em->persist($new_meta);
                    }
                }
            }
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>'options mis à jour avec succés']);
    }
}
