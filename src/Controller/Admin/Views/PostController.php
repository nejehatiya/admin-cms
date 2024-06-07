<?php

namespace App\Controller\Admin\Views;

use App\Entity\{Post,TemplatePage,Taxonomy,PostMetaFields,ModelesPost};
use App\Form\PostSingleType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
#[Route('/post/{post_type}')]
class PostController extends AbstractController
{
    private $em;
    private $serializer;
    const POST_STATUS = ['Publié','Corbeille','Brouillon'];
    public $current_themes;
    public $assets_front_directory;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,SerializerInterface $serializer,string $current_themes,string $assets_front_directory)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->current_themes = $current_themes;
        $this->assets_front_directory = $assets_front_directory;
    }

    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository,$post_type): Response
    {
        $posts = $postRepository->findAll();
        $posts = json_decode($this->serializer->serialize($posts, 'json', ['groups' =>['post_data'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        return $this->render('admin/post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request,$post_type): Response
    {
        $post = new Post();
        // get template list by post type
        $template_pages = $this->em->getRepository(TemplatePage::class)->findByPostType($post_type);
        $template_pages = json_decode($this->serializer->serialize($template_pages, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        //  get category by taxonomy by pos type
        $taxonomy = $this->em->getRepository(Taxonomy::class)->findByPostType($post_type);
        $taxonomy = json_decode($this->serializer->serialize($taxonomy, 'json', ['groups' =>['show_data'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        // get all post meta fields by post type
        $postmeta_forms = $this->em->getRepository(PostMetaFields::class)->findByPostType($post_type);
        $postmeta_forms = json_decode($this->serializer->serialize($postmeta_forms, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // get all modele post by post type
        $all_post_modals = $this->em->getRepository(ModelesPost::class)->findByPostType($post_type);
        $all_post_modals = json_decode($this->serializer->serialize($all_post_modals, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // 
        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'template_pages'=>$template_pages,
            'taxonomies'=>$taxonomy,
            'postmeta_forms'=>$postmeta_forms,
            'post_modals'=>$all_post_modals,
            'post_type'=>$post_type,
            'post_status'=>self::POST_STATUS,
            'current_themes'=>$this->current_themes,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager,$post_type): Response
    {
        // get template list by post type
        $template_pages = $this->em->getRepository(TemplatePage::class)->findByPostType($post_type);
        $template_pages = json_decode($this->serializer->serialize($template_pages, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        //  get category by taxonomy by pos type
        $taxonomy = $this->em->getRepository(Taxonomy::class)->findByPostType($post_type);
        $taxonomy = json_decode($this->serializer->serialize($taxonomy, 'json', ['groups' =>['show_data'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        // get all post meta fields by post type
        $postmeta_forms = $this->em->getRepository(PostMetaFields::class)->findByPostType($post_type);
        $postmeta_forms = json_decode($this->serializer->serialize($postmeta_forms, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        // get all modele post by post type
        $all_post_modals = $this->em->getRepository(ModelesPost::class)->findByPostType($post_type);
        $all_post_modals = json_decode($this->serializer->serialize($all_post_modals, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        //var_dump($post);exit;
        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'template_pages'=>$template_pages,
            'taxonomies'=>$taxonomy,
            'postmeta_forms'=>$postmeta_forms,
            'post_modals'=>$all_post_modals,
            'post_type'=>$post_type,
            'post_status'=>self::POST_STATUS,
            'current_themes'=>$this->current_themes,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager,$post_type): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
