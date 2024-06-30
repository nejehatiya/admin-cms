<?php

namespace App\Controller\Admin\Api;

use Cocur\Slugify\Slugify;
use App\Entity\{User,Roles,Images};
use App\Form\PostModalsType;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/api/users')]
class ApiUserController extends AbstractController
{
    private $em;
    private $serializer;
    private $slugify;
    public $password_hasher;
    // init constructor
    public function __construct(EntityManagerInterface $EntityManagerInterface,UserPasswordHasherInterface $userPasswordHasher,SerializerInterface $serializer)
    {
        $this->em = $EntityManagerInterface;
        $this->serializer = $serializer;
        $this->slugify = new Slugify();
        $this->password_hasher = $userPasswordHasher;
    }
    #[Route('/check-email', name: 'app_user_check_index_email', methods: ['POST'])]
    public function indexCheckEmail(Request $request): Response
    {
        // get email from request
        $email = $request->request->get('email');

        // check validity email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return $this->json(['success'=>false,'message'=>'email est invalid']);
        }
        // get name from request
        $id = (int)$request->request->get('id');
        // check if name is existe
        $check_email = $this->em->getRepository(User::class)->findByEmail($email,$id);
        return $this->json(['success'=>empty($check_email),'message'=>empty($check_email)?'email autorisé':'email existe déja']);
    }
    
    #[Route('/new', name: 'app_user_api_new_yyy_ss', methods: ['POST'])]
    public function indexNew(Request $request): Response
    {
        return $this->indexEdit($request,null);
    }
    #[Route('/edit-user/{id}', name: 'app_user_api_edit_api_test', methods: ['POST'])]
    public function indexEdit(Request $request,$id): Response
    {
        // get email from request
        $email = $request->request->get('email');
        // check validity email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return $this->json(['success'=>false,'message'=>'email est invalid']);
        }
        // check if email is existe
        $check_email = $this->em->getRepository(User::class)->findByEmail($email,$id);
        if(!empty($check_email)){
            return $this->json(['success'=>false,'message'=>'email existe déja']);
        }
        // get nom , prenom , image from request
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $image_profile = (int)$request->request->get('image_profile');
        $active = $request->request->get('active');
        $roles = json_decode($request->request->get('roles_user'),true);
        $password = $request->request->get('password');
        // start role creation
        $user = $id ?  $this->em->getRepository(User::class)->find($id) : new User() ;
        if($id && empty($user)){
            return $this->json(['success'=>false,'message'=>'user n\'existe pas']);
        }
        // start set data
        $user->setFirstName($nom);
        $user->setLastName($prenom);
        $user->setStatus($active);
        $user->setEmail($email);
        // set images 
        if($image_profile){
            $image = $this->em->getRepository(Images::class)->find($image_profile);
            if($image){
                $user->setImageProfil($image->getId());
            }
        }
        // delete old roles
        if($id){
            foreach($user->getRolesUser() as $role_ele){
                $user->removeRolesUser($role_ele);
            }
        }
        // set roles
        if(!empty($roles)){
            // find role user;
            $role_list = [];
            foreach($roles as $value){
                $role = $this->em->getRepository(Roles::class)->find($value);
                if(!empty($role)){
                    // set user roles
                    $user->addRolesUser($role);
                    
                    $role_list[]  = $role->getRole();
                }
            }
            $user->setRoles($role_list);
        }else{
            $roles = ['ROLE_USER'];
            $user->setRoles($roles);
            // find role user
            $role = $this->em->getRepository(Roles::class)->findOneBy(array('role'=>'ROLE_USER'));
            if(!empty($role)){
                // set user roles
                $user->addRolesUser($role);
            }
        }
        // set passwor if edit and strlen(password)
        if(strlen($password)){
            $hash_password = $this->password_hasher->hashPassword(
                $user,
                $password
            );
            $user->setPassword($hash_password);
        }
        // is create
        if(!$id){
            $this->em->persist($user);
        }
        // publish in database
        $this->em->flush();
        return $this->json(['success'=>true,'message'=>$id ?'user mis à jour avec succés':'user créer avec succés']);
    }

    #[Route('/get/{id}', name: 'app_user_api_edit_dscsdcsd_ccc', methods: ['GET'])]
    public function indexGetData(Request $request,$id): Response
    {
        
        // get modele post
        $user = $this->em->getRepository(User::class)->find($id);
        $user = json_decode($this->serializer->serialize($user, 'json', ['groups' =>['show_api'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']), true);
        
        if(empty($user)){
            return $this->json(['success'=>false,'message'=>'user n\'existe pas']);
        }
        
        return $this->json(['success'=>true,'user'=>$user]);
    }
}
